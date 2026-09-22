<?php


$habits = [
    ['name' => 'Читання книг',        'targetPerWeek' => 5, 'doneThisWeek' => 5],
    ['name' => 'Фізичні вправи',      'targetPerWeek' => 4, 'doneThisWeek' => 2],
    ['name' => 'Медитація',           'targetPerWeek' => 7, 'doneThisWeek' => 7],
    ['name' => 'Прогулянка з собакою',    'targetPerWeek' => 7, 'doneThisWeek' => 4],
    ['name' => 'Ранкова зарядка',     'targetPerWeek' => 3, 'doneThisWeek' => 1],
];


function formatHabit(array $habit): string
{
    return sprintf(
        '%s: %d із %d разів на тиждень',
        $habit['name'],
        $habit['doneThisWeek'],
        $habit['targetPerWeek']
    );
}


function getHabitStatus(array $habit): string
{
    if ($habit['doneThisWeek'] >= $habit['targetPerWeek']) {
        $status = 'Виконано';
    } else {
        $status = 'В процесі';
    }

    return $status;
}


function getHabitPercent(array $habit): float
{
    if ($habit['targetPerWeek'] <= 0) {
        return 0.0;
    }

    return round(($habit['doneThisWeek'] / $habit['targetPerWeek']) * 100, 1);
}


$percentages = array_map('getHabitPercent', $habits);
$averagePercent = count($percentages) > 0
    ? round(array_sum($percentages) / count($percentages), 1)
    : 0.0;


$doneCount = count(array_filter($habits, fn(array $h): bool => getHabitStatus($h) === 'Виконано'));
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Трекер звичок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Трекер звичок</h1>

    <table class="habits-table">
        <thead>
            <tr>
                <th>Звичка</th>
                <th>Опис</th>
                <th>Виконання</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($habits as $habit): ?>
                <?php
                    $status = getHabitStatus($habit);
                    $percent = getHabitPercent($habit);
                    $statusClass = $status === 'Виконано' ? 'status-done' : 'status-progress';
                ?>
                <tr>
                    <td><?= htmlspecialchars($habit['name']) ?></td>
                    <td><?= htmlspecialchars(formatHabit($habit)) ?></td>
                    <td><?= $percent ?>%</td>
                    <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="summary">
        <p>Середній відсоток виконання по всіх звичках: <strong><?= $averagePercent ?>%</strong></p>
        <p>Виконано: <strong><?= $doneCount ?></strong> із <strong><?= count($habits) ?></strong></p>
    </div>
</body>
</html>
