<?php

namespace PospalHelper\Core\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PospalHelper\Core\Auth\Sign;

class SignServiceProvider implements ServiceProviderInterface
{

    /**
     * @inheritDoc
     */
    public function register(Container $pimple)
    {
        $pimple['sign'] = function () {
            return new Sign();
        };
    }
}