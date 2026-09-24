<?php

namespace totum\common\helpers;

class EmojiHelper
{
    public static function contains(string $value): bool
    {
        return (bool)preg_match('/[\x{1F600}-\x{1F64F}]/u', $value);
    }

    public static function strip(string $value): string
    {
        return preg_replace('/[\x{1F300}-\x{1FAFF}]/u', '', $value);
    }

    public static function count(string $value): int
    {
        preg_match_all('/[\x{1F300}-\x{1FAFF}]/u', $value, $matches);
        return count($matches[0]);
    }
}
