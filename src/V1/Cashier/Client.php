<?php

namespace PospalHelper\V1\Cashier;

use PospalHelper\Core\BaseClient;

/**
 * 收银员Api
 */
class Client extends BaseClient
{
    /**
     * 获取门店所有收银员
     *
     * @return array
     * @throws \PospalHelper\Core\Exception\RequestException
     */
    public function queryAllCashier(): array
    {
        $uri = '/pospal-api2/openapi/v1/cashierOpenApi/queryAllCashier';
        $config = $this->getConfig();

        $req = [
            'appId' => $config['appId'],
        ];

        return $this->query($uri, $req);
    }
}