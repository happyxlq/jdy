<?php
/*
 *
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\Jdy\JdyAccounting;

use Kingdee\Jdy\Auth\Auth\AuthServiceProvider;
use Kingdee\Jdy\JdyAccounting\AccountingSubject\AccountingSubjectServiceProvider;
use Kingdee\Jdy\JdyAccounting\Voucher\VoucherServiceProvider;
use Kingdee\Jdy\JdyAccounting\System\SystemServiceProvider;
use Kingdee\Jdy\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @property \Kingdee\Jdy\MiniProgram\Auth\AccessToken $access_token
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        SystemServiceProvider::class,
        AuthServiceProvider::class,
        AccountingSubjectServiceProvider::class,
        VoucherServiceProvider::class,
    ];
}
