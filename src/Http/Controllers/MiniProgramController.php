<?php

namespace SimpleCMS\Wechat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\Rules\Enum;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Validator;
use SimpleCMS\Framework\Attributes\ApiName;
use SimpleCMS\Payment\Enums\ChannelEnum;
use SimpleCMS\Wechat\Services\MiniProgramService;
use Symfony\Component\HttpFoundation\JsonResponse;
use SimpleCMS\Payment\Services\Frontend\PaymentService;
use SimpleCMS\Framework\Http\Controllers\BackendController as BaseController;

/**
 * 小程序控制器
 *
 * @author Dennis Lui <hackout@vip.qq.com>
 */
class MiniProgramController extends BaseController
{

    /**
     * 微信小程序
     *
     * @author Dennis Lui <hackout@vip.qq.com>
     * @param  string            $code
     * @param  MiniProgramService $service
     * @return JsonResponse
     */
    #[ApiName(name: '微信小程序-交换token')]
    public function token(string $code, MiniProgramService $service): JsonResponse
    {
        $result = $service->getOpenId($code);
        return $this->success($result);
    }
    /**
     * 微信小程序
     *
     * @author Dennis Lui <hackout@vip.qq.com>
     * @param  Request $request
     * @param  MiniProgramService $service
     * @return JsonResponse|ResponseInterface
     */
    #[ApiName(name: '微信小程序-服务端')]
    public function serve(Request $request, MiniProgramService $service): JsonResponse|ResponseInterface
    {
        return $service->serve();
    }
}
