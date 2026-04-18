CREATE DATABASE IF NOT EXISTS datab;
USE datab;

DROP TABLE IF EXISTS requests;

CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'Новая',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO requests (title, description, status) VALUES
('Проблема с сайтом', 'Не открывается главная страница', 'Новая'),
('Ошибка входа', 'Не могу войти в аккаунт', 'В работе'),
('Добавить функцию', 'Нужно добавить поиск', 'Завершена');
