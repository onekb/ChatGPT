<?php

include 'vendor/autoload.php';

use Onekb\ChatGpt\ChatGpt;

// 你的key
// your key
$apiKey = 'sk-PXxxxxxxxx';

// 最大提交聊天记录数，内容太多会消耗更多tokens
// The maximum number of submitted chat records, too much content will consume more tokens
$limit = 10;

$chatGpt = new ChatGpt($apiKey, $limit);

// 设置HTTP代理（选填） Set HTTP proxy (optional)
\Onekb\ChatGpt\Di::set('proxy', 'http://127.0.0.1:1087');

// 自定义聊天记录 Custom chat history
//$chatGpt->history = [
//    [
//        'role' => 'user',
//        'content' => '你好',
//    ],
//    [
//        'role' => 'assistant',
//        'content' => '你好',
//    ],
//];

do {
    $input = readline('🐚 问问神奇海螺：'); // 🐚 Ask the Magic Conch:
    if (!$input) {
        continue;
    }
    echo "让我想想。\n";// Let me think.
    try {
        $result = $chatGpt->ask($input);
        $text = $result['choices'][0]['message']['content'];
    } catch (\Exception $e) {
        $text = '可能是因为网络原因或速率限制，请求中断，你可以再问一次。'; // It may be due to network reasons or rate limiting, the request is interrupted, you can ask again.
    }
    // 重新发起对话 Reinitiate a conversation
    // $chatGpt->clearHistory();
    echo '🐚 ：' . $text . PHP_EOL . PHP_EOL;
} while (true);
