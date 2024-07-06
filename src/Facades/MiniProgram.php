<?php

namespace SimpleCMS\Wechat\Facades;

use Illuminate\Support\Facades\Facade;

/**
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
