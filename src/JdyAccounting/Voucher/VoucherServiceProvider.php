<?php
/*
 * This file is part of the Kingdee/jdy.
 *
 * (c) alim <alim@bulutbazar.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\jdy\JdyAccounting\Voucher;

use Kingdee\jdy\JdyAccounting\Voucher\Voucher;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 *
 * @author alim <alim@bulutbazar.com>
 */
class VoucherServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        !isset($app['voucher']) && $app['voucher'] = function ($app) {
            return new Voucher($app);
        };
    }
}
