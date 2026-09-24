<?php

namespace totum\common\helpers;

class PaginationHelper
{
    public static function totalPages(int $total, int $perPage): int
    {
        if ($perPage <= 0) {
            return 0;
        }
        return (int)ceil($total / $perPage);
    }

    public static function offset(int $page, int $perPage): int
    {
        return max(0, ($page - 1) * $perPage);
    }

    public static function window(int $current, int $total, int $radius = 2): array
    {
        $start = max(1, $current - $radius);
        $end = min($total, $current + $radius);
        return range($start, $end);
    }

    public static function clampPage(int $page, int $total): int
    {
        if ($total <= 0) {
            return 1;
        }
        return max(1, min($page, $total));
    }
}
