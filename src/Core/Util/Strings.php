<?php

namespace PospalHelper\Core\Util;

class Strings
{
    public static function toUpperCamelCase(string $str) {
        return str_replace(" ", "", ucwords(str_replace(["-", "_"], " ", $str)));
    }
}