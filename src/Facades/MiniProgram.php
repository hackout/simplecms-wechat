<?php

namespace SimpleCMS\Wechat\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Psr\Http\Message\ResponseInterface serverStart()
 * @method \Illuminate\Support\Collection codeToSession(string $code)
 * 
 * @see \SimpleCMS\Wechat\Packages\Wechat\MiniProgram
 */
class MiniProgram extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mini_program';
    }
}
