<?php
/*
 * This file is part of the Kingdee/jdy.
 *
 * (c) alim <alim@bulutbazar.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\jdy\Kernel;

use Kingdee\jdy\Kernel\Contracts\AccessTokenInterface;
use Kingdee\jdy\Kernel\Exceptions\HttpException;
use Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException;
use Kingdee\jdy\Kernel\Exceptions\RuntimeException;
use Kingdee\jdy\Kernel\Traits\HasHttpRequests;
use Kingdee\jdy\Kernel\Traits\InteractsWithCache;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AccessToken.
 *
 * @author alim <alim@bulutbazar.com>
 */
abstract class AccessToken implements AccessTokenInterface
{
    use HasHttpRequests;
    use InteractsWithCache;
    /**
     * @var \Pimple\Container
     */
    protected $app;
    /**
     * @var string
     */
    protected $requestMethod = 'GET';
    /**
     * @var string
     */
    protected $endpointToGetToken;
    /**
     * @var string
     */
    protected $queryName;
    /**
     * @var array
     */
    protected $token;

    /**
     * @var int
     */
    protected $safeSeconds = 500;
    /**
     * @var string
     */
    protected $tokenKey = 'access_token';
    /**
     * @var string
     */
    protected $cachePrefix = 'Kingdee.jdy.kernel.access_token.';

    /**
     * AccessToken constructor.
     *
     * @param \Pimple\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param bool $refresh
     *
     * @throws \Kingdee\jdy\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidConfigException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException
     * @throws \Kingdee\jdy\Kernel\Exceptions\RuntimeException
     *
     * @return array
     */
    public function getToken(bool $refresh = false): array
    {
        $cacheKey = $this->getCacheKey();
        $cached = $this->getCache()->fetch($cacheKey);
        if (!$refresh && !empty($cached)) {
            return $cached;
        }
        $token = $this->requestToken($this->getCredentials(), true);
        $this->setToken($token, $token['expires_in'] ?? 7200);

        return $token;
    }

    /**
     * @param string $token
     * @param int    $lifetime
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Kingdee\jdy\Kernel\Exceptions\RuntimeException
     *
     * @return \Kingdee\jdy\Kernel\Contracts\AccessTokenInterface
     */
    public function setToken(array $result, $lifetime = 7200): AccessTokenInterface
    {
        $cache = $this->getCache();
        $cache->save($this->getCacheKey(), $result, $lifetime - $this->safeSeconds);
        $cacheKey = $this->getCacheKey();
        if (empty($this->getCache()->fetch($cacheKey))) {
            throw new RuntimeException('Failed to cache access token.');
        }

        return $this;
    }

    /**
     * @throws \Kingdee\jdy\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidConfigException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException
     * @throws \Kingdee\jdy\Kernel\Exceptions\RuntimeException
     *
     * @return \Kingdee\jdy\Kernel\Contracts\AccessTokenInterface
     */
    public function refresh(): AccessTokenInterface
    {
        $this->getToken(true);

        return $this;
    }

    /**
     * @param array $credentials
     * @param bool  $toArray
     *
     * @throws \Kingdee\jdy\Kernel\Exceptions\HttpException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidConfigException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException
     *
     * @return \Psr\Http\Message\ResponseInterface|\Kingdee\jdy\Kernel\Support\Collection|array|object|string
     */
    public function requestToken(array $credentials, $toArray = false)
    {
        $response = $this->sendRequest($credentials);
        $result = json_decode($response->getBody()->getContents(), true);
        $formatted = $this->castResponseToType($response, $this->app['config']->get('response_type'));
        if ($result['errcode'] === 0 && empty($result['data'][$this->tokenKey])) {
            throw new HttpException('Request access_token fail: '.json_encode($result, JSON_UNESCAPED_UNICODE), $response, $formatted);
        }

        return $toArray ? $result : $formatted;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array                              $requestOptions
     *
     * @throws \Kingdee\jdy\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidConfigException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException
     * @throws \Kingdee\jdy\Kernel\Exceptions\RuntimeException
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface
    {
        parse_str($request->getUri()->getQuery(), $query);
        $query = http_build_query(array_merge($this->getQuery(), $query));
        return $request->withUri($request->getUri()->withQuery($query));
    }

    /**
     * Send http request.
     *
     * @param array $credentials
     *
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException
     *
     * @return ResponseInterface
     */
    protected function sendRequest(array $credentials): ResponseInterface
    {
        $options = [
            ('GET' === $this->requestMethod) ? 'query' : 'json' => $credentials,
        ];

        return $this->setHttpClient($this->app['http_client'])->request($this->getEndpoint(), $this->requestMethod, $options);
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cachePrefix.md5(json_encode($this->getCredentials()));
    }

    /**
     * The request query will be used to add to the request.
     *
     * @throws \Kingdee\jdy\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidConfigException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException
     * @throws \Kingdee\jdy\Kernel\Exceptions\RuntimeException
     *
     * @return array
     */
    protected function getQuery(): array
    {
        return [$this->queryName ?? $this->tokenKey => $this->getToken()['data'][$this->tokenKey]];
    }

    /**
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        if (empty($this->endpointToGetToken)) {
            throw new InvalidArgumentException('No endpoint for access token request.');
        }

        return $this->endpointToGetToken;
    }

    /**
     * @return string
     */
    public function getTokenKey()
    {
        return $this->tokenKey;
    }

    /**
     * Credential for get token.
     *
     * @return array
     */
    abstract protected function getCredentials(): array;
}
