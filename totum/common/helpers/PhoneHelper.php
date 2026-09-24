<?php

namespace totum\common\helpers;

class PhoneHelper
{
    public static function normalize(string $phone, string $countryCode = '7'): string
    {
        $digits = preg_replace('/\D/', '', $phone);
        if (str_starts_with($digits, '8') && strlen($digits) === 11) {
            $digits = $countryCode . substr($digits, 1);
        }
        return $digits;
    }

    public static function format(string $phone): string
    {
        $digits = self::normalize($phone);
        if (strlen($digits) !== 11) {
            return $phone;
        }
        return sprintf(
            '+%s (%s) %s-%s-%s',
            $digits[0],
            substr($digits, 1, 3),
            substr($digits, 4, 3),
            substr($digits, 7, 2),
            substr($digits, 9, 2)
        );
    }

    public static function isValid(string $phone): bool
    {
        return preg_match('/^\+?[\d\s\-()]{10,16}$/', $phone) === 1;
    }
}
