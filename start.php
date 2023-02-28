<?php

include 'vendor/autoload.php';

use Onekb\ChatGpt\ChatGpt;

$authorization = 'Bearer eyJhbGc...... your authorization token here ......';

// 代理地址 Proxy address
$apiReverseProxyUrl = 'https://gpt.pawan.krd/backend-api/conversation';
// or 'https://chat.duti.tech/api/conversation' or more


$chatGpt = new ChatGpt($authorization, $apiReverseProxyUrl);

// 设置HTTP代理（选填） Set HTTP proxy (optional)
\Onekb\ChatGpt\Di::set('proxy', 'http://127.0.0.1:8899');

// 设置谈话参数（继续会话） Set talk parameters (continue session)
//$chatGpt->setConversation($yourConversationID, $yourParentMessageID);

do {
    $input = readline('🐚 问问神奇海螺：'); // 🐚 Ask the Magic Conch:
    if (! $input) {
        continue;
    }
    echo "让我想想。\n";// Let me think.
    try {
        $result = $chatGpt->ask($input);
        $text = $result['answer'];
    } catch (\Exception $e) {
        $text = '可能是因为网络原因，请求中断，你可以再问一次。'; // Maybe because of network reasons, the request is interrupted, you can ask again.
    }
    echo '🐚 ：' . $text . PHP_EOL . PHP_EOL;
} while (true);
