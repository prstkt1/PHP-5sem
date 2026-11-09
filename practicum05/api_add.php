<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Дозволено лише POST']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}

$name   = trim($input['name'] ?? '');
$target = filter_var($input['target_per_week'] ?? null, FILTER_VALIDATE_INT);

if ($name === '' || $target === false || $target < 1 || $target > 7) {
    http_response_code(422);
    echo json_encode(['error' => 'Назва обов\'язкова, а ціль на тиждень - ціле число від 1 до 7']);
    exit;
}

$stmt = $pdo->prepare(
    'INSERT INTO habits (name, target_per_week, current_streak) VALUES (:name, :target, 0)'
);
$stmt->execute(['name' => $name, 'target' => $target]);

http_response_code(201);
echo json_encode([
    'id'              => (int)$pdo->lastInsertId(),
    'name'            => $name,
    'target_per_week' => $target,
    'current_streak'  => 0,
], JSON_UNESCAPED_UNICODE);
