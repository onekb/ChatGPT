<?php

include 'vendor/autoload.php';

use Onekb\ChatGpt\ChatGpt;

// 填任意一个就可以了
$sessionToken = '';
$authorization = '';

// 过了cloudflare的验证后，填写这两者，都必填
$cfClearance = '';// 有效期2小时，过期要换，在Cookie里可以获取
$userAgent = '';// 就是你浏览器UA标识，在header里可以获取

// 设置HTTP代理
//\Onekb\ChatGpt\Di::set('proxy', 'http://127.0.0.1:8899');

$chatGpt = new ChatGpt($sessionToken, $authorization, $userAgent, $cfClearance);

do {
    $input = readline('🐚 问问神奇海螺：');
    if (! $input) {
        continue;
    }
    echo "让我想想。\n";
//    try { // 考虑到可能会$cfClearance超时，先去掉try吧，有需要自行打开
    $result = $chatGpt->ask($input);
    $text = $result['answer'];
//    } catch (\Exception $e) {
//        $text = '可能是因为网络原因，请求中断，你可以再问一次。';
//    }
    echo '🐚 ：' . $text . PHP_EOL . PHP_EOL;
} while (true);
