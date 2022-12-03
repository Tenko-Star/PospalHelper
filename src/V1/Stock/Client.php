<?php

namespace PospalHelper\V1\Stock;

use DateTimeImmutable;
use PospalHelper\Core\BaseClient;
use PospalHelper\Core\Exception\RequestException;
use PospalHelper\Core\Exception\UnexpectedParamsException;

/**
 * 盘点API
 */
class Client extends BaseClient
{
    /**
     * 查询历史盘点记录
     *
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     * @return array
     * @throws RequestException
     * @throws UnexpectedParamsException
     */
    public function queryStockTakingHistories(DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $uri = '/pospal-api2/openapi/v1/stockTakingOpenApi/queryStockTakingHistories';
        $config = $this->getConfig();

        $diff = $start->diff($end);
        if ($diff->days > 90) {
            throw new UnexpectedParamsException('The start time interval must be less than 31 days.');
        }

        $req = [
            'appId' => $config['appId'],
            'startTime' => $start->format('Y-m-d H:i:s'),
            'endTime' => $end->format('Y-m-d H:i:s')
        ];

        return $this->query($uri, $req);
    }

    /**
     * 查询某一盘点记录明细
     *
     * @param int $stockTakingId
     * @return array
     * @throws RequestException
     */
    public function queryStockTakingItems(int $stockTakingId): array
    {
        $uri = '/pospal-api2/openapi/v1/stockTakingOpenApi/queryStockTakingItems';
        $config = $this->getConfig();

        $req = [
            'appId' => $config['appId'],
            'stockTakingId' => $stockTakingId,
        ];

        return $this->query($uri, $req);
    }

    /**
     * 查询某一盘点详情
     *
     * @param int $stockTakingId
     * @return array
     * @throws RequestException
     */
    public function queryStockTakingDetailsById(int $stockTakingId): array
    {
        $uri = '/pospal-api2/openapi/v1/stockTakingOpenApi/queryStockTakingDetailsById';
        $config = $this->getConfig();

        $req = [
            'appId' => $config['appId'],
            'stockTakingId' => $stockTakingId,
        ];

        return $this->query($uri, $req);
    }
}