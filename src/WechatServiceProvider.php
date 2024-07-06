<?php

namespace SimpleCMS\Wechat;

use Illuminate\Support\ServiceProvider;
use SimpleCMS\Wechat\Http\Controllers\MiniProgramController;

class WechatServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->bootConfig();
        $this->loadFacades();
        $this->loadRoutes();
    }

    /**
     * 绑定Facades
     *
     * @author Dennis Lui <hackout@vip.qq.com>
     * @return void
     */
    protected function loadFacades(): void
    {
        $this->app->bind('mini_program', fn() => new \SimpleCMS\Wechat\Packages\MiniProgram());
    }


    /**
     * 加载路由
     *
     * @author Dennis Lui <hackout@vip.qq.com>
     * @return void
     */
    protected function loadRoutes(): void
    {
        $router = $this->app['router'];
        $router->post('/api/wechat/token/{code}', [MiniProgramController::class, 'token'])->where(['code' => slug_regex()])->name('plugins.wechat.token');
        $router->post('/api/wechat/serve', [MiniProgramController::class, 'serve'])->name('plugins.wechat.serve');
    }


    /**
     * 初始化配置文件
     * @return void
     */
    protected function bootConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/wechat.php' => config_path('wechat.php'),
        ], 'simplecms');
    }
}
