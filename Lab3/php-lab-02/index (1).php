<?php
declare(strict_types=1);

/**
 * Лабораторная работа №2
 * Типы данных, выражения и форматированный вывод
 * Индивидуальный вариант №3 — Температура (Цельсий -> Фаренгейт)
 */

// ---------------------------------------------------------
// Задание 5 (используется и далее): константа проходного балла
// ---------------------------------------------------------
const PASSING_SCORE = 50;

// ---------------------------------------------------------
// Задание 2. Данные студента
// ---------------------------------------------------------
$studentName  = "Оразбаев Махсуд";
$course       = 3;
$program      = "Информационные системы";
$averageGrade = 85.75;
$hasDebt      = false;
$debtText     = $hasDebt ? "есть" : "нет";

// ---------------------------------------------------------
// Задание 4. Расчёт итоговой оценки
// R = 0.30L + 0.20S + 0.50E
// ---------------------------------------------------------
$laboratoryGrade  = 85;
$independentGrade = 78;
$examGrade        = 92;

$result = $laboratoryGrade * 0.30
        + $independentGrade * 0.20
        + $examGrade * 0.50;

$formattedResult = number_format($result, 2, ".", " ");

// ---------------------------------------------------------
// Задание 5. Статус студента (тернарный оператор)
// ---------------------------------------------------------
$status = $result >= PASSING_SCORE
    ? "Дисциплина освоена"
    : "Дисциплина не освоена";

// ---------------------------------------------------------
// Задание 6. Явное преобразование типов
// ---------------------------------------------------------
$inputValue    = "125";
$integerValue  = (int) $inputValue;
$floatValue    = (float) $inputValue;
$booleanValue  = (bool) $inputValue;

// ---------------------------------------------------------
// Задание 7. Сравнение значений (строгое / нестрогое)
// ---------------------------------------------------------
$comparisons = [
    ['expr' => '"10" == 10',   'value' => "10" == 10],
    ['expr' => '"10" === 10',  'value' => "10" === 10],
    ['expr' => '0 == false',   'value' => 0 == false],
    ['expr' => '0 === false',  'value' => 0 === false],
    ['expr' => 'null == false','value' => null == false],
    ['expr' => 'null === false','value' => null === false],
];

// ---------------------------------------------------------
// Индивидуальный вариант №3. Температура
// Формула: fahrenheit = celsius * 9 / 5 + 32
// ---------------------------------------------------------
const F_MULTIPLIER = 9 / 5;
const F_OFFSET      = 32;

$celsius    = 24.5;                                   // исходные данные (float)
$fahrenheit = $celsius * F_MULTIPLIER + F_OFFSET;      // арифметическое выражение
$isFreezing = $celsius === 0.0;                        // строгое сравнение
$tempLabel  = $celsius . "°C";                         // строковая конкатенация

$formattedFahrenheit = number_format($fahrenheit, 2, ".", " ");

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа №2 — PHP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; margin-bottom: 25px; }
        th, td { border: 1px solid #999; padding: 8px 12px; text-align: left; }
        th { background: #f0f0f0; }
        h1 { font-size: 22px; }
        h2 { font-size: 18px; margin-top: 35px; }
    </style>
</head>
<body>

<h1>Лабораторная работа №2. Типы данных, выражения и форматированный вывод</h1>

<h2>Задание 2. Информация о студенте</h2>
<p>ФИО: <?= htmlspecialchars($studentName) ?></p>
<p>Курс: <?= $course ?></p>
<p>Образовательная программа: <?= htmlspecialchars($program) ?></p>
<p>Средний балл: <?= $averageGrade ?></p>
<p>Задолженность: <?= $debtText ?></p>

<h2>Задание 3. Определение типов (var_dump)</h2>
<pre><?php
var_dump($studentName);
var_dump($course);
var_dump($averageGrade);
var_dump($hasDebt);
?></pre>

<h2>Задание 4–5. Итоговая оценка и статус</h2>
<p>Лабораторные: <?= $laboratoryGrade ?>, Самостоятельная: <?= $independentGrade ?>, Экзамен: <?= $examGrade ?></p>
<p>Итоговая оценка: <strong><?= $formattedResult ?></strong></p>
<p>Статус: <strong><?= $status ?></strong></p>

<h2>Задание 6. Явное преобразование типов</h2>
<pre><?php
var_dump($integerValue, $floatValue, $booleanValue);
?></pre>

<h2>Задание 7. Сравнение значений</h2>
<table>
    <tr><th>Выражение</th><th>Результат</th></tr>
    <?php foreach ($comparisons as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['expr']) ?></td>
            <td><?= var_export($row['value'], true) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Задание 8. Форматированный HTML-вывод (сводная таблица)</h2>
<table>
    <tr>
        <th>Студент</th><th>Курс</th><th>Итоговая оценка</th><th>Статус</th>
    </tr>
    <tr>
        <?php
        printf(
            "<td>%s</td><td>%d</td><td>%.2f</td><td>%s</td>",
            htmlspecialchars($studentName),
            $course,
            $result,
            $status
        );
        ?>
    </tr>
</table>

<h2>Индивидуальный вариант №3. Температура (Цельсий → Фаренгейт)</h2>
<p>Исходная температура: <?= htmlspecialchars($tempLabel) ?></p>
<p>Формула: F = C × 9/5 + 32</p>
<p>Результат: <strong><?= $formattedFahrenheit ?>°F</strong></p>
<p>Проверка на 0°C (строгое сравнение === 0.0): <?= var_export($isFreezing, true) ?></p>

<pre><?php
printf(
    "Температура: %.1f°C = %.2f°F",
    $celsius,
    $fahrenheit
);
?></pre>

</body>
</html>
