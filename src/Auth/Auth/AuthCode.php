<?php

/*
 *
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\Jdy\Auth\Auth;

use Kingdee\Jdy\Kernel\BaseClient;

/**
 * Class Auth.
 *
 * @author alim <alim@bulutbazar.com>
 */
class AuthCode extends BaseClient
{
    /**
     * Get session info by code.
     *
     * @param string $accessToken
     *
     * @throws \Kingdee\Jdy\Kernel\Exceptions\InvalidConfigException
     *
     * @return \Psr\Http\Message\ResponseInterface|\Kingdee\Jdy\Kernel\Support\Collection|array|object|string
     */
    public function authCode(string $accessToken)
    {
        $params = ['access_token'=>$accessToken];
        return $this->httpGet('auth/user/auth_code', $params);
    }

    public function validate(string $authCode){

        $params = ['client_id'=>$this->app['config']['client_id'], 'client_secret'=>$this->app['config']['client_secret'], 'auth_code'=>$authCode];
        return $this->httpGet('auth/user/auth_code', $params);
    }
}
