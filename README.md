# 微信小程序小插件

仅处理 code、openid、订阅消息推送及微信后端服务回调。

## 安装

适配 Laravel 12 及更高版本，最低要求为 PHP 8.2。

```bash
composer require simplecms/wechat
```

## 配置 .env

```bash
WECHAT_PROGRAM_APPID="小程序 APPID"
WECHAT_PROGRAM_SECRET="小程序 secret"
```

## 代码示例

```php
use SimpleCMS\Wechat\Facades\MiniProgram;

// 交换登录 code
$session = MiniProgram::codeToSession($code);

// 发送订阅消息
MiniProgram::postMessage(
    openId: 'xxxx',
    templateId: 'xxxxx',
    data: ['thing1' => '测试消息'],
    state: 'formal'
);
```

### 监听登录事件

```php
use Illuminate\Support\Facades\Event;

Event::listen('plugin.wechat.code2session', function (string $openId) {
    // ... 处理小程序登录
});
```

### 监听微信回调事件

```php
use Illuminate\Support\Facades\Event;

Event::listen('plugin.wechat.message', function (string $eventName, array $message, \Closure $next) {
    // $eventName: subscribe / unsubscribe / text / image / location 等
    // ... 处理微信回调消息
    return $next($message);
});
```

### 服务端入口

```php
POST /api/wechat/serve
```

```php
POST /api/wechat/token/{code}
```

## Facades

```php
use SimpleCMS\Wechat\Facades\MiniProgram;
```

## 说明

- 配置会自动合并到 Laravel 的 `wechat` 配置项。
- 路由已修复，不再依赖不存在的 `slug_regex()`。
- `postMessage` 现在正确使用传入的 `state` 参数。
