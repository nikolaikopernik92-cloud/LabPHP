<?php

declare(strict_types=1);

namespace University\Schedule\Model;

use University\Schedule\Exception\InvalidScheduleException;

final readonly class Lesson
{
    private const array DAYS = [
        1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт', 5 => 'Пт', 6 => 'Сб', 7 => 'Вс',
    ];

    public function __construct(
        public Course $course,
        public Teacher $teacher,
        public int $day,
        public string $startTime,
        public int $durationMinutes,
    ) {
        if ($day < 1 || $day > 7) {
            throw new InvalidScheduleException('День недели должен быть от 1 до 7.');
        }
        if (preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $startTime) !== 1) {
            throw new InvalidScheduleException('Время начала должно быть в формате ЧЧ:ММ.');
        }
        if ($durationMinutes < 30 || $durationMinutes > 240) {
            throw new InvalidScheduleException('Длительность должна быть от 30 до 240 минут.');
        }
    }

    public function startMinutes(): int
    {
        [$hours, $minutes] = explode(':', $this->startTime);

        return (int) $hours * 60 + (int) $minutes;
    }

    public function endMinutes(): int
    {
        return $this->startMinutes() + $this->durationMinutes;
    }

    public function overlaps(self $other): bool
    {
        return $this->day === $other->day
            && $this->startMinutes() < $other->endMinutes()
            && $other->startMinutes() < $this->endMinutes();
    }

    public function label(): string
    {
        return sprintf(
            '%s %s (%d мин) | %s %s | %s',
            self::DAYS[$this->day],
            $this->startTime,
            $this->durationMinutes,
            $this->course->code,
            $this->course->title,
            $this->teacher->fullName,
        );
    }
}
