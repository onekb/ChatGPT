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

    public function __construct($authorization, $apiReverseProxyUrl)
    {
        $this->config = [
            'authorization' => $authorization,
            'apiReverseProxyUrl' => $apiReverseProxyUrl,
        ];

        $this->http = Di::get(Http::class);
    }

    public function setConversation($conversationID, $parentMessageID)
    {
        $this->config['conversationID'] = $conversationID;
        $this->config['parentID'] = $parentMessageID;
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
            $this->config['apiReverseProxyUrl'],
            $queryDatas, // Real body 'https://chat.openai.com/backend-api/conversation'
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
            return null;
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