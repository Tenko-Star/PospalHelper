<?php

namespace PospalHelper\Core\Validator;

trait HandleArgumentTrait
{
    private function handleArgs(array $args, \Closure $func): bool
    {
        $result = true;
        foreach ($args as $key => &$arg) {
            if (!is_string($arg)) {
                unset($args[$key]);
                continue;
            }

            [$name] = $list = explode(',', $arg);
            array_shift($list);

            $result = $func($name, $list);
            if (!$result) break;
        }

        return $result;
    }
}