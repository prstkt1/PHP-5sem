<?php
require_once 'db.php';
require_once 'habits.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name   = trim($_POST['name'] ?? '');
$target = trim($_POST['target_per_week'] ?? '');
$streak = trim($_POST['current_streak'] ?? '0');

$errors = validateHabit($name, $target, $streak);

if (empty($errors)) {
    addHabit($pdo, $name, (int)$target, (int)$streak);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Помилка додавання</title>
</head>
<body>
    <h1>Не вдалося додати звичку</h1>
    <ul>
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
    <p><a href="index.php">Назад</a></p>
</body>
</html>
