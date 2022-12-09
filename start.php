<?php

include 'vendor/autoload.php';

use Onekb\ChatGpt\ChatGpt;

// 填任意一个就可以了
$sessionToken = '';
$authorization = '';

$chatGpt = new ChatGpt($sessionToken, $authorization);

do {
    $input = readline('🐚 问问神奇海螺：');
    if (! $input) {
        continue;
    }
    echo "让我想想。\n";
    try {
        $text = $chatGpt->ask($input);
    } catch (\Exception $e) {
        $text = '可能是因为网络原因，请求中断，你可以再问一次。';
    }
    echo '🐚 ：' . $text . PHP_EOL . PHP_EOL;
} while (true);
