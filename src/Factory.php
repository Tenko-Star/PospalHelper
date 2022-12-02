<?php

namespace PospalHelper;

use PospalHelper\Core\Util\Strings;

/**
 * Class Factory
 *
 * @method static \PospalHelper\V1\Application v1(array $config)
 * @method static \PospalHelper\V2\Application v2(array $config)
 */
class Factory
{
    public static function make(string $application, array $config) {
        $namespace = Strings::toUpperCamelCase($application);
        $app = "\\PospalHelper\\$namespace\\Application";

        return new $app($config);
    }

    public static function __callStatic($name, $args) {
        return self::make($name, ...$args);
    }
}