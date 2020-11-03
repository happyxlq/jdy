<?php
/*
 * This file is part of the Kingdee/jdy.
 *
 * (c) alim <alim@bulutbazar.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\jdy\JdyScm\Product;

use Kingdee\jdy\JdyScm\Product\Product;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 *
 * @author alim <alim@bulutbazar.com>
 */
class ProductServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        !isset($app['product']) && $app['product'] = function ($app) {
            return new Product($app);
        };
    }
}
