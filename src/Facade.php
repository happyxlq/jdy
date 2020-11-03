<?php

/*
 * This file is part of the Kingdee/Jdy.
 *
 * (c) alim <alim@bulutbazar.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\Jdy;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * Class Facade.
 *
 * @author alim <alim@bulutbazar.com>
 */
class Facade extends LaravelFacade
{
    /**
     * 默认为 Server.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'Jdy.mini_program';
    }

    /**
     * @return \Kingdee\Jdy\MiniProgram\Application
     */
    public static function miniProgram($name = '')
    {
        return $name ? app('Jdy.mini_program.'.$name) : app('Jdy.mini_program');
    }
}
