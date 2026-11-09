<?php
$pdo = new PDO(
    'mysql:host=localhost;dbname=habits_db;charset=utf8mb4',
    'habits',
    'habits123',
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);