<?php

//require 'vendor/autoload.php';
//use GuzzleHttp\Client;


// 最大提交聊天记录数，内容太多会消耗更多tokens
// The maximum number of submitted chat records, too much content will consume more tokens
$limit = 2;

//$chatGpt = new ChatGpt($apiKey, $limit);

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

$act = 'question';
$input = $_POST['question'];
if ($act == 'question') {
    try {
        $result = ask($input);
        $text = $result['choices'][0]['message']['content'];
	    $ret = 0;
    } catch (\Exception $e) {
        $text = '可能是因为网络原因或速率限制，请求中断，你可以再问一次。'; // It may be due to network reasons or rate limiting, the request is interrupted, you can ask again.
	    $ret = 1;
	    echo $e;
    }
    echo <<<END
<form action="gpt.php" method="POST">
<form>
问题：
<input type="text" name="question">
<input type="submit" value="提问"> </form>
问：$input
<br>
答：$text
<br>
END;
} else {
    echo 'act'.$act.'act';
}

    /**
     * @param string $content
     *
     * @return array
     */
    function ask($content)
    {
        $history = array(
            'role' => 'user',
            'content' => $content,
        );

        $data = gpt35Curl($history);
        return $data;
    }


    function gpt35Curl(array $message, $json=true) {
       $url = "https://api.openai.com/v1/chat/completions";
       $headers = array(
           'Content-Type: application/json',
           'Authorization: Bearer '.'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',
           //'Authorization: Bearer '.'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',
           //'Authorization: Bearer '.'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',
       );

       $data = array(
           'model' => 'gpt-3.5-turbo',
           'messages' => array(
                $message
           ),
           'temperature' => 0.7
       );

       $ch = curl_init($url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

       $result = curl_exec($ch);
       curl_close($ch);
       $ret = json_decode($result, true);
       return $ret;
    }
?>
