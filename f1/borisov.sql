-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 06 2025 г., 05:49
-- Версия сервера: 5.6.51
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `borisov`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Стул'),
(2, 'Стол'),
(3, 'Шкаф');

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `product_id`, `user_id`, `comment_text`, `created_at`) VALUES
(26, 6, 7, '<script>alert(123)</script>', '2025-11-06 01:22:59'),
(27, 6, 7, '<script>alert(123)</script>', '2025-11-06 01:23:07'),
(28, 6, 7, '<script>alert(123)</script>', '2025-11-06 02:07:33'),
(29, 6, 7, '<script>alert(123)</script>', '2025-11-06 02:07:42');

-- --------------------------------------------------------

--
-- Структура таблицы `reg`
--

CREATE TABLE `reg` (
  `id` int(11) NOT NULL,
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `reg`
--

INSERT INTO `reg` (`id`, `login`, `email`, `surname`, `name`, `password`, `status`) VALUES
(1, '1@1mail.ru', '1@1mail.ru', '1@1mail.ru', '1@1mail.ru', '1@1mail.ru', 1),
(2, '2@1mail.ru', '2@1mail.ru', '2', '1', '2@1mail.ru', 0),
(3, '2@1mail.ru', '2@1mail.ru', '2@1mail.ru', '2@1mail.ru', '2@1mail.ru', 0),
(4, 'robert@mail.ru', 'robert@mail.ru', 'robert@mail.ru', 'robert@mail.ru', 'robert@mail.ru', 0),
(5, 'robert2@mail.ru', 'robert2@mail.ru', 'robert2@mail.ru', 'robert2@mail.ru', 'robert2@mail.ru', 0),
(6, '213@mail.ru', '213@mail.ru', '213@mail.ru', '213@mail.ru', '213@mail.ru', 0),
(7, '1231@gmail.com', '1231@gmail.com', '1231@gmail.com', '1231@gmail.com', '1231@gmail.com', 0),
(8, 'SELECT * FROM Users WHERE UserId = 105 OR 1=1;', '45345@mail.ru', 'SELECT * FROM Users WHERE UserId = 105 OR 1=1;', 'SELECT * FROM Users WHERE UserId = 105 OR 1=1;', 'SELECT * FROM Users WHERE UserId = 105 OR 1=1;', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tovar`
--

CREATE TABLE `tovar` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `smalldesc` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bigdesc` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tovar`
--

INSERT INTO `tovar` (`id`, `name`, `category`, `smalldesc`, `bigdesc`, `price`) VALUES
(1, 'Шкаф 1-но дверный без зеркала Венеция 9', 'Шкаф', 'Материал: ЛДСП Размер (ДхВхШ): 400х2100х560 мм Цвет: \r\nЯсень Анкор темный/Ясень Анкор светлый', 'Материал: ЛДСП Размер (ДхВхШ): 400х2100х560 мм Цвет: \r\nЯсень Анкор темный/Ясень Анкор светлый', '13 860 руб'),
(2, 'Обеденный стол 6777-1 Дуб Антик Белый', 'Стол', 'Стол произведен из массива гевеи МДФ, шпон.', 'Вставка: Бабочка 33 см. Материал: Массив гевеи, МДФ, Шпон Размер: Длина 120 см. Ширина 80 см. Вставка 33 см. Высота 75 см. Производитель: Малайзия Цвет: Дуб Антик Белый', '23 820 руб'),
(3, 'Стул венский Венеция-Кантри', 'Стул', 'Материал: береза', 'Материал: береза Размеры: Ширина - 440 мм Глубина - 480 мм Высота - 770 мм Вес - 2.5 кг', '5 600 руб'),
(4, 'Антресоль к шкафу Норд 1200 (Миф)', 'Шкаф', 'Ширина: 1200 мм Высота: 418 мм Глубина: 510 мм', 'Ширина: 1200 мм Высота: 418 мм Глубина: 510 мм Материал корпуса: ЛДСП Материал фасада: ЛДСП Максимальная нагрузка: 15 кг Способ открывания: Push-to-open', '4 344 руб'),
(5, 'Стул MEXICA-CHAISE (Pin Magic)', 'Стул', 'Ширина: 450 мм Высота: 1000 мм Глубина: 450 мм', 'Ширина: 450 мм Высота: 1000 мм Глубина: 450 мм Материал: Массив сосны, покрытый маслом/лаком Цвет: Коньяк (масло)/Черный (лак) Максимальная нагрузка: 120 кг', '9 821 руб'),
(6, 'Письменный стол Iris (Woodville)', 'Стол', 'Ширина: 1200 мм Высота: 785 мм Глубина: 556 мм', 'Толщина столешницы: 16 мм Материал корпуса: ЛДСП Материал фасада: ЛДСП Материал опор: Металл Цвет корпуса: Дуб вотан Цвет фасада: Дуб вотан Цвет опор: Черный муар Направляющие: Шариковые Тип сборки: Универсальный', '30 964 руб');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tovar_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `reg`
--
ALTER TABLE `reg`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `reg`
--
ALTER TABLE `reg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
