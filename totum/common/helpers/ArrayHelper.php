<?php

namespace totum\common\helpers;

class ArrayHelper
{
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }

    public static function only(array $array, array $keys): array
    {
        return array_intersect_key($array, array_flip($keys));
    }

    public static function except(array $array, array $keys): array
    {
        return array_diff_key($array, array_flip($keys));
    }

    public static function pluck(array $items, string $key): array
    {
        return array_map(fn($item) => $item[$key] ?? null, $items);
    }
}
