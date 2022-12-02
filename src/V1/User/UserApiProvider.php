<?php

namespace PospalHelper\V1\User;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PospalHelper\Core\ServiceContainer;

class UserApiProvider implements ServiceProviderInterface
{

    /**
     * @inheritDoc
     */
    public function register(Container $pimple)
    {
        $pimple['user'] = function (ServiceContainer $app) {
            return new Client($app);
        };
    }
}