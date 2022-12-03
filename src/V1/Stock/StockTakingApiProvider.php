<?php

namespace PospalHelper\V1\Stock;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PospalHelper\Core\ServiceContainer;

class StockTakingApiProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['stock'] = function (ServiceContainer $app) {
            return new Client($app);
        };
    }
}