<?php

namespace PospalHelper\Core\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PospalHelper\Core\Config;
use PospalHelper\Core\ServiceContainer;

class ConfigServiceProvider implements ServiceProviderInterface
{

    /**
     * @inheritDoc
     */
    public function register(Container $pimple)
    {
        $pimple['config'] = function ($app) {
            /** @var ServiceContainer $app */
            return new Config($app->getConfig());
        };
    }
}