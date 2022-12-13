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

        $data && $options['json'] = $data;

        // 设置代理
        if (Di::has('proxy')) {
            $options['proxy'] = Di::get('proxy');
            $options['verify'] = false;
        }

        return $client->request($method, $url, $options);
    }

}