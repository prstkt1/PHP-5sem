<?php
function listAll(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM habits ORDER BY id')->fetchAll();
}

function streakFor(PDO $pdo, int $id): ?int
{
    $stmt = $pdo->prepare('SELECT current_streak FROM habits WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $value = $stmt->fetchColumn();
    return $value === false ? null : (int)$value;
}


function findHabit(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM habits WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    return $row === false ? null : $row;
}


function addHabit(PDO $pdo, string $name, int $targetPerWeek, int $currentStreak = 0): int
{
    $stmt = $pdo->prepare(
        'INSERT INTO habits (name, target_per_week, current_streak)
         VALUES (:name, :target, :streak)'
    );
    $stmt->execute([
        ':name'   => $name,
        ':target' => $targetPerWeek,
        ':streak' => $currentStreak,
    ]);
    return (int)$pdo->lastInsertId();
}


function updateStreak(PDO $pdo, int $id, int $streak): bool
{
    $stmt = $pdo->prepare('UPDATE habits SET current_streak = :streak WHERE id = :id');
    return $stmt->execute([':streak' => $streak, ':id' => $id]);
}


function updateHabit(PDO $pdo, int $id, string $name, int $targetPerWeek, int $streak): bool
{
    $stmt = $pdo->prepare(
        'UPDATE habits
         SET name = :name, target_per_week = :target, current_streak = :streak
         WHERE id = :id'
    );
    return $stmt->execute([
        ':name'   => $name,
        ':target' => $targetPerWeek,
        ':streak' => $streak,
        ':id'     => $id,
    ]);
}


function deleteHabit(PDO $pdo, int $id): bool
{
    $stmt = $pdo->prepare('DELETE FROM habits WHERE id = :id');
    return $stmt->execute([':id' => $id]);
}

function validateHabit(string $name, string $target, string $streak): array
{
    $errors = [];
    if ($name === '' || mb_strlen($name) > 100) {
        $errors[] = 'Назва обов’язкова (до 100 символів).';
    }
    if (filter_var($target, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 7]]) === false) {
        $errors[] = 'Ціль на тиждень — ціле число від 1 до 7.';
    }
    if (filter_var($streak, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
        $errors[] = 'Серія — ціле невід’ємне число.';
    }
    return $errors;
}
