<?php
// ==========================================
// Константы
// ==========================================
const UNIVERSITY = "Алматинский технологический университет";
const DISCIPLINE  = "Программирование на PHP";
const MAX_SCORE   = 100;

// ==========================================
// Переменные — данные студента
// ==========================================
$lastName  = "Иванов";
$firstName = "Данияр";
$group     = "ИС-23-22";
$course    = 3;

// ==========================================
// Переменные — исходные данные варианта
// (Вариант 1. Карточка успеваемости: три оценки -> средний балл)
// ==========================================
$grade1 = 85;
$grade2 = 90;
$grade3 = 78;

// Типы данных (для наглядности используем разные типы)
$studentName = $lastName . " " . $firstName; // string
$isEnrolled  = true;                          // bool
$subjects    = ["PHP", "HTML", "CSS"];        // array

// ==========================================
// Вычисление результата
// ==========================================
$averageGrade = ($grade30 + $grade20 + $grade25) / 3;

// ==========================================
// Определение статуса (if...else)
// ==========================================
if ($averageGrade >= 50) {
    $status = "Дисциплина освоена";
    $statusClass = "success";
} else {
    $status = "Необходимо повысить результат";
    $statusClass = "warning";
}

$reportDate = date("d.m.Y");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная №1 — Карточка студента</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #f4f6f8;
        }
        .card {
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h1 { margin-top: 0; font-size: 22px; }
        h2 { font-size: 18px; color: #555; font-weight: normal; }
        .block {
            margin: 15px 0;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .block p { margin: 6px 0; }
        .success { color: green; font-weight: bold; }
        .warning { color: red; font-weight: bold; }
        .date { color: #888; font-size: 13px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <h1><?= UNIVERSITY ?></h1>
        <h2><?= DISCIPLINE ?></h2>

        <div class="block">
            <p><strong>Студент:</strong> <?= $studentName ?></p>
            <p><strong>Группа:</strong> <?= $group ?>; <strong>курс:</strong> <?= $course ?></p>
            <p><strong>Предметы:</strong> <?= implode(", ", $subjects) ?></p>
        </div>

        <div class="block">
            <p><strong>Исходные данные (оценки):</strong></p>
            <p>Оценка 1: <?= $grade30 ?></p>
            <p>Оценка 2: <?= $grade20 ?></p>
            <p>Оценка 3: <?= $grade25 ?></p>
        </div>

        <div class="block">
            <p><strong>Средний балл:</strong> <?= round($averageGrade, 2) ?> из <?= MAX_SCORE ?></p>
            <p><strong>Статус:</strong> <span class="<?= $statusClass ?>"><?= $status ?></span></p>
        </div>

        <p class="date">Дата формирования: <?= $reportDate ?></p>
    </div>
</body>
</html>
