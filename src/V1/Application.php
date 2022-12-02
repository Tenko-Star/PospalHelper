<?php

namespace PospalHelper\V1;

use PospalHelper\Core\ServiceContainer;
use PospalHelper\V1\Customer\CustomerApiProvider;
use PospalHelper\V1\Push\PushProvider;
use PospalHelper\V1\User\UserApiProvider;

/**
 * @property \PospalHelper\V1\User\Client $user
 * @property \PospalHelper\V1\Customer\Client $customer
 * @property \PospalHelper\V1\Push\Client $push
 */
class Application extends ServiceContainer
{
    protected array $providers = [
        UserApiProvider::class,
        CustomerApiProvider::class,
        PushProvider::class
    ];
}