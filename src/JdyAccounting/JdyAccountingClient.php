<?php
namespace Kingdee\Jdy\JdyAccounting;

use Kingdee\Jdy\Kernel\BaseClient;
use Kingdee\Jdy\Kernel\Contracts\AccessTokenInterface;
use Kingdee\Jdy\Kernel\ServiceContainer;
use Kingdee\Jdy\Kernel\Traits\InteractsWithCache;
use Psr\Http\Message\RequestInterface;

class JdyAccountingClient extends BaseClient {
    use InteractsWithCache;

    public $dbId;
    public $sId;
    public $dbIdCacheKey = 'Kingdee.Jdy.JdyAccounting.dbId';
    public $sIdCacheKey = 'Kingdee.Jdy.JdyAccounting.sId';
    public function __construct( ServiceContainer $app, AccessTokenInterface $accessToken = null ) {
        parent::__construct( $app, $accessToken );
        $this->dbId = $this->getCache()->fetch($this->dbIdCacheKey);
        $this->sId  = $this->getCache()->fetch($this->sIdCacheKey);
    }

    public function setSId($sId){
        $this->sId = $sId;
        $this->getCache()->save($this->sIdCacheKey, $sId);
    }

    public function setDbId($dbId){
        $this->dbId = $dbId;
        $this->getCache()->save($this->dbIdCacheKey, $dbId);
    }

    protected function dbIdMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if ($this->accessToken) {
                    $request = $this->applyToRequest($request, $options, 'dbId');
                }
                return $handler($request, $options);
            };
        };
    }

    protected function sIdMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if ($this->accessToken) {
                    $request = $this->applyToRequest($request, $options, 'sId');
                }
                return $handler($request, $options);
            };
        };
    }


    private function applyToRequest(RequestInterface $request, array $requestOptions = [], string $type = 'dbId'): RequestInterface
    {
        parse_str($request->getUri()->getQuery(), $query);
        $query = http_build_query(array_merge($this->getQuery($type), $query));
        return $request->withUri($request->getUri()->withQuery($query));
    }


    private function getQuery(string $type = null): array
    {
        if(empty($type))
        {
            $type = 'dbId';
        }
        return [$type => $this->{$type}];
    }

    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        $this->registerHttpMiddlewares();
        $this->pushMiddleware($this->sIdMiddleware(), 'sId');
        $this->pushMiddleware($this->dbIdMiddleware(), 'dbId');
        return parent::request($url, $method, $options, $returnRaw);

    }

}
