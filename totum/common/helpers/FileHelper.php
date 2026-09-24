<?php

namespace totum\common\helpers;

class FileHelper
{
    public static function humanSize(int $bytes, int $precision = 2): string
    {
        return NumberHelper::formatBytes($bytes, $precision);
    }

    public static function extension(string $filename): string
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    public static function basename(string $filename): string
    {
        return pathinfo($filename, PATHINFO_FILENAME);
    }

    public static function ensureExtension(string $filename, string $extension): string
    {
        $extension = ltrim($extension, '.');
        if (self::extension($filename) === $extension) {
            return $filename;
        }
        return $filename . '.' . $extension;
    }
}
