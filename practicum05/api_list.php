<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $stmt = $pdo->prepare(
        'SELECT id, name, target_per_week, current_streak
         FROM habits WHERE name LIKE :q ORDER BY id'
    );
    $stmt->execute(['q' => '%' . $q . '%']);
} else {
    $stmt = $pdo->query(
        'SELECT id, name, target_per_week, current_streak FROM habits ORDER BY id'
    );
}

echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
