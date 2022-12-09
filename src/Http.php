<?php

namespace Onekb\ChatGpt;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class Http
{
    public function request($method, $url, $data = null, $options = [])
    {
        /**
         * @var Client $client
         */
        $client = Di::get(Client::class, [
            [
                'cookies' => Di::get(CookieJar::class),
            ],
        ]);

        $options['headers']['user-agent']
            = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36';

        $data && $options['json'] = $data;

        // 设置代理
        if (Di::has('proxy')) {
            $options['proxy'] = Di::get('proxy');
            $options['verify'] = false;
        }

        return $client->request($method, $url, $options);
    }

}