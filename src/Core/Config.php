<?php

namespace PospalHelper\Core;

use PospalHelper\Core\Exception\ConfigNotFoundException;
use PospalHelper\Core\Util\Collection;

class Config extends Collection {
    private static array $rules = [
        'appId',
        'appKey',
        'baseUri'
    ];

    public function init() {
        $config = $this->item;

        foreach (self::$rules as $rule) {
            if (!isset($config[$rule])) {
                throw new ConfigNotFoundException("Could not find config named $rule.");
            }
        }
    }
}