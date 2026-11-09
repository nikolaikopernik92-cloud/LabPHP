<?php

declare(strict_types=1);

namespace University\Schedule\Contract;

use University\Schedule\Model\Lesson;

interface LessonRepositoryInterface
{
    public function add(Lesson $lesson): void;

    /** @return list<Lesson> */
    public function all(): array;

    /** @return list<Lesson> */
    public function findByTeacher(int $teacherId): array;

    /** @return list<Lesson> */
    public function findByCourse(string $courseCode): array;

    /** @return list<Lesson> */
    public function findByDay(int $day): array;
}
