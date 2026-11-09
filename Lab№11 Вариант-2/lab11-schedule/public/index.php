<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use University\Schedule\Exception\ScheduleConflictException;
use University\Schedule\Model\Course;
use University\Schedule\Model\Lesson;
use University\Schedule\Model\Teacher;
use University\Schedule\Repository\InMemoryLessonRepository;
use University\Schedule\Service\ScheduleService;

$service = new ScheduleService(new InMemoryLessonRepository());

$php = new Course('PHP1101', 'Программирование на PHP', 5);
$db = new Course('DB1201', 'Базы данных', 5);
$ivanov = new Teacher(1, 'Иванов И. И.');
$petrova = new Teacher(2, 'Петрова А. С.');

$service->addLesson(new Lesson($php, $ivanov, 1, '09:00', 90));
$service->addLesson(new Lesson($db, $petrova, 1, '10:30', 90));
$service->addLesson(new Lesson($php, $ivanov, 3, '11:00', 90));

try {
    $service->addLesson(new Lesson($db, $ivanov, 1, '09:30', 60));
} catch (ScheduleConflictException $error) {
    echo 'Конфликт: ', $error->getMessage(), PHP_EOL;
}

echo PHP_EOL, "Занятия преподавателя Иванов И. И.:", PHP_EOL;
foreach ($service->byTeacher(1) as $lesson) {
    echo $lesson->label(), PHP_EOL;
}

echo PHP_EOL, "Занятия курса DB1201:", PHP_EOL;
foreach ($service->byCourse('DB1201') as $lesson) {
    echo $lesson->label(), PHP_EOL;
}

echo PHP_EOL, "Понедельник:", PHP_EOL;
foreach ($service->byDay(1) as $lesson) {
    echo $lesson->label(), PHP_EOL;
}
