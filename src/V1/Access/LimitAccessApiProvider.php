<?php

namespace PospalHelper\V1\Access;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PospalHelper\Core\ServiceContainer;

class LimitAccessApiProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['access'] = function (ServiceContainer $app) {
            return new Client($app);
        };
    }
}