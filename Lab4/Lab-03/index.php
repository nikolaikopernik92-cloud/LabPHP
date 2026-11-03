<?php
declare(strict_types=1);

/**
 * Лабораторная работа 3. Вариант 12: «Количество шагов».
 *
 * Правила проверки:
 *  - допустимое количество шагов за день: целое число от 0 до 100000;
 *  - null  -> день без данных, пропускается (continue);
 *  - -1    -> маркер завершения, обработка останавливается (break);
 *  - любое другое значение -> исключение InvalidArgumentException.
 *
 * Результат: сумма, среднее, лучший день, уровень активности.
 * Дополнительно: массив названий дней и список допустимых значений.
 *
 * Выбор теста: index.php?test=1 ... index.php?test=8
 */

const MIN_STEPS    = 0;
const MAX_STEPS    = 100000;
const STOP_MARKER  = -1;

// Названия дней (второй массив для дополнительного задания)
$days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

// Тестовые наборы данных (по одному на сценарий)
$tests = [
    1 => ['title' => 'Обычные корректные данные',
          'data'  => [8500, 12000, null, 6400, 10200, 4300, 9800]],
    2 => ['title' => 'Нижняя граница (0 шагов)',
          'data'  => [0, 0, 0, 0, 0, 0, 0]],
    3 => ['title' => 'Верхняя граница (100000 шагов)',
          'data'  => [100000, 99999, 0, 50000, 1, 100000, 75000]],
    4 => ['title' => 'Недопустимое значение (100001)',
          'data'  => [8000, 100001, 5000, 7000, 9000, 6000, 4000]],
    5 => ['title' => 'Только пропуски (null)',
          'data'  => [null, null, null, null, null, null, null]],
    6 => ['title' => 'Маркер остановки (-1)',
          'data'  => [6000, 9000, -1, 15000, 200000, 7000, 8000]],
    7 => ['title' => 'Отрицательное значение, не являющееся маркером (-2)',
          'data'  => [8000, 9000, -2, 7000, 6000, 5000, 4000]],
    8 => ['title' => 'Пустой массив',
          'data'  => []],
];

// Выбор теста из адресной строки; при неверном значении берём тест 1
$testId = 1;
if (isset($_GET['test']) && is_string($_GET['test']) && ctype_digit($_GET['test'])) {
    $requested = (int)$_GET['test'];
    if (isset($tests[$requested])) {
        $testId = $requested;
    }
}
$steps = $tests[$testId]['data'];

// Накопители статистики
$total      = 0;      // сумма шагов
$count      = 0;      // число обработанных дней
$skipped    = 0;      // число пропущенных дней (null)
$maxSteps   = null;   // максимум шагов за день
$bestDay    = null;   // название лучшего дня
$stoppedAt  = null;   // день, на котором сработал маркер
$validItems = [];     // допустимые значения: ['day' => ..., 'steps' => ...]

echo "<!DOCTYPE html>\n<html lang=\"ru\">\n<head>\n<meta charset=\"UTF-8\">\n";
echo "<title>Лабораторная работа 3. Вариант 12</title>\n";
echo "<style>body{font-family:Arial,sans-serif;max-width:720px;margin:24px auto;padding:0 12px}"
   . "nav a{margin-right:8px}.err{color:#b00020}.ok{color:#1b5e20}</style>\n</head>\n<body>\n";

echo "<h1>Анализ количества шагов за неделю</h1>\n<nav>Тест: ";
foreach ($tests as $id => $test) {
    echo "<a href=\"?test=$id\">$id</a>";
}
echo "</nav>\n";
echo "<p><b>Сценарий $testId:</b> " . htmlspecialchars($tests[$testId]['title']) . "</p>\n";

try {
    // Исключение №1: нет исходных данных вообще
    if ($steps === []) {
        throw new RuntimeException('Массив шагов пуст');
    }

    // Основной перебор массива
    foreach ($steps as $index => $value) {
        $day = $days[$index] ?? 'день №' . ($index + 1);

        // Нет данных за день: строгая проверка на null, переходим к следующему
        if ($value === null) {
            $skipped++;
            continue;
        }

        // Специальный маркер: дальнейшие элементы не обрабатываются
        if ($value === STOP_MARKER) {
            $stoppedAt = $day;
            break;
        }

        // Исключение №2: значение не целое или вне диапазона 0..100000
        if (!is_int($value) || $value < MIN_STEPS || $value > MAX_STEPS) {
            throw new InvalidArgumentException("Некорректное значение шагов: $day");
        }

        // Допустимое значение: накапливаем статистику
        $total += $value;
        $count++;
        $validItems[] = ['day' => $day, 'steps' => $value];

        // Поиск лучшего дня (при равенстве остаётся первый)
        if ($bestDay === null || $value > $maxSteps) {
            $maxSteps = $value;
            $bestDay  = $day;
        }
    }

    // Исключение №3: были только пропуски или маркер стоял первым
    if ($count === 0) {
        throw new RuntimeException('Нет данных для расчета');
    }

    $average = round($total / $count, 2);

    // Итоговая классификация через match
    $level = match (true) {
        $average >= 10000 => 'высокий',
        $average >= 7500  => 'хороший',
        $average >= 5000  => 'средний',
        default           => 'низкий',
    };

    echo "<h2>Результат анализа</h2>\n";
    echo "<p>Обработано дней: $count</p>\n";
    echo "<p>Пропущено дней (нет данных): $skipped</p>\n";
    if ($stoppedAt !== null) {
        echo "<p>Обработка остановлена маркером на дне: " . htmlspecialchars($stoppedAt) . "</p>\n";
    }
    echo "<p>Сумма шагов: " . number_format($total, 0, '', ' ') . "</p>\n";
    echo "<p>Среднее за день: " . number_format($average, 2, ',', ' ') . "</p>\n";
    echo "<p>Лучший день: " . htmlspecialchars((string)$bestDay)
       . " (" . number_format($maxSteps, 0, '', ' ') . " шагов)</p>\n";
    echo "<p>Уровень активности: <b>$level</b></p>\n";

    // Дополнительное задание: HTML-список допустимых значений (цикл for)
    echo "<h3>Допустимые значения</h3>\n<ul>\n";
    for ($i = 0; $i < count($validItems); $i++) {
        $item = $validItems[$i];
        $n    = $item['steps'];

        // Статус дня: ветвление if / elseif / else
        if ($n >= 10000) {
            $status = 'цель достигнута';
        } elseif ($n >= 5000 && $n < 10000) {
            $status = 'средняя активность';
        } else {
            $status = 'малоподвижный день';
        }

        echo "<li>" . htmlspecialchars($item['day']) . ": "
           . number_format($n, 0, '', ' ') . " шагов — $status</li>\n";
    }
    echo "</ul>\n";

} catch (InvalidArgumentException | RuntimeException $e) {
    // Безопасное сообщение пользователю без технических деталей
    echo "<p class=\"err\">Ошибка: " . htmlspecialchars($e->getMessage()) . "</p>\n";
} finally {
    echo "<p>Обработка завершена</p>\n";
}

echo "</body>\n</html>\n";
