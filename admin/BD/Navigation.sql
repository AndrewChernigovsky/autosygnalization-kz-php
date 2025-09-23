-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 09 2025 г., 19:18
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `autosygnals`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Navigation`
--

CREATE TABLE `Navigation` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `on_page` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Navigation`
--

INSERT INTO `Navigation` (`id`, `title`, `content`, `link`, `icon_path`, `sort_order`, `on_page`, `created_at`, `updated_at`) VALUES
(12, 'Автосигнализации', 'Автосигнализации', '/autosygnals', '/server/uploads/navigation/icons/icon-1754067701.svg', 2, 1, '2025-07-31 15:16:51', '2025-08-09 16:17:04'),
(13, 'Главная2', '', '/', '/server/uploads/navigation/icons/icon-1754055507.svg', 1, 1, '2025-08-01 13:38:27', '2025-08-09 16:18:36'),
(14, 'Услуги', 'Услуги', '/services', '/server/uploads/navigation/icons/icon-1754067710.svg', 3, 1, '2025-08-01 13:39:30', '2025-08-09 16:17:10'),
(15, 'Zalupa', 'Zalupivka', '/', '/server/uploads/navigation/icons/icon-1754752917.svg', 4, 1, '2025-08-09 15:21:57', '2025-08-09 15:21:57'),
(21, 'Zapravka', 'Контакты', '/contacts', NULL, 6, 1, '2025-08-09 16:14:52', '2025-08-09 16:14:52');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Navigation`
--
ALTER TABLE `Navigation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Navigation`
--
ALTER TABLE `Navigation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
