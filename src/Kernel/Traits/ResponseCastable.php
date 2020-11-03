<?php
/*
 *
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kingdee\jdy\Kernel\Traits;

use Kingdee\jdy\Kernel\Contracts\Arrayable;
use Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException;
use Kingdee\jdy\Kernel\Http\Response;
use Kingdee\jdy\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait ResponseCastable.
 *
 * @author alim <alim@bulutbazar.com>
 */
trait ResponseCastable
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string|null                         $type
     *
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException
     *
     * @return array|\Kingdee\jdy\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    protected function castResponseToType(ResponseInterface $response, $type = null)
    {
        $response = Response::buildFromPsrResponse($response);
        $response->getBody()->rewind();
        switch ($type ?? 'array') {
            case 'collection':
                return $response->toCollection();
            case 'array':
                return $response->toArray();
            case 'object':
                return $response->toObject();
            case 'raw':
                return $response;
            default:
                if (!is_subclass_of($type, Arrayable::class)) {
                    throw new InvalidArgumentException(sprintf(
                        'Config key "response_type" classname must be an instanceof %s',
                        Arrayable::class
                    ));
                }

                return new $type($response);
        }
    }

    /**
     * @param mixed       $response
     * @param string|null $type
     *
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidArgumentException
     * @throws \Kingdee\jdy\Kernel\Exceptions\InvalidConfigException
     *
     * @return array|\Kingdee\jdy\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    protected function detectAndCastResponseToType($response, $type = null)
    {
        switch (true) {
            case $response instanceof ResponseInterface:
                $response = Response::buildFromPsrResponse($response);
                break;
            case $response instanceof Arrayable:
                $response = new Response(200, [], json_encode($response->toArray()));
                break;
            case ($response instanceof Collection) || is_array($response) || is_object($response):
                $response = new Response(200, [], json_encode($response));
                break;
            case is_scalar($response):
                $response = new Response(200, [], $response);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unsupported response type "%s"', gettype($response)));
        }

        return $this->castResponseToType($response, $type);
    }
}
