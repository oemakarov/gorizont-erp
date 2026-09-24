<?php

namespace totum\common\helpers;

class HashHelper
{
    public static function shortHash(string $value, int $length = 12): string
    {
        return substr(hash('sha256', $value), 0, $length);
    }

    public static function hmac(string $value, string $secret): string
    {
        return hash_hmac('sha256', $value, $secret);
    }

    public static function randomToken(int $bytes = 16): string
    {
        return bin2hex(random_bytes($bytes));
    }

    public static function constantEquals(string $known, string $user): bool
    {
        return hash_equals($known, $user);
    }
}
