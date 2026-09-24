<?php

namespace totum\common\helpers;

class UuidHelper
{
    public static function v4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function isUuid(string $value): bool
    {
        return (bool)preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value);
    }

    public static function compact(string $uuid): string
    {
        return str_replace('-', '', $uuid);
    }

    public static function expand(string $compact): string
    {
        if (strlen($compact) !== 32) {
            return $compact;
        }
        $parts = str_split($compact, 8);
        $parts = array_merge([$parts[0]], str_split(substr($compact, 8), 4));
        return implode('-', $parts);
    }
}
