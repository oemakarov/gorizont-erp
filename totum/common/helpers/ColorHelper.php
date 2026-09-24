<?php

namespace totum\common\helpers;

class ColorHelper
{
    public static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    public static function rgbToHex(int $r, int $g, int $b): string
    {
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    public static function lighten(string $hex, float $percent): string
    {
        [$r, $g, $b] = self::hexToRgb($hex);
        $r = min(255, (int)($r + (255 - $r) * $percent));
        $g = min(255, (int)($g + (255 - $g) * $percent));
        $b = min(255, (int)($b + (255 - $b) * $percent));
        return self::rgbToHex($r, $g, $b);
    }
}
