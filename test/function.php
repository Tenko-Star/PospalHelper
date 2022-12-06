<?php

if (!function_exists('env')) {
    function env(string $name): ?string
    {
        static $env = null;

        if (is_null($env)) {
            $content = file_get_contents('.env');
            $lines = explode("\n", $content);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    [$key, $value] = explode('=', $line);
                    $key = trim($key);
                    $value = trim($value);

                    $env[$key] = $value;
                }
            }
        }

        return $env[$name] ?? null;
    }
}