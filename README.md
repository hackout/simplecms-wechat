# SimpleCMS WeChat

A Laravel 12 compatible WeChat Mini Program package that provides:

- Mini Program code-to-session exchange
- WeChat message callback integration
- Subscription message sending
- Helper methods for phone number and user-related operations
- Laravel Service Provider + Facade registration

简体中文 / English

---

## Overview 概览

This package encapsulates the common WeChat Mini Program backend operations for Laravel applications, making it easy to integrate login, server callback processing, and subscription notifications.

这个包封装了 Laravel 应用中常用的微信小程序后端能力，方便接入登录、服务端回调处理以及订阅消息推送。

## Compatibility 兼容性

- PHP: ^8.2
- Laravel: ^12.0
- EasyWeChat: ^6.7

## Installation 安装

```bash
composer require simplecms/wechat
```

## Configuration 配置

Add the following values to `.env`:

```bash
WECHAT_PROGRAM_APPID="Mini Program APPID"
WECHAT_PROGRAM_SECRET="Mini Program secret"
```

Add to `.env` 中：

```bash
WECHAT_PROGRAM_APPID="小程序 APPID"
WECHAT_PROGRAM_SECRET="小程序 secret"
```

The package merges its config automatically into Laravel as `wechat.program`:

```php
'program' => [
    'appid' => env('WECHAT_PROGRAM_APPID', ''),
    'secret' => env('WECHAT_PROGRAM_SECRET', ''),
],
```

配置文件会自动合并到 Laravel 配置项 `wechat.program`：

```php
'program' => [
    'appid' => env('WECHAT_PROGRAM_APPID', ''),
    'secret' => env('WECHAT_PROGRAM_SECRET', ''),
],
```

## Basic Usage 基础用法

### 1. Exchange code for session / 通过 code 获取 session

```php
use SimpleCMS\Wechat\Facades\MiniProgram;

$session = MiniProgram::codeToSession($code);

if ($session->has('openid')) {
    $openid = $session->get('openid');
}
```

### 2. Send a subscription message / 发送订阅消息

```php
use SimpleCMS\Wechat\Facades\MiniProgram;

MiniProgram::postMessage(
    openId: 'oX123456789',
    templateId: 'TEMPLATE_ID',
    data: [
        'thing1' => 'Order shipped',
        'thing2' => '2026-08-31',
    ],
    state: 'formal'
);
```

```php
use SimpleCMS\Wechat\Facades\MiniProgram;

MiniProgram::postMessage(
    openId: 'oX123456789',
    templateId: 'TEMPLATE_ID',
    data: [
        'thing1' => '订单已发货',
        'thing2' => '2026-08-31',
    ],
    state: 'formal'
);
```

### 3. Get phone number / 获取手机号

```php
use SimpleCMS\Wechat\Facades\MiniProgram;

$result = MiniProgram::getPhoneNumber($code);
```

## Event Listeners 事件监听

### Login event / 登录事件

```php
use Illuminate\Support\Facades\Event;

Event::listen('plugin.wechat.code2session', function (string $openid) {
    // Handle successful login
    // Example: write user, create JWT, bind account
});
```

```php
use Illuminate\Support\Facades\Event;

Event::listen('plugin.wechat.code2session', function (string $openid) {
    // 处理登录成功后的业务逻辑
    // 例如：写入用户表、生成 JWT、绑定微信用户
});
```

### WeChat callback event / 微信消息回调事件

```php
use Illuminate\Support\Facades\Event;

Event::listen('plugin.wechat.message', function (string $eventName, array $message, \Closure $next) {
    // $eventName can be: subscribe, unsubscribe, text, image, location, link, etc.
    // $message is the callback payload from WeChat

    return $next($message);
});
```

```php
use Illuminate\Support\Facades\Event;

Event::listen('plugin.wechat.message', function (string $eventName, array $message, \Closure $next) {
    // $eventName 可为：subscribe / unsubscribe / text / image / location / link 等
    // $message 为微信回调消息数组

    return $next($message);
});
```

## Routes 路由

The package registers the following routes automatically:

包内已自动注册路由：

```http
POST /api/wechat/token/{code}
POST /api/wechat/serve
```

### Usage 说明

- `/api/wechat/token/{code}`: used for Mini Program login to exchange openid / session
- `/api/wechat/serve`: used for WeChat server callback

- `/api/wechat/token/{code}`：用于小程序登录换取 openid / session
- `/api/wechat/serve`：用于微信消息服务器回调

## Facade 门面

```php
use SimpleCMS\Wechat\Facades\MiniProgram;
```

You can call it directly through the Facade:

可直接通过 Facade 调用：

```php
MiniProgram::codeToSession($code);
MiniProgram::serverStart();
MiniProgram::postMessage(...);
```

## Notes 注意事项

- This package only provides the WeChat Mini Program backend infrastructure; it does not include frontend page code.
- If you need custom business logic for events, listen to the relevant event names in your app service provider.
- Routes no longer depend on `slug_regex()`, making them more compatible with modern Laravel runtimes.
- The `state` parameter in `postMessage()` is properly passed to the WeChat API.

- 本包仅提供微信小程序基础能力封装，不包含前端页面代码。
- 若需要自定义事件业务逻辑，请在应用的 `AppServiceProvider` 或事件注册处监听对应事件名。
- 路由不再依赖 `slug_regex()`，适配更现代的 Laravel 运行环境。
- `postMessage()` 中 `state` 参数已正确传递给微信官方接口。

## License 许可协议

MIT License

本项目使用 MIT License。
