<?php

namespace totum\common\helpers;

class UrlHelper
{
    public static function buildQuery(array $params): string
    {
        return http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    }

    public static function parseQuery(string $query): array
    {
        parse_str($query, $result);
        return $result;
    }

    public static function appendQuery(string $url, array $params): string
    {
        $separator = str_contains($url, '?') ? '&' : '?';
        return $url . $separator . self::buildQuery($params);
    }

    public static function isAbsolute(string $url): bool
    {
        return (bool)preg_match('#^https?://#i', $url);
    }
}
