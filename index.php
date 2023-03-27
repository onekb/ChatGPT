<?php

//require 'vendor/autoload.php';
//use GuzzleHttp\Client;

// 你的key
// your key
$apiKey = 'jjj';

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
    // 重新发起对话 Reinitiate a conversation
    // $chatGpt->clearHistory();
    echo '🐚 ：' . $ret. $text . PHP_EOL . PHP_EOL;
	$updateObj = new \stdClass();
    	$updateObj->result= $ret;;
    	$updateObj->text= $text;
        $updateJson = json_encode($updateObj);
        echo $updateJson;
} else if ($act == 'adsf'){
    echo <<<END
<form action="index.php" method="POST">
<form>
问题：<br>
<input type="text" name="firstname">
<br>
<input type="submit" value="提问">
</form>
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

        //$data = gpt3_5Turbo($history);
        //$data = gpt35($history);
        $data = gpt35Curl($history);
        /*
        if (isset($data['choices'][0]['message'])) {
            $this->addHistory(
                $data['choices'][0]['message']['role'],
                trim($data['choices'][0]['message']['content'])
            );
        }*/

        return $data;
    }

    function gpt3_5Turbo(array $message)
    {
        $http= new GuzzleHttp\Client();
        return json_decode(
            $http->request('POST', 'https://api.openai.com/v1/chat/completions', array(
                'model' => 'gpt-3.5-turbo',
                'messages' => $message,
            ), array(
                    'headers' => $this->getHeaders(),
                )
            )->getBody()->getContents(),
            true
        );
    }
    function gpt35(array $message, $json=true) {
        $url = 'https://api.openai.com/v1/chat/completions';
        $data = array('model' => 'gpt-3.5-turbo', 'messages' => $message);
        if($json){
            $str = 'application/json';
            $data = json_encode($data);
        }else{
            $str = 'application/x-www-form-urlencoded';
            $data = http_build_query($data);
        }
        $header = [
            'header'  => "Content-Type: $str",
            'Authorization' => 'Bearer ' . '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',
        ];
        $options[ 'http' ] = array(
            'timeout' => 10,
            'method'  => 'POST',
            'header'  => $header,
            'content' => $data,
        );
        $context = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }

    function gpt35Curl(array $message, $json=true) {
       $url = "https://api.openai.com/v1/chat/completions";
       $headers = array(
           'Content-Type: application/json',
           'Authorization: Bearer '.'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',
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
       return $result;

    }
?>
