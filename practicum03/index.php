<?php
declare(strict_types=1);

require_once __DIR__ . '/classes/Habit.php';
require_once __DIR__ . '/classes/MeasurableHabit.php';
require_once __DIR__ . '/classes/HabitTracker.php';
require_once __DIR__ . '/lib/functions.php';

function daysAgo(int ...$offsets): array
{
    return array_map(fn(int $n) => date('Y-m-d', strtotime("-{$n} days")), $offsets);
}

$tracker = new HabitTracker();
$tracker->addHabit(new Habit('Читання', 5, daysAgo(0, 1, 2, 4)));
$tracker->addHabit(new Habit('Зарядка', 7, daysAgo(1, 2, 3)));
$tracker->addHabit(new MeasurableHabit('Вода', 7, 'склянок', 8, daysAgo(0, 1, 2, 3, 4, 5, 6)));
$tracker->addHabit(new MeasurableHabit('Біг', 3, 'км', 5, daysAgo(0, 2)));
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Трекер звичок</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        table { border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 6px 12px; text-align: left; }
    </style>
</head>
<body>
<h1>Трекер звичок</h1>
<table>
    <tr>
        <th>#</th><th>Тип</th><th>Опис</th><th>Частота</th>
        <th>За 7 днів</th><th>Виконання</th><th>Серія (днів)</th>
    </tr>
    <?php foreach ($tracker->listAll() as $i => $habit): ?>
        <?php
        $done = $habit->countLastDays(7);
        $percent = calcCompletion($done, $habit->getTargetPerWeek());
        ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= $habit instanceof MeasurableHabit ? 'Вимірювана' : 'Звичайна' ?></td>
            <td><?= htmlspecialchars($habit->getInfo()) ?></td>
            <td><?= formatFrequency($habit->getTargetPerWeek()) ?></td>
            <td><?= $done ?></td>
            <td><?= $percent ?>%</td>
            <td><?= $tracker->streakFor($habit->getName()) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>