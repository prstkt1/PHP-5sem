CREATE TABLE IF NOT EXISTS habits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    target_per_week TINYINT NOT NULL,
    current_streak INT NOT NULL DEFAULT 0
) CHARACTER SET utf8mb4;

INSERT INTO habits (name, target_per_week, current_streak) VALUES
('Ранкова зарядка', 5, 3),
('Читання 30 хвилин', 7, 12),
('Вивчення англійської', 4, 0);
