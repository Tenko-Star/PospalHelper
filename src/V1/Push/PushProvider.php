<?php

namespace PospalHelper\V1\Push;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PospalHelper\Core\ServiceContainer;

class PushProvider implements ServiceProviderInterface
{

    /**
     * @inheritDoc
     */
    public function register(Container $pimple)
    {
        $pimple['push'] = function (ServiceContainer $app) {
            return new Client($app);
        };
    }
}