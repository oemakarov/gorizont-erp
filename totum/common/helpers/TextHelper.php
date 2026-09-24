<?php

namespace totum\common\helpers;

class TextHelper
{
    public static function slugify(string $value, string $separator = '-'): string
    {
        $value = mb_strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', $separator, $value);
        return trim($value, $separator);
    }

    public static function initials(string $name, int $count = 2): string
    {
        $parts = preg_split('/\s+/', trim($name));
        $parts = array_filter($parts);
        $result = '';
        foreach (array_slice($parts, 0, $count) as $part) {
            $result .= mb_substr($part, 0, 1);
        }
        return mb_strtoupper($result);
    }

    public static function wordCount(string $value): int
    {
        return count(preg_split('/\s+/', trim($value), -1, PREG_SPLIT_NO_EMPTY));
    }
}
