<?php

namespace totum\common\helpers;

class IpHelper
{
    public static function isPrivate(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
            && filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    public static function anonymize(string $ip): string
    {
        if (str_contains($ip, ':')) {
            return preg_replace('/:[^:]*$/', ':0', $ip);
        }
        return preg_replace('/\.\d+$/', '.0', $ip);
    }

    public static function toLong(string $ip): int|false
    {
        return ip2long($ip);
    }

    public static function fromLong(int $long): string|false
    {
        return long2ip($long);
    }
}
