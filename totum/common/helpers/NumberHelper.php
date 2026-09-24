<?php

namespace totum\common\helpers;

class NumberHelper
{
    public static function clamp(int|float $value, int|float $min, int|float $max): int|float
    {
        return max($min, min($max, $value));
    }

    public static function toPercent(int|float $part, int|float $whole, int $precision = 2): float
    {
        if ($whole == 0) {
            return 0.0;
        }
        return round(($part / $whole) * 100, $precision);
    }

    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= 1024 ** $pow;
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
