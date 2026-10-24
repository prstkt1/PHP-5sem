<?php
require_once 'db.php';
require_once 'habits.php';

$habits = listAll($pdo);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Трекер звичок</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; min-width: 600px; }
        th, td { border: 1px solid #999; padding: 6px 12px; text-align: left; }
        th { background: #eee; }
        form.inline { display: inline; }
        fieldset { margin-top: 2rem; max-width: 420px; }
        label { display: block; margin: 8px 0; }
    </style>
</head>
<body>
    <h1>Трекер звичок</h1>

    <?php if (empty($habits)): ?>
        <p>Звичок ще немає.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Назва</th>
                <th>Ціль на тиждень</th>
                <th>Поточна серія</th>
                <th>Дії</th>
            </tr>
            <?php foreach ($habits as $h): ?>
                <tr>
                    <td><?= (int)$h['id'] ?></td>
                    <td><?= htmlspecialchars($h['name']) ?></td>
                    <td><?= (int)$h['target_per_week'] ?></td>
                    <td><?= (int)$h['current_streak'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= (int)$h['id'] ?>">Редагувати</a>
                        <form class="inline" action="delete.php" method="post"
                              onsubmit="return confirm('Видалити звичку «<?= htmlspecialchars(addslashes($h['name']), ENT_QUOTES) ?>»?');">
                            <input type="hidden" name="id" value="<?= (int)$h['id'] ?>">
                            <button type="submit">Видалити</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <fieldset>
        <legend>Додати звичку</legend>
        <form action="add.php" method="post">
            <label>Назва:
                <input type="text" name="name" maxlength="100" required>
            </label>
            <label>Ціль на тиждень (1–7):
                <input type="number" name="target_per_week" min="1" max="7" required>
            </label>
            <label>Поточна серія:
                <input type="number" name="current_streak" min="0" value="0" required>
            </label>
            <button type="submit">Додати</button>
        </form>
    </fieldset>
</body>
</html>
