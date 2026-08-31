<?php

namespace SimpleCMS\Wechat\Services;

use Psr\Http\Message\ResponseInterface;
use SimpleCMS\Wechat\Facades\MiniProgram;

class MiniProgramService
{
    /**
     * 获取Code
     *
     * @author Dennis Lui <hackout@vip.qq.com>
     * @param  string     $code
     * @return array|null
     */
    public function getOpenId(string $code): ?array
    {
        $session = MiniProgram::codeToSession($code);

        if (! $session->has('openid') && $session->has('errmsg')) {
            throw new \RuntimeException($session->get('errmsg'));
        }

        return event('plugin.wechat.code2session', $session->get('openid'));
    }

    /**
     * 消息服务
     *
     * @author Dennis Lui <hackout@vip.qq.com>
     * @return ResponseInterface
     */
    public function serve(): ResponseInterface
    {
        return MiniProgram::serverStart();
    }
}
