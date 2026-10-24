<?php
require_once 'db.php';
require_once 'habits.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        deleteHabit($pdo, $id);
    }
}

header('Location: index.php');
exit;
