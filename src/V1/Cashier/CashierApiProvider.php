<?php

namespace PospalHelper\V1\Cashier;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PospalHelper\Core\ServiceContainer;

class CashierApiProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['cashier'] = function (ServiceContainer $app) {
            return new Client($app);
        };
    }
}