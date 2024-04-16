-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 16 2024 г., 23:17
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `event_platform`
--

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `price` int NOT NULL,
  `number_seats` int NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `event_name`, `price`, `number_seats`, `date`) VALUES
(1, 'Волейбол', 100, 3, '2024-04-25'),
(2, 'Футбол', 100, 100, '2024-04-22'),
(3, 'Баскетбол', 100, 150, '2024-04-23'),
(5, 'Теннис', 70, 50, '2024-04-25'),
(6, 'Гольф', 90, 50, '2024-04-28'),
(9, 'Плавание', 120, 30, '2024-04-18'),
(10, 'Скалолазание', 70, 70, '2024-04-19'),
(13, 'Регби', 70, 50, '2024-04-25');

-- --------------------------------------------------------

--
-- Структура таблицы `event_records`
--

CREATE TABLE `event_records` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `event_records`
--

INSERT INTO `event_records` (`id`, `user_id`, `event_id`) VALUES
(5, 1, 6),
(1, 5, 1),
(2, 5, 2),
(6, 5, 3),
(22, 5, 6),
(23, 13, 5),
(24, 13, 9),
(27, 13, 10),
(26, 15, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'user'),
(2, 'manager');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `role_id`, `password`) VALUES
(1, 'Aliona', 'Yusyumbeli', 'yusyumbeli03@mail.ru', 2, '$2y$10$jZkcXxsNG1KUUt1.lneL9ud1HJDM3jofuF8QIVpSXHDhODePT14te'),
(5, 'Tanea', 'Franzheva', 'tanechka02@mail.ru', 1, '$2y$10$N7ooDMU/h/JSPFoTFuDoh.nMT6.cDjzp7GqjhPeRkyWafExJFGewe'),
(9, 'Nicolay', 'Calin', 'kalin0203@gmail.com', 1, '$2y$10$H5Scc4KH6FXMUycFeOIq2ewdRgGiLNsTxctCtT02D0ICxh97f02S6'),
(10, 'Anna', 'Gasparean', 'gasparean@mail.ru', 1, '$2y$10$1huMd/tuKGKfbCnVg8D3kuarD9PJyQnJfEOz.dvCJT4rJ2NV7Czo.'),
(12, 'Svetlana', 'Kapsamun', 'svetka2004@mail.ru', 1, '$2y$10$hlEgkUTKL4EtTMtiFvWdsuwfEZoM2.Y4tTX6cVsH99g4/YNNYrY2.'),
(13, 'Anastasia', 'Petrova', 'nastea@mail.ru', 1, '$2y$10$rQWmu/cEacj9Q8TIjH/5O.a1P.d57heu4hEwOsdCP00Y0SU.6ZtEe'),
(14, 'Petya', 'Petrov', 'petrov@gmail.ru', 1, '$2y$10$TWbvhvDoFq2bcfbz3vMhn.L3m2o8pQSZONjESfHwrgcBrectiaOAi'),
(15, 'Anya', 'Koleva', 'anya2002@mail.ru', 1, '$2y$10$nezKxLo0c9EfuB1uzxZQA.oGerslcV7VQ5B5UhpKt6o40X2RXDFUC');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `event_records`
--
ALTER TABLE `event_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_event` (`user_id`,`event_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `event_records`
--
ALTER TABLE `event_records`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `event_records`
--
ALTER TABLE `event_records`
  ADD CONSTRAINT `event_records_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `event_records_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
