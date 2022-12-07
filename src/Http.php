<?php

namespace Onekb\ChatGpt;

use GuzzleHttp\Client;

class Http
{
    public function request($method, $url, $data = null, $headers = [])
    {
        /**
         * @var Client $client
         */
        $client = Di::get(Client::class);

        $headers['user-agent']
            = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36';

        return $client->request($method, $url, [
            'headers' => $headers,
            'json' => $data,
        ]);
    }

}