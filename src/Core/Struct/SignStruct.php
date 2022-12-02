<?php

namespace PospalHelper\Core\Struct;

use PospalHelper\Core\Exception\UnexpectedTypeException;

final class SignStruct
{
    public string $timestamp = '';
    public string $signature = '';
    public string $appId = '';

    public const SignTypeV1 = 0;
    public const SignTypeV2 = 1;

    private int $type;

    public function __construct(string $timestamp, string $signature, int $type = self::SignTypeV1, string $appId = '')
    {
        $this->signature = $signature;
        $this->timestamp = $timestamp;
        $this->type = $type;
        $this->appId = $appId;
    }

    public function __toString(): string
    {
        return $this->signature;
    }

    public function __toArray(): array
    {
        return $this->toArray();
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        $arr = ['time-stamp' => $this->timestamp];

        switch ($this->type) {
            case self::SignTypeV1:
                $arr['data-signature'] = $this->signature;
                break;
            case self::SignTypeV2:
                $arr['appId'] = $this->signature;
                $arr['data-signature-v3'] = $this->signature;
                break;
            default:
                throw new UnexpectedTypeException();
        }

        return $arr;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}