<?php

include 'vendor/autoload.php';

use Onekb\ChatGpt\ChatGpt;

// 填任意一个就可以了
$sessionToken = '';
$authorization = '';

$chatGpt = new ChatGpt($sessionToken, $authorization);

do {
    $input = readline('问问神奇海螺：');
    echo "让我想想。\n";
    $text = $chatGpt->ask($input);
    echo $text . PHP_EOL . PHP_EOL;
} while (true);
