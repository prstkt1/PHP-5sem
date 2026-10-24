CREATE DATABASE IF NOT EXISTS practicum4
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE practicum4;

CREATE TABLE IF NOT EXISTS habits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    target_per_week TINYINT UNSIGNED NOT NULL,
    current_streak INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO habits (name, target_per_week, current_streak) VALUES
    ('Ранкова зарядка', 5, 12),
    ('Читання книги', 7, 3),
    ('Вивчення англійської', 4, 8);
