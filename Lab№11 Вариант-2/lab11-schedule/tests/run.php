<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use University\Schedule\Exception\InvalidScheduleException;
use University\Schedule\Exception\ScheduleConflictException;
use University\Schedule\Model\Course;
use University\Schedule\Model\Lesson;
use University\Schedule\Model\Teacher;
use University\Schedule\Repository\InMemoryLessonRepository;
use University\Schedule\Service\ScheduleService;
use University\Schedule\Tests\Support\Assert;

function makeService(): ScheduleService
{
    return new ScheduleService(new InMemoryLessonRepository());
}

$php = new Course('PHP1101', 'PHP', 5);
$db = new Course('DB1201', 'Базы данных', 5);
$ivanov = new Teacher(1, 'Иванов И. И.');
$petrova = new Teacher(2, 'Петрова А. С.');

$tests = [
    '1. Успех: добавление и поиск по преподавателю' => function () use ($php, $ivanov): void {
        $service = makeService();
        $service->addLesson(new Lesson($php, $ivanov, 1, '09:00', 90));
        Assert::same(1, count($service->byTeacher(1)), 'Должно быть одно занятие');
        Assert::same(0, count($service->byTeacher(99)), 'Чужих занятий быть не должно');
    },
    '2. Успех: поиск по курсу' => function () use ($php, $db, $ivanov): void {
        $service = makeService();
        $service->addLesson(new Lesson($php, $ivanov, 1, '09:00', 90));
        $service->addLesson(new Lesson($db, $ivanov, 2, '09:00', 90));
        Assert::same(1, count($service->byCourse('DB1201')), 'Одно занятие курса DB1201');
    },
    '3. Успех: поиск по дню и сортировка по времени' => function () use ($php, $db, $ivanov, $petrova): void {
        $service = makeService();
        $service->addLesson(new Lesson($db, $petrova, 1, '12:00', 60));
        $service->addLesson(new Lesson($php, $ivanov, 1, '08:00', 60));
        $found = $service->byDay(1);
        Assert::same(2, count($found), 'Два занятия в понедельник');
        Assert::same('08:00', $found[0]->startTime, 'Раннее занятие первым');
    },
    '4. Ошибка: конфликт времени у преподавателя' => function () use ($php, $db, $ivanov): void {
        $service = makeService();
        $service->addLesson(new Lesson($php, $ivanov, 1, '09:00', 90));
        Assert::throws(
            fn () => $service->addLesson(new Lesson($db, $ivanov, 1, '10:00', 60)),
            ScheduleConflictException::class,
            'Пересечение должно быть отклонено',
        );
    },
    '5. Граница: занятие встык не конфликтует' => function () use ($php, $db, $ivanov): void {
        $service = makeService();
        $service->addLesson(new Lesson($php, $ivanov, 1, '09:00', 60));
        $service->addLesson(new Lesson($db, $ivanov, 1, '10:00', 60));
        Assert::same(2, count($service->byTeacher(1)), 'Два занятия подряд допустимы');
    },
    '6. Успех: разные преподаватели в одно время' => function () use ($php, $db, $ivanov, $petrova): void {
        $service = makeService();
        $service->addLesson(new Lesson($php, $ivanov, 1, '09:00', 90));
        $service->addLesson(new Lesson($db, $petrova, 1, '09:00', 90));
        Assert::same(2, count($service->byDay(1)), 'Конфликта нет');
    },
    '7. Ошибка: неверный код курса' => function (): void {
        Assert::throws(
            fn () => new Course('bad', 'Курс', 5),
            InvalidScheduleException::class,
            'Код bad недопустим',
        );
    },
    '8. Граница: день недели 7 допустим, 8 нет' => function () use ($php, $ivanov): void {
        $lesson = new Lesson($php, $ivanov, 7, '10:00', 60);
        Assert::same(7, $lesson->day, 'День 7 допустим');
        Assert::throws(
            fn () => new Lesson($php, $ivanov, 8, '10:00', 60),
            InvalidScheduleException::class,
            'День 8 недопустим',
        );
    },
    '9. Ошибка: неверный формат времени' => function () use ($php, $ivanov): void {
        Assert::throws(
            fn () => new Lesson($php, $ivanov, 1, '25:00', 60),
            InvalidScheduleException::class,
            'Время 25:00 недопустимо',
        );
    },
    '10. PSR-4: класс Course загружен из src/Model/Course.php' => function (): void {
        $file = str_replace('\\', '/', (new ReflectionClass(Course::class))->getFileName());
        Assert::same(true, str_ends_with($file, '/src/Model/Course.php'), 'Неверный путь файла: ' . $file);
    },
    '11. PSR-4: несуществующий класс не загружается' => function (): void {
        Assert::same(
            false,
            class_exists('University\\Schedule\\Model\\Missing'),
            'Класса Missing быть не должно',
        );
    },
    '12. PSR-4: autoload-dev загружает Tests\\Support\\Assert' => function (): void {
        $file = str_replace('\\', '/', (new ReflectionClass(Assert::class))->getFileName());
        Assert::same(true, str_ends_with($file, '/tests/Support/Assert.php'), 'Неверный путь файла: ' . $file);
    },
];

$failed = 0;
foreach ($tests as $name => $test) {
    try {
        $test();
        echo "[PASS] $name\n";
    } catch (Throwable $error) {
        $failed++;
        echo "[FAIL] $name: {$error->getMessage()}\n";
    }
}

if ($failed > 0) {
    echo "Провалено проверок: $failed\n";
    exit(1);
}

echo "Все проверки пройдены.\n";
