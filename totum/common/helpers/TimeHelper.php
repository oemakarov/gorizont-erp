<?php

namespace totum\common\helpers;

class TimeHelper
{
    public static function secondsToHuman(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds . 's';
        }
        $minutes = (int)floor($seconds / 60);
        if ($minutes < 60) {
            return $minutes . 'm';
        }
        $hours = (int)floor($minutes / 60);
        if ($hours < 24) {
            return $hours . 'h ' . ($minutes % 60) . 'm';
        }
        $days = (int)floor($hours / 24);
        return $days . 'd ' . ($hours % 24) . 'h';
    }

    public static function ago(string $datetime): string
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        return self::secondsToHuman($diff) . ' ago';
    }

    public static function isLeapYear(int $year): bool
    {
        return ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0);
    }
}
