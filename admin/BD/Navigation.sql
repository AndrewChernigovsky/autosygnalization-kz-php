-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 09 2025 г., 18:49
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
(12, 'Автосигнализации', '', '/autosygnals', '/server/uploads/navigation/icons/icon-1754067701.svg', 2, 1, '2025-07-31 15:16:51', '2025-08-09 15:43:21'),
(13, 'Главная2', '', '/', '/server/uploads/navigation/icons/icon-1754055507.svg', 1, 0, '2025-08-01 13:38:27', '2025-08-09 15:48:49'),
(14, 'Услуги', '', '/services', '/server/uploads/navigation/icons/icon-1754067710.svg', 3, 1, '2025-08-01 13:39:30', '2025-08-01 17:01:50'),
(15, 'Zalupa', 'Zalupivka', '/', '/server/uploads/navigation/icons/icon-1754752917.svg', 4, 1, '2025-08-09 15:21:57', '2025-08-09 15:21:57'),
(16, 'ASD', '', 'ASD', '/server/uploads/navigation/icons/icon-1754753539.svg', 5, 1, '2025-08-09 15:32:19', '2025-08-09 15:32:19'),
(17, 'ASD', 'ddd', '/services', NULL, 6, 1, '2025-08-09 15:32:34', '2025-08-09 15:32:34'),
(18, 'test', '', '/', NULL, 7, 1, '2025-08-09 15:33:53', '2025-08-09 15:33:53'),
(19, 'test2', '', '/sertificates', NULL, 8, 1, '2025-08-09 15:34:07', '2025-08-09 15:34:07'),
(20, 'test 3', '', '/autosygnals', NULL, 9, 0, '2025-08-09 15:37:09', '2025-08-09 15:37:09');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
