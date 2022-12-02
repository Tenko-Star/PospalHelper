<?php

namespace PospalHelper\V1\User;

use PospalHelper\Core\BaseClient;
use PospalHelper\Core\Exception\RequestException;
use PospalHelper\Core\Iterator\IteratorResponse;

class Client extends BaseClient
{
    protected ?string $baseUri = '';

    public function queryAllUser(): UserIterator
    {
        $client = $this;

        return new UserIterator(
            function (?array $params) use ($client) {
                $url = '/pospal-api2/openapi/v1/userOpenApi/queryAllUser';
                $config = $client->getConfig();
                $req = [
                    'appId' => $config['appId']
                ];
                if (!is_null($params)) {
                    $req['postBackParameter'] = $params;
                }
                $response = $client->query($url, $req);

                return new IteratorResponse(
                    $response['result'],
                    $response['pageSize'],
                    $response['postBackParameter']
                );
            }
        );
    }
}