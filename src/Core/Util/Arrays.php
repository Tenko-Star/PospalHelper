<?php

namespace PospalHelper\Core\Util;

class Arrays
{
    public static function add(array $array, string $key, $value): array
    {
        if (!static::exists($array, $key)) {
            static::set($array, $key, $value);
        }

        return $array;
    }

    public static function get(array $array, string $key, $default = null)
    {
        if (static::exists($array, $key)) return $array[$key];

        foreach (explode('.', $key) as $segment) {
            if (static::exists($array, $segment)) $array = $array[$segment];
            else return $default;
        }

        return $array;
    }

    public static function set(array $array, string $key, $value): array
    {
        $keys = explode('.', $key);
        $origin = &$array;

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $origin;
    }

    public static function remove(array &$array, string $key): void
    {
        if (array_key_exists($key, $array)) unset($array[$key]);

        $current = &$array;
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!(isset($current[$key]) && is_array($current[$key]))) {
                return;
            }

            $current = &$current[$key];
        }

        $key = array_shift($keys);
        if (array_key_exists($key, $current)) unset($current[$key]);
    }

    /**
     * @param array $array
     * @param string[] $keys
     * @return void
     */
    public static function removeSome(array &$array, array $keys): void
    {
        if (count($keys) === 0) return;

        $original = &$array;

        foreach ($keys as $key) {
            if (!is_string($key)) continue;
            if (array_key_exists($key, $original)) {
                unset($original[$key]);
                continue;
            }

            $current = &$original;
            $segments = explode('.', $key);

            while (count($segments) > 1) {
                $segment = array_shift($segments);

                if (!(isset($current[$segment]) && is_array($current[$segment]))) {
                    continue 2;
                }

                $current = &$current[$segment];
            }

            $segment = array_shift($segments);
            if (array_key_exists($segment, $current)) unset($current[$segment]);
        }
    }

    public static function extend(array $target, array $source, string $namespace = ''): array
    {
        foreach ($source as $key => $value) {
            $target = static::set($target, $namespace.$key, $value);
        }

        return $target;
    }

    public static function exists(array $array, string $key): bool
    {
        if (array_key_exists($key, $array)) return true;

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!(isset($array[$key]) && is_array($array[$key]))) {
                return false;
            }

            $array = &$array[$key];
        }

        return array_key_exists(array_shift($keys), $array);
    }
}