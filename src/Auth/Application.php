<?php
/*
 *
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\jdy\Auth;

use Kingdee\jdy\Auth\Auth\AuthServiceProvider;
use Kingdee\jdy\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @property \Kingdee\jdy\MiniProgram\Auth\AccessToken $access_token
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        AuthServiceProvider::class,
    ];
}
