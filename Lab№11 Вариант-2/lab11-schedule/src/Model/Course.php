<?php

declare(strict_types=1);

namespace University\Schedule\Model;

use University\Schedule\Exception\InvalidScheduleException;

final readonly class Course
{
    public function __construct(
        public string $code,
        public string $title,
        public int $credits,
    ) {
        if (preg_match('/^[A-Z]{2,5}[0-9]{3,4}$/', $code) !== 1) {
            throw new InvalidScheduleException('Неверный код курса.');
        }
        if (trim($title) === '') {
            throw new InvalidScheduleException('Название курса обязательно.');
        }
        if ($credits < 1 || $credits > 10) {
            throw new InvalidScheduleException('Кредиты должны быть от 1 до 10.');
        }
    }
}
