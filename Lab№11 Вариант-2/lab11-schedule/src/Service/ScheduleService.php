<?php

declare(strict_types=1);

namespace University\Schedule\Service;

use University\Schedule\Contract\LessonRepositoryInterface;
use University\Schedule\Exception\ScheduleConflictException;
use University\Schedule\Model\Lesson;

final readonly class ScheduleService
{
    public function __construct(
        private LessonRepositoryInterface $repository,
    ) {
    }

    public function addLesson(Lesson $lesson): void
    {
        foreach ($this->repository->findByTeacher($lesson->teacher->id) as $existing) {
            if ($existing->overlaps($lesson)) {
                throw new ScheduleConflictException(sprintf(
                    'Преподаватель %s уже занят: %s.',
                    $lesson->teacher->fullName,
                    $existing->label(),
                ));
            }
        }

        $this->repository->add($lesson);
    }

    /** @return list<Lesson> */
    public function byTeacher(int $teacherId): array
    {
        return $this->sorted($this->repository->findByTeacher($teacherId));
    }

    /** @return list<Lesson> */
    public function byCourse(string $courseCode): array
    {
        return $this->sorted($this->repository->findByCourse($courseCode));
    }

    /** @return list<Lesson> */
    public function byDay(int $day): array
    {
        return $this->sorted($this->repository->findByDay($day));
    }

    /**
     * @param list<Lesson> $lessons
     * @return list<Lesson>
     */
    private function sorted(array $lessons): array
    {
        usort(
            $lessons,
            static fn (Lesson $a, Lesson $b): int
                => [$a->day, $a->startMinutes()] <=> [$b->day, $b->startMinutes()],
        );

        return $lessons;
    }
}
