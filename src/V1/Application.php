<?php

namespace PospalHelper\V1;

use PospalHelper\Core\ServiceContainer;
use PospalHelper\V1\Access\LimitAccessApiProvider;
use PospalHelper\V1\Cashier\CashierApiProvider;
use PospalHelper\V1\Customer\CustomerApiProvider;
use PospalHelper\V1\Push\PushProvider;
use PospalHelper\V1\Stock\StockTakingApiProvider;
use PospalHelper\V1\User\UserApiProvider;

/**
 * @property \PospalHelper\V1\User\Client $user
 * @property \PospalHelper\V1\Customer\Client $customer
 * @property \PospalHelper\V1\Push\Client $push
 * @property \PospalHelper\V1\Stock\Client $stock
 * @property \PospalHelper\V1\Cashier\Client $cashier
 * @property \PospalHelper\V1\Access\Client $access
 */
class Application extends ServiceContainer
{
    protected array $providers = [
        UserApiProvider::class,
        CustomerApiProvider::class,
        PushProvider::class,
        StockTakingApiProvider::class,
        CashierApiProvider::class,
        LimitAccessApiProvider::class
    ];
}