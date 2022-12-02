<?php

namespace PospalHelper\V1\Customer;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PospalHelper\Core\ServiceContainer;

class CustomerApiProvider implements ServiceProviderInterface
{

    /**
     * @inheritDoc
     */
    public function register(Container $pimple)
    {
        $pimple['customer'] = function (ServiceContainer $app) {
            return new Client($app);
        };
    }
}