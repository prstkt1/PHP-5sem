<?php
declare(strict_types=1);

$errors = [];
$success = false;

$name = '';
$targetPerWeek = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $targetPerWeek = trim($_POST['targetPerWeek'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Назва звички обов\'язкова.';
    }

    if ($targetPerWeek === '') {
        $errors['targetPerWeek'] = 'Вкажіть ціль на тиждень.';
    } elseif (!ctype_digit($targetPerWeek) || (int)$targetPerWeek < 1 || (int)$targetPerWeek > 7) {
        $errors['targetPerWeek'] = 'Ціль має бути цілим числом від 1 до 7.';
    }

    if (empty($errors)) {
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="UTF-8">
<title>Практикум №2</title>
</head>
<body>
<h1>Нова звичка</h1>

<?php if ($success): ?>
  <p class="success">Звичку «<?= htmlspecialchars($name) ?>» додано! Ціль: <?= htmlspecialchars($targetPerWeek) ?> раз(ів) на тиждень.</p>
<?php endif; ?>

<form method="post" action="form.php" id="habitForm" novalidate>
  <label for="name">Назва звички</label>
  <input type="text" id="name" name="name" required
         value="<?= htmlspecialchars($name) ?>">
  <?php if (isset($errors['name'])): ?>
    <div class="error"><?= htmlspecialchars($errors['name']) ?></div>
  <?php endif; ?>

  <label for="targetPerWeek">Ціль (разів на тиждень, 1–7)</label>
  <input type="number" id="targetPerWeek" name="targetPerWeek" min="1" max="7" required
         value="<?= htmlspecialchars($targetPerWeek) ?>">
  <?php if (isset($errors['targetPerWeek'])): ?>
    <div class="error"><?= htmlspecialchars($errors['targetPerWeek']) ?></div>
  <?php endif; ?>

  <div class="checkbox-row">
    <input type="checkbox" id="doneToday" name="doneToday">
    <label for="doneToday" style="margin:0;">Вже виконано сьогодні</label>
  </div>

  <div id="jsError" class="error"></div>

  <button type="submit">Додати звичку</button>
</form>

<script>
  const DRAFT_KEY = 'habitDraft_doneToday';
  const nameInput = document.getElementById('name');
  const targetInput = document.getElementById('targetPerWeek');
  const doneCheckbox = document.getElementById('doneToday');
  const jsError = document.getElementById('jsError');
  const form = document.getElementById('habitForm');


  const savedDraft = localStorage.getItem(DRAFT_KEY);
  if (savedDraft !== null) {
    doneCheckbox.checked = JSON.parse(savedDraft);
  }

  doneCheckbox.addEventListener('change', () => {
    localStorage.setItem(DRAFT_KEY, JSON.stringify(doneCheckbox.checked));
  });

  form.addEventListener('submit', (event) => {
    jsError.textContent = '';
    const nameValue = nameInput.value.trim();
    const targetValue = targetInput.value.trim();
    const targetNum = Number(targetValue);

    if (nameValue === '') {
      jsError.textContent = 'Назва звички не може бути порожньою.';
      event.preventDefault();
      return;
    }
    if (!Number.isInteger(targetNum) || targetNum < 1 || targetNum > 7) {
      jsError.textContent = 'Ціль має бути цілим числом від 1 до 7.';
      event.preventDefault();
      return;
    }
  });

  <?php if ($success): ?>
  localStorage.removeItem(DRAFT_KEY);
  <?php endif; ?>
</script>
</body>
</html>
