<?php
namespace SimpleCMS\Wechat\Packages;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use EasyWeChat\MiniApp\Application;
use Psr\Http\Message\ResponseInterface;

class MiniProgram
{
    protected $config;

    protected $app;

    public function __construct()
    {
        $this->initConfig();
        $this->app = new Application($this->config);
    }

    protected function initConfig(): void
    {
        $this->config = [
            'app_id' => config('wechat.program.appid'),
            'secret' => config('wechat.program.secret'),
            'http' => [
                'throw' => false,
                'timeout' => 5.0,

                'retry' => true,
            ],
        ];
    }

    /**
     * code交换session
     *
     * @author Dennis Lui <hackout@vip.qq.com>
     * @param  string     $code
     * @return Collection
     */
    public function codeToSession(string $code): Collection
    {
        $utils = $this->app->getUtils();
        try {
            $response = $utils->codeToSession($code);
        } catch (\Exception $e) {
            $response = json_decode(Str::afterLast($e->getMessage(), 'code2Session error:'), true);
        }
        return collect($response);
    }

    /**
     * 解密数据
     *
     * @author Dennis Lui <hackout@vip.qq.com>
     * @param  string     $sessionKey
     * @param  string     $iv
     * @param  string    $encryptedData
     * @return Collection
     */
    public function decryptSession(string $sessionKey, string $iv, string $encryptedData): Collection
    {
        $utils = $this->app->getUtils();
        try {
            $response = $utils->decryptSession($sessionKey, $iv, $encryptedData);
        } catch (\Exception $e) {
            $response = json_decode(Str::afterLast($e->getMessage(), 'code2Session error:'), true);
        }
        return collect($response);
    }

    /**
     * 发送订阅信息
     *
     * @author Dennis Lui <hackout@vip.qq.com>
     * @param  string $openId
     * @param  string $templateId
     * @param  array  $data
     * @param  string $state
     * @return void
     */
    public function postMessage(string $openId, string $templateId, array $data = [], string $state = 'formal'): void
    {
        $client = $this->app->getClient();

        $client->post('/cgi-bin/message/subscribe/send', [
            'json' => [
                'touser' => $openId,
                'template_id' => $templateId,
                'page' => 'pages/detail/detail?id=',
                'data' => $this->convertDataForMessage($data),
                'miniprogram_state' => 'formal',
                'lang' => 'zh_CN'
            ]
        ]);
    }

    protected function convertDataForMessage(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = [
                'value' => $value
            ];
        }
        return $result;
    }

    /**
     * 服务端监听
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function serverStart(): ResponseInterface
    {
        $server = $this->app->getServer();
        $server->addEventListener('subscribe', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'subscribe', message: $message, closure: $next);
        });
        $server->addEventListener('unsubscribe', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'unsubscribe', message: $message, closure: $next);
        });
        $server->addEventListener('SCAN', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'SCAN', message: $message, closure: $next);
        });
        $server->addEventListener('LOCATION', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'LOCATION', message: $message, closure: $next);
        });
        $server->addEventListener('CLICK', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'CLICK', message: $message, closure: $next);
        });
        $server->addEventListener('VIEW', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'VIEW', message: $message, closure: $next);
        });
        $server->addMessageListener('text', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'text', message: $message, closure: $next);
        });
        $server->addMessageListener('image', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'image', message: $message, closure: $next);
        });
        $server->addMessageListener('voice', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'voice', message: $message, closure: $next);
        });
        $server->addMessageListener('video', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'video', message: $message, closure: $next);
        });
        $server->addMessageListener('shortvideo', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'shortvideo', message: $message, closure: $next);
        });
        $server->addMessageListener('location', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'location', message: $message, closure: $next);
        });
        $server->addMessageListener('link', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'link', message: $message, closure: $next);
        });
        $server->addMessageListener('voice', function ($message, \Closure $next) {
            return event('plugin.wechat.message', event: 'voice', message: $message, closure: $next);
        });
        return $server->serve();
    }

    /**
     * 小程序获取手机号码
     * @param string $code
     * @return array
     */
    public function getPhoneNumber(string $code): array
    {
        $client = $this->app->getClient();
        $data = [
            'code' => $code,
        ];
        $result = $client->postJson('wxa/business/getuserphonenumber', $data);
        return $result->toArray(true);
    }
}