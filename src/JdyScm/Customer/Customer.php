<?php

/*
 *
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\jdy\JdyScm\Customer;



use Kingdee\jdy\JdyScm\JdyScmClient;
use Kingdee\jdy\Kernel\BaseClient;

/**
 * Class ServiceList.
 *
 * @author alim
 */
class Customer extends JdyScmClient
{
    /*
     * 查询客户列表
     * 获取精斗云进销存V3系统的客户列表（支持过滤）
     */
    public function customerList($filter = [])
    {
        $filter = json_encode($filter, JSON_FORCE_OBJECT);
        return $this->httpPostJson('jdyscm/customer/list', ['filter'=>$filter]);
    }

    public function addCustomer($data){
        return $this->httpPostJson('jdyscm/customer/add', ['items'=>[$data]]);
    }

    public function addCustomers($items){
        return $this->httpPost('jdyscm/customer/add', ['items'=>$items]);
    }

    public function updateCustomer($data){
        return $this->httpPostJson('jdyscm/customer/update', ['items'=>[$data]]);
    }

    public function updateCustomers($items){
        return $this->httpPost('jdyscm/customer/update', ['items'=>$items]);
    }



}
