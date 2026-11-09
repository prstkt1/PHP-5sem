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

$id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id < 1) {
    http_response_code(422);
    echo json_encode(['error' => 'Некоректний id']);
    exit;
}

$stmt = $pdo->prepare('UPDATE habits SET current_streak = current_streak + 1 WHERE id = :id');
$stmt->execute(['id' => $id]);

$stmt = $pdo->prepare(
    'SELECT id, name, target_per_week, current_streak FROM habits WHERE id = :id'
);
$stmt->execute(['id' => $id]);
$habit = $stmt->fetch();

if (!$habit) {
    http_response_code(404);
    echo json_encode(['error' => 'Звичку не знайдено']);
    exit;
}

echo json_encode($habit, JSON_UNESCAPED_UNICODE);
