<?php

namespace totum\common\helpers;

class StringHelper
{
    public static function snakeToCamel(string $value): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $value))));
    }

    public static function camelToSnake(string $value): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $value));
    }

    public static function truncate(string $value, int $limit, string $suffix = '...'): string
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }
        return mb_substr($value, 0, max(0, $limit - mb_strlen($suffix))) . $suffix;
    }
}
