<?php

declare(strict_types=1);

namespace University\Schedule\Repository;

use University\Schedule\Contract\LessonRepositoryInterface;
use University\Schedule\Model\Lesson;

final class InMemoryLessonRepository implements LessonRepositoryInterface
{
    /** @var list<Lesson> */
    private array $lessons = [];

    public function add(Lesson $lesson): void
    {
        $this->lessons[] = $lesson;
    }

    public function all(): array
    {
        return $this->lessons;
    }

    public function findByTeacher(int $teacherId): array
    {
        return array_values(array_filter(
            $this->lessons,
            static fn (Lesson $lesson): bool => $lesson->teacher->id === $teacherId,
        ));
    }

    public function findByCourse(string $courseCode): array
    {
        return array_values(array_filter(
            $this->lessons,
            static fn (Lesson $lesson): bool => $lesson->course->code === $courseCode,
        ));
    }

    public function findByDay(int $day): array
    {
        return array_values(array_filter(
            $this->lessons,
            static fn (Lesson $lesson): bool => $lesson->day === $day,
        ));
    }
}
