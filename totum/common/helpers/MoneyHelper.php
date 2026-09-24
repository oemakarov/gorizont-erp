<?php

namespace totum\common\helpers;

class MoneyHelper
{
    public static function format(float $amount, string $currency = 'RUB', int $decimals = 2): string
    {
        return number_format($amount, $decimals, '.', ' ') . ' ' . $currency;
    }

    public static function round(float $amount, int $decimals = 2): float
    {
        return round($amount, $decimals);
    }

    public static function parse(string $value): float
    {
        $value = str_replace([' ', "\u{00A0}", ','], ['', '', '.'], $value);
        return (float)preg_replace('/[^0-9.\-]/', '', $value);
    }

    public static function toCents(float $amount): int
    {
        return (int)round($amount * 100);
    }
}
