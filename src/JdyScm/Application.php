<?php
/*
 *
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\Jdy\JdyScm;

use Kingdee\Jdy\Auth\Auth\AuthServiceProvider;
use Kingdee\Jdy\JdyScm\Bill\BillServiceProvider;
use Kingdee\Jdy\JdyScm\Customer\CustomerServiceProvider;
use Kingdee\Jdy\JdyScm\Inventory\InventoryServiceProvider;
use Kingdee\Jdy\JdyScm\Product\ProductServiceProvider;
use Kingdee\Jdy\JdyScm\Sale\SaleServiceProvider;
use Kingdee\Jdy\JdyScm\SaleOrder\SaleOrderServiceProvider;
use Kingdee\Jdy\JdyScm\SaleReturn\SaleReturnServiceProvider;
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
        AuthServiceProvider::class,
        System\ServiceProvider::class,
        CustomerServiceProvider::class,
        SaleOrderServiceProvider::class,
        ProductServiceProvider::class,
        BillServiceProvider::class,
        SaleServiceProvider::class,
        SaleReturnServiceProvider::class,
        InventoryServiceProvider::class,
    ];
}
