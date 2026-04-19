CREATE DATABASE IF NOT EXISTS datab;
USE datab;

DROP TABLE IF EXISTS requests;
DROP TABLE IF EXISTS reg;

CREATE TABLE reg (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status INT DEFAULT 0,
    surname VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL
);

INSERT INTO reg (login, email, password, status, surname, name) VALUES
('admin', 'admin@admin.com', 'admin', 1, 'Админ', 'Админов'),
('user', 'user@user.com', 'user', 0, 'Пользователь', 'Тест');


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
