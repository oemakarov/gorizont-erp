<?php

namespace totum\common\helpers;

class JsonHelper
{
    public static function encode(mixed $value, bool $pretty = false): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | ($pretty ? JSON_PRETTY_PRINT : 0));
    }

    public static function decode(string $json, bool $assoc = true): mixed
    {
        return json_decode($json, $assoc, 512, JSON_THROW_ON_ERROR);
    }

    public static function isValid(string $json): bool
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function getPath(array $data, string $path, mixed $default = null): mixed
    {
        $segments = explode('.', $path);
        foreach ($segments as $segment) {
            if (!is_array($data) || !array_key_exists($segment, $data)) {
                return $default;
            }
            $data = $data[$segment];
        }
        return $data;
    }
}
