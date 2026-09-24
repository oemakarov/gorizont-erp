<?php

namespace totum\common\helpers;

class DateHelper
{
    public static function startOfDay(string $date): string
    {
        return date('Y-m-d 00:00:00', strtotime($date));
    }

    public static function endOfDay(string $date): string
    {
        return date('Y-m-d 23:59:59', strtotime($date));
    }

    public static function daysBetween(string $from, string $to): int
    {
        $from = (new \DateTime($from))->setTime(0, 0);
        $to = (new \DateTime($to))->setTime(0, 0);
        return (int)$from->diff($to)->format('%r%a');
    }

    public static function isWeekend(string $date): bool
    {
        return in_array(date('N', strtotime($date)), ['6', '7'], true);
    }
}
