<?php

namespace totum\common\helpers;

class CsvHelper
{
    public static function parseLine(string $line, string $delimiter = ';'): array
    {
        return str_getcsv($line, $delimiter);
    }

    public static function buildLine(array $fields, string $delimiter = ';'): string
    {
        $fp = fopen('php://temp', 'r+');
        fputcsv($fp, $fields, $delimiter);
        rewind($fp);
        $line = stream_get_contents($fp);
        fclose($fp);
        return rtrim($line);
    }

    public static function normalize(array $row, array $header): array
    {
        $result = [];
        foreach ($header as $index => $name) {
            $result[$name] = $row[$index] ?? null;
        }
        return $result;
    }
}
