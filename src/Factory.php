<?php
/*
 *
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\jdy;

/**
 * Class Factory.
 *
 * @method static \Kingdee\jdy\Application    jdy(array $config)
 */
class Factory
{
    /**
     * @param array $config
     *
     * @return \Kingdee\jdy\Application
     */
    public static function make(array $config, $name = 'auth')
    {
        $namespace = Kernel\Support\Str::studly($name);
        $application = "\\Kingdee\\jdy\\{$namespace}\\Application";

        return new $application($config);
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
