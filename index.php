<?php

include 'vendor/autoload.php';

use Onekb\ChatGpt\ChatGpt;

// 你的key
// your key
$apiKey = 'jjj';

// 最大提交聊天记录数，内容太多会消耗更多tokens
// The maximum number of submitted chat records, too much content will consume more tokens
$limit = 2;

$chatGpt = new ChatGpt($apiKey, $limit);

// 设置HTTP代理（选填） Set HTTP proxy (optional)
//\Onekb\ChatGpt\Di::set('proxy', 'http://127.0.0.1:1087');

header("Content-Type:text/html;charset=utf-8");
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

$act = $_POST['action'];
if ($act == 'q')
	$input= $_POST['input'];
    try {
        $result = $chatGpt->ask($input);
        $text = $result['choices'][0]['message']['content'];
	$ret = 0;
    } catch (\Exception $e) {
        $text = '可能是因为网络原因或速率限制，请求中断，你可以再问一次。'; // It may be due to network reasons or rate limiting, the request is interrupted, you can ask again.
	$ret = 1;
    }
    // 重新发起对话 Reinitiate a conversation
    // $chatGpt->clearHistory();
    echo '🐚 ：' . $text . PHP_EOL . PHP_EOL;
	$updateObj = new \stdClass();
    	$updateObj->result= $ret;;
    	$updateObj->text= $text;
        $updateJson = json_encode($updateObj);
        echo $updateJson;
}

?>
