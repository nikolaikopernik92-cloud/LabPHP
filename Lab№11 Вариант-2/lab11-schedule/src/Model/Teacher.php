<?php

declare(strict_types=1);

namespace University\Schedule\Model;

use University\Schedule\Exception\InvalidScheduleException;

final readonly class Teacher
{
    public function __construct(
        public int $id,
        public string $fullName,
    ) {
        if ($id < 1) {
            throw new InvalidScheduleException('Идентификатор преподавателя должен быть положительным.');
        }
        if (trim($fullName) === '') {
            throw new InvalidScheduleException('ФИО преподавателя обязательно.');
        }
    }
}
