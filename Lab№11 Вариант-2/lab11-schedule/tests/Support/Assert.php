<?php

declare(strict_types=1);

namespace University\Schedule\Tests\Support;

final class Assert
{
    public static function same(mixed $expected, mixed $actual, string $message): void
    {
        if ($expected !== $actual) {
            throw new \RuntimeException(sprintf(
                '%s (ожидалось %s, получено %s)',
                $message,
                var_export($expected, true),
                var_export($actual, true),
            ));
        }
    }

    /** @param class-string<\Throwable> $expected */
    public static function throws(callable $action, string $expected, string $message): void
    {
        try {
            $action();
        } catch (\Throwable $error) {
            if ($error instanceof $expected) {
                return;
            }
            throw new \RuntimeException(sprintf(
                '%s (ожидалось %s, получено %s)',
                $message,
                $expected,
                $error::class,
            ));
        }

        throw new \RuntimeException("$message (исключение не выброшено)");
    }
}
