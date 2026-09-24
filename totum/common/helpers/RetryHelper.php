<?php

namespace totum\common\helpers;

class RetryHelper
{
    public static function retry(callable $callback, int $attempts = 3, int $delayMs = 100): mixed
    {
        $lastException = null;
        for ($i = 0; $i < $attempts; $i++) {
            try {
                return $callback();
            } catch (\Exception $e) {
                $lastException = $e;
                if ($i < $attempts - 1) {
                    usleep($delayMs * 1000);
                }
            }
        }
        throw $lastException;
    }

    public static function backoff(int $attempt, int $baseMs = 100): int
    {
        return $baseMs * (2 ** min($attempt, 10));
    }
}
