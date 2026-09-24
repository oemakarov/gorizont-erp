<?php

namespace totum\common\helpers;

class HtmlHelper
{
    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public static function tag(string $name, string $content, array $attrs = []): string
    {
        $attrString = '';
        foreach ($attrs as $key => $val) {
            $attrString .= ' ' . $key . '="' . self::escape((string)$val) . '"';
        }
        return '<' . $name . $attrString . '>' . $content . '</' . $name . '>';
    }

    public static function attributes(array $attrs): string
    {
        $parts = [];
        foreach ($attrs as $key => $val) {
            $parts[] = $key . '="' . self::escape((string)$val) . '"';
        }
        return implode(' ', $parts);
    }
}
