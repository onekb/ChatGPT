# onekb/ChatGPT

__采用OpenAI GPT3.5模型API[（参考）](https://platform.openai.com/docs/guides/chat)__

__Using OpenAI GPT3.5 model API [(reference)](https://platform.openai.com/docs/guides/chat)__

![](./screenshots/conch.jpeg)
![](./screenshots/demo.png)

# Installing

```php
composer require onekb/chat-gpt
```

# Usage

```php
// 你的key
// your key
$apiKey = 'sk-PXxxxxxxxx';

// 最大提交聊天记录数，内容太多会消耗更多tokens
// The maximum number of submitted chat records, too much content will consume more tokens
$limit = 10;

$chatGPT=new \Onekb\ChatGpt\ChatGpt($apiKey, $limit);

// 简单使用
var_dump($chatGPT->ask('你好'));

// 设置代理
\Onekb\ChatGpt\Di::set('proxy', 'http://127.0.0.1:8899');

// 重新发起对话 Reinitiate a conversation
// $chatGpt->clearHistory();

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
```

# ⚡️ Getting Started

**Step 1. 克隆本项目到本地 Clone this project to local**

```bash
git clone https://github.com/onekb/ChatGPT.git
```

**Step 2. 配置你的环境变量 Configure your environment variables**

首先，将 `start.php` 文件中的 `$apiKey` 字段替换成你自己的 OpenAI API Key

First, replace the `$apiKey` field in the `start.php` file with your own OpenAI API Key

> 你可以在这里找到参数值 👉 [教程](#其他other)

> You can find parameter values here 👉 [tutorial](#其他other)

**Step 3. Hello world!**

最后，你的电脑必须有 PHP 环境，然后在项目根目录下执行以下命令

Finally, your computer must have a PHP environment, and then execute the following command in the project root directory

```bash
composer install --no-dev
```

```php
php start.php
```

就酱，准备好起飞 🚀

full stop, ready to take off 🚀

# 更新日志

3.5.0 2023-03-03
- 改用官方gpt-3.5-turbo模型API接口
- Q：为什么版本直接跳到V3.5？ A：我乐意

2.0.0 2023-02-28
- 重构代码
- 变更获取方式
- 英文readme

1.0.5 2022-12-13
- 修复cloudflare拦截问题，需补全验证信息

1.0.3 2022-12-09

- cookie交给CookieJar维护
- 优化Di管理方式
- 返回谈话ID
- 支持设置谈话参数（继续会话）
- 支持设置代理

1.0.1 2022-12-07

- 初版

# 其他other

获取API Key
Get API Key

登录你的OpenAI账户，访问 https://platform.openai.com/account/api-keys 获取你的API Key
Log in to your OpenAI account and visit https://platform.openai.com/account/api-keys to get your API Key