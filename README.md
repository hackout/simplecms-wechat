# 微信小程序小插件

仅处理code openid 及推送、后端服务

## 安装

```bash
composer require simplecms/wechat
```

## 配置.env

```bash
WECHAT_PROGRAM_APPID="小程序APPID"
WECHAT_PROGRAM_SECRET="小程序secret"
```

## 推送消息

```php
use SimpleCMS\Wechat\Facades\MiniProgram; 
//发送推送消息
return MiniProgram::postMessage(openId:'xxxx',templateId:'xxxxx',data:[]);
```

### 监听后端消息

在```App\Providers\AppServiceProvider```的boot中增加事件监听

```php
use Illuminate\Support\Facades\Event;

//监听登录
//前端URL: /api/wechat/mini/token/{code}
Event::listen('plugin.wechat.code2session',function(string $openId){
           //...Todo..
        });

//监听后端
//前端URL: /api/wechat/serve
Event::listen('plugin.message',function($event='subscribe', $message, \Closure $next){
           //...Todo..
        });
```

## Facades

```php
use SimpleCMS\Wechat\Facades\MiniProgram; #地理位置 
```

## 其他说明

更多操作参考IDE提示
