<?php
/*
 *
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\jdy\JdyScm;

use Kingdee\jdy\Auth\Auth\AuthServiceProvider;
use Kingdee\jdy\JdyScm\Bill\BillServiceProvider;
use Kingdee\jdy\JdyScm\Customer\CustomerServiceProvider;
use Kingdee\jdy\JdyScm\Inventory\InventoryServiceProvider;
use Kingdee\jdy\JdyScm\Product\ProductServiceProvider;
use Kingdee\jdy\JdyScm\Sale\SaleServiceProvider;
use Kingdee\jdy\JdyScm\SaleOrder\SaleOrderServiceProvider;
use Kingdee\jdy\JdyScm\SaleReturn\SaleReturnServiceProvider;
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
