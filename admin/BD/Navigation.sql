-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 01 2025 г., 17:06
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
  `link` varchar(255) NOT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `isActive` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Navigation`
--

INSERT INTO `Navigation` (`id`, `title`, `link`, `icon_path`, `sort_order`, `isActive`, `created_at`, `updated_at`) VALUES
(12, 'Автосигнализации', '/autosygnals', '/server/uploads/navigation/icons/icon-1753975011.svg', 2, 1, '2025-07-31 15:16:51', '2025-08-01 13:54:38'),
(13, 'Главная', '/', '/server/uploads/navigation/icons/icon-1754055507.svg', 1, 1, '2025-08-01 13:38:27', '2025-08-01 13:54:38'),
(14, 'Услуги', '/services', NULL, 3, 1, '2025-08-01 13:39:30', '2025-08-01 13:40:13');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
