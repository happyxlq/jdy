<?php
/*
 *
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\jdy\JdyAccounting;

use Kingdee\jdy\Auth\Auth\AuthServiceProvider;
use Kingdee\jdy\JdyAccounting\AccountingSubject\AccountingSubjectServiceProvider;
use Kingdee\jdy\JdyAccounting\Voucher\VoucherServiceProvider;
use Kingdee\jdy\JdyAccounting\System\SystemServiceProvider;
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
        SystemServiceProvider::class,
        AuthServiceProvider::class,
        AccountingSubjectServiceProvider::class,
        VoucherServiceProvider::class,
    ];
}
