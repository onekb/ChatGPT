<?php

namespace Onekb\ChatGpt;

class ChatGpt
{
    public $config = [];

    // 聊天记录 Chat history
    public $history = [];

    /**
     * @var Http
     */
    protected $http = null;

    public function __construct($apiKey, $limit)
    {
        $this->config = [
            'apiKey' => $apiKey,
            'limit' => $limit,
        ];

        $this->http = Di::get(Http::class);
    }

    /**
     * @param string $content
     *
     * @return array
     */
    public function ask(string $content): array
    {
        $this->history[] = [
            'role' => 'user',
            'content' => $content,
        ];

        $data = $this->gpt3_5Turbo($this->history);
        if (isset($data['choices'][0]['message'])) {
            $this->addHistory(
                $data['choices'][0]['message']['role'],
                trim($data['choices'][0]['message']['content'])
            );
        }

        return $data;
    }

    public function addHistory(string $role, string $content)
    {
        $this->history[] = [
            'role' => $role,
            'content' => $content,
        ];
        // 去除多余的历史记录
        if (count($this->history) > $this->config['limit'] && $this->config['limit'] > 0) {
            array_shift($this->history);
        }
    }

    public function clearHistory()
    {
        $this->history = [];
    }

    public function gpt3_5Turbo(array $message): array
    {
        return json_decode(
            $this->http->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => $message,
            ], [
                    'headers' => $this->getHeaders(),
                ]
            )->getBody()->getContents(),
            true
        );
    }

    protected function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->config['apiKey'],
        ];
    }
}