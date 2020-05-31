<?php
/*
 * This file is part of the otkurbiz/jdy.
 *
 * (c) alim <alim@bulutbazar.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace OtkurBiz\jdy\JdyScm\SaleReturn;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 *
 * @author alim <alim@bulutbazar.com>
 */
class SaleReturnServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        !isset($app['sale_return']) && $app['sale_return'] = function ($app) {
            return new SaleReturn($app);
        };
    }
}
