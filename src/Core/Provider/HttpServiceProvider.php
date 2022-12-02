<?php

namespace PospalHelper\Core\Provider;

use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PospalHelper\Core\ServiceContainer;

class HttpServiceProvider implements ServiceProviderInterface
{

    /**
     * @inheritDoc
     */
    public function register(Container $pimple)
    {
        $pimple['http'] = function ($app) {
            return new Client($app['config']->get('http', []));
        };
    }
}