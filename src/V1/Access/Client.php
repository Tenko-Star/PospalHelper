<?php

namespace PospalHelper\V1\Access;

use DateTimeImmutable;
use PospalHelper\Core\BaseClient;
use PospalHelper\Core\Exception\UnexpectedParamsException;

/**
 * 接口访问量API
 */
class Client extends BaseClient
{
    /**
     * 查询访问量配置
     *
     * @return array
     * @throws \PospalHelper\Core\Exception\RequestException
     */
    public function queryAccessTimes(): array
    {
        $uri = '/pospal-api2/openapi/v1/openApiLimitAccess/queryAccessTimes';
        $config = $this->getConfig();

        $req = [
            'appId' => $config['appId'],
        ];

        return $this->query($uri, $req);
    }

    /**
     * 查询每日访问量
     *
     * <b>开始日期和结束日期之间不能超过7天</b>
     *
     * @param DateTimeImmutable $start 开始日期
     * @param DateTimeImmutable $end 结束日期
     * @return array
     * @throws UnexpectedParamsException
     * @throws \PospalHelper\Core\Exception\RequestException
     */
    public function queryDailyAccessTimesLog(DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $uri = '/pospal-api2/openapi/v1/openApiLimitAccess/queryDailyAccessTimesLog';
        $config = $this->getConfig();

        $diff = $start->diff($end);
        if ($diff->days > 7) {
            throw new UnexpectedParamsException('The start time interval must be less than 7 days.');
        }

        $start = $start->format('Y-m-d');
        $end = $end->format('Y-m-d');

        $req = [
            'appId' => $config['appId'],
            'beginDate' => $start,
            'endDate' => $end
        ];

        return $this->query($uri, $req);
    }
}