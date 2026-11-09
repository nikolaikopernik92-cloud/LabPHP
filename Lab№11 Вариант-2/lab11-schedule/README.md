# Лабораторная работа 11. Вариант 2: Расписание

Типы: Course, Teacher, Lesson, ScheduleService (+ контракт, репозиторий, 2 исключения).
Операции: добавление занятия (с проверкой конфликта преподавателя) и поиск (по преподавателю, курсу, дню).

## Запуск
    composer validate
    composer install
    composer dump-autoload
    composer show
    php public/index.php
    composer check

## Таблица соответствия FQCN и файлов
| Полное имя класса | Файл |
|---|---|
| University\Schedule\Model\Course | src/Model/Course.php |
| University\Schedule\Model\Teacher | src/Model/Teacher.php |
| University\Schedule\Model\Lesson | src/Model/Lesson.php |
| University\Schedule\Contract\LessonRepositoryInterface | src/Contract/LessonRepositoryInterface.php |
| University\Schedule\Repository\InMemoryLessonRepository | src/Repository/InMemoryLessonRepository.php |
| University\Schedule\Service\ScheduleService | src/Service/ScheduleService.php |
| University\Schedule\Exception\InvalidScheduleException | src/Exception/InvalidScheduleException.php |
| University\Schedule\Exception\ScheduleConflictException | src/Exception/ScheduleConflictException.php |
| University\Schedule\Tests\Support\Assert | tests/Support/Assert.php |

## Две намеренные ошибки PSR-4 (для отчёта; выполнить на копии и вернуть обратно)
1. **Регистр каталога.** Переименовать `src/Model` в `src/model` и запустить `php public/index.php`.
   На Linux: `Class "University\Schedule\Model\Course" not found`.
   Причина: PSR-4 чувствителен к регистру, namespace `Model` ≠ каталог `model`.
   Исправление: вернуть имя `src/Model`.
2. **Namespace не соответствует пути.** В `src/Service/ScheduleService.php` заменить
   `namespace University\Schedule\Service;` на `namespace University\Schedule\Services;`
   и выполнить `composer dump-autoload -o` (или `composer validate`/dump-autoload покажет warning
   «does not comply with psr-4 autoloading standard»); `php public/index.php` выдаст `Class ... not found`.
   Исправление: вернуть `Service`, снова `composer dump-autoload`.

## Таблица тестирования
Запустите `php tests/run.php` и вставьте фактический вывод. Ожидаемо: 12 строк [PASS] и «Все проверки пройдены.»

| № | Проверка | Ожидаемый результат |
|---|---|---|
| 1 | Добавление и поиск по преподавателю | 1 занятие; чужой id — 0 |
| 2 | Поиск по курсу | 1 занятие DB1201 |
| 3 | Поиск по дню и сортировка | раннее занятие первым |
| 4 | Конфликт времени у преподавателя | ScheduleConflictException |
| 5 | Занятия встык | конфликта нет |
| 6 | Разные преподаватели в одно время | конфликта нет |
| 7 | Неверный код курса | InvalidScheduleException |
| 8 | Граница дня недели 7/8 | 7 допустим, 8 — исключение |
| 9 | Неверный формат времени | InvalidScheduleException |
| 10 | PSR-4: путь Course | src/Model/Course.php |
| 11 | PSR-4: несуществующий класс | class_exists = false |
| 12 | autoload-dev: Assert | tests/Support/Assert.php |
