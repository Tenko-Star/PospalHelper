<?php

namespace PospalHelper\Core\Auth;

use PospalHelper\Core\Struct\SignStruct;
use UnexpectedValueException;

class Sign
{
    /**
     * Get signature version 1
     * ***DO NOT REARRANGEMENT OR CHANGE DATA AFTER SIGN***
     *
     * @param string $appKey
     * @param array $data
     * @return SignStruct
     */
    public function v1(string $appKey, array &$data): SignStruct
    {
        $time = $this->getMicroTime();
        $sort = asort($data);
        if (!$sort) {
            throw new UnexpectedValueException('sort failure');
        }

        $jsonData = json_encode($data, JSON_NUMERIC_CHECK);
        $signature = strtoupper(md5($appKey.$jsonData));

        return new SignStruct($time, $signature);
    }

    /**
     * Get signature version 2
     * ***DO NOT REARRANGEMENT OR CHANGE DATA AFTER SIGN***
     *
     * @param string $appId
     * @param string $appKey
     * @param array $data
     * @return SignStruct
     */
    public function v2(string $appId, string $appKey, array &$data): SignStruct
    {
        $time = $this->getMicroTime();
        $sort = asort($data);
        if (!$sort) {
            throw new UnexpectedValueException('sort failure');
        }

        $jsonData = json_encode($data);
        $signature = strtoupper(md5($appId.$appKey.$time.$jsonData));

        return new SignStruct($time, $signature, SignStruct::SignTypeV2, $appId);
    }

    private function getMicroTime(): string
    {
        [$ms, $s] = explode(' ', microtime());
        return (float)sprintf('%.0f', ((float)$s + (float)$ms) * 1000);
    }
}