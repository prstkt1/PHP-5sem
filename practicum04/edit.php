<?php
require_once 'db.php';
require_once 'habits.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $name   = trim($_POST['name'] ?? '');
    $target = trim($_POST['target_per_week'] ?? '');
    $streak = trim($_POST['current_streak'] ?? '');

    $errors = validateHabit($name, $target, $streak);

    if (empty($errors)) {
        updateHabit($pdo, $id, $name, (int)$target, (int)$streak);
        header('Location: index.php');
        exit;
    }
 
    $habit = ['id' => $id, 'name' => $name, 'target_per_week' => $target, 'current_streak' => $streak];
} else {
    $id    = (int)($_GET['id'] ?? 0);
    $habit = findHabit($pdo, $id);
    if ($habit === null) {
        http_response_code(404);
        exit('Звичку не знайдено. <a href="index.php">Назад</a>');
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагування звички</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        label { display: block; margin: 8px 0; }
        .err { color: #b00; }
    </style>
</head>
<body>
    <h1>Редагування звички</h1>

    <?php foreach ($errors as $e): ?>
        <p class="err"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>

    <form action="edit.php" method="post">
        <input type="hidden" name="id" value="<?= (int)$habit['id'] ?>">
        <label>Назва:
            <input type="text" name="name" maxlength="100" required
                   value="<?= htmlspecialchars($habit['name']) ?>">
        </label>
        <label>Ціль на тиждень (1–7):
            <input type="number" name="target_per_week" min="1" max="7" required
                   value="<?= htmlspecialchars((string)$habit['target_per_week']) ?>">
        </label>
        <label>Поточна серія:
            <input type="number" name="current_streak" min="0" required
                   value="<?= htmlspecialchars((string)$habit['current_streak']) ?>">
        </label>
        <button type="submit">Зберегти</button>
        <a href="index.php">Скасувати</a>
    </form>
</body>
</html>
