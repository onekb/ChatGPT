<?php

namespace Onekb\ChatGpt;

use GuzzleHttp\Cookie\CookieJar;

class ChatGpt
{
    public $config = [];

    /**
     * @var Http
     */
    protected $http = null;

    public function __construct($sessionToken, $authorization)
    {
        $this->config = [
            'sessionToken' => $sessionToken,
            'authorization' => $authorization,
        ];

        if ($this->config['sessionToken']) {
            /**
             * @var \GuzzleHttp\Cookie\CookieJar $cookieJar
             */
            $cookieJar = Di::get(CookieJar::class);
            $cookieJar->setCookie(
                new \GuzzleHttp\Cookie\SetCookie([
                    'Name' => '__Secure-next-auth.session-token',
                    'Value' => $this->config['sessionToken'],
                    'Domain' => 'chat.openai.com',
                    'Path' => '/',
                ])
            );
        }

        $this->http = Di::get(Http::class);
        $this->refreshToken();
    }

    public function setConversation($conversationID, $parentMessageID)
    {
        $this->config['conversationID'] = $conversationID;
        $this->config['parentID'] = $parentMessageID;
    }

    public function refreshToken($force = false)
    {
        if ($force || ! $this->config['sessionToken'] || ! $this->config['authorization']) {
            $response = $this->http->request(
                'GET',
                'https://chat.openai.com/api/auth/session',
                null,
                [
                    'headers' => $this->getHeaders(),
                ]
            );

            // 查找 "set-cookie" 项
            $cookies = [];
            foreach ($response->getHeaders() as $header => $value) {
                if ($header === 'set-cookie') {
                    $cookies = explode(',', $value);
                    break;
                }
            }

            // 查找 "__Secure-next-auth.session-token"
            $newSessionCookie = null;
            foreach ($cookies as $cookie) {
                if (strpos($cookie, '__Secure-next-auth.session-token') !== false) {
                    $newSessionCookie = $cookie;
                    break;
                }
            }

            // 从 cookie 中提取会话令牌
            $newSessionToken = null;
            if ($newSessionCookie) {
                $newSessionToken = str_replace(
                    '__Secure-next-auth.session-token=',
                    '',
                    $newSessionCookie
                );
                $newSessionToken = explode('; Path=/;', $newSessionToken)[0];
            }
            $this->config['sessionToken'] = $newSessionToken;
            // 注意：这里只是把令牌保存到配置中，实际上并在 CookieJar 中获取，之后修改配置也不会同步到 CookieJar 中
        }
    }

    /**
     * @param $parts
     *
     * @return array
     */
    public function ask($parts): array
    {
        $queryDatas = [
            'action' => 'next',
            'messages' => [
                [
                    'id' => $this->uuidv4(),
                    'role' => 'user',
                    'content' => ['content_type' => 'text', 'parts' => [$parts]],
                ],
            ],
            'parent_message_id' => $this->config['parentID'] ?? $this->uuidv4(),
            'model' => $this->config['model'] ?? 'text-davinci-002-render',
        ];
        isset($this->config['conversationID'])
        && $queryDatas['conversation_id'] = $this->config['conversationID'];

        $response = $this->http->request(
            'POST',
            'https://chat.openai.com/backend-api/conversation',
            $queryDatas,
            [
                'headers' => $this->getHeaders(),
            ]
        );

        if ($response->getStatusCode() === 200) {
            $datas = $this->getResponseData($response->getBody()->getContents());
            $parentID = $datas['message']['id'];
            $conversationID = $datas['conversation_id'];
            $answer = $datas['message']['content']['parts'][0];
            if ($parentID) {
                $this->config['parentID'] = $parentID;
            }
            if ($conversationID) {
                $this->config['conversationID'] = $conversationID;
            }

            return [
                'answer' => $answer,
                'conversationID' => $conversationID,
                'parentMessageId' => $parentID,
            ];
        }

        return [];
    }

    protected function getResponseData($data)
    {
        $temp = explode("\n\n", $data);
        try {
            if (count($temp) > 4) {
                $dataStr = str_replace('data: ', '', $temp[count($temp) - 5]);

                return json_decode($dataStr, true);
            }
        } catch (Exception $e) {
            return undefined;
        }
    }

    protected function uuidv4()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    protected function getHeaders()
    {
        return [
            'Authorization' => $this->config['authorization'],
        ];
    }
}