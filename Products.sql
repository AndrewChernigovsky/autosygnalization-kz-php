-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 08 2025 г., 17:18
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
-- Структура таблицы `Products`
--

CREATE TABLE `Products` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` int NOT NULL,
  `price_list` text COLLATE utf8mb4_general_ci,
  `currency` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_popular` tinyint(1) DEFAULT '0',
  `is_special` tinyint(1) DEFAULT '0',
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `functions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `options_filters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `autosygnals` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Дамп данных таблицы `Products`
--

INSERT INTO `Products` (`id`, `category`, `model`, `title`, `description`, `price`, `price_list`, `currency`, `quantity`, `link`, `is_popular`, `is_special`, `gallery`, `functions`, `options`, `options_filters`, `autosygnals`, `created_at`, `updated_at`) VALUES
('product_keychain_a60', 'keychain', 'a60', 'Starline A60 Eco', 'Умный охранно-телематический комплекс StarLine А60 включает лучшие решения в области автобезопасности и гарантирует надежную защиту от угона благодаря устойчивому к электронному взлому диалоговому коду управления и широким возможностям для авторских блокировок двигателя. А гибкие настройки сервисных функций подарят вашему автомобилю уровень комфорта премиум-класса', 43700, '[{\"title\":\"\",\"price\":\"\",\"currency\":\"\",\"content\":\"\"}]', '₸', 1, '/product?category=keychain&id=product_keychain_a60', 1, 0, '[\"https:\\/\\/starline-service.kz\\/client\\/images\\/products\\/starline_a60\\/package_Starline-A60-Eco.avif\",\"https:\\/\\/starline-service.kz\\/client\\/images\\/products\\/starline_a60\\/Starline-A60-Eco-3.avif\",\"https:\\/\\/starline-service.kz\\/client\\/images\\/products\\/starline_a60\\/Starline-A60-Eco-2.avif\",\"https:\\/\\/starline-service.kz\\/client\\/images\\/products\\/starline_a60\\/Starline-A60-Eco-1.avif\"]', '[\"\\u0410\\u0432\\u0442\\u043e\\u0437\\u0430\\u043f\\u0443\\u0441\\u043a\"]', '[\"\\u0414\\u043b\\u044f \\u043b\\u0435\\u0433\\u043a\\u043e\\u0432\\u043e\\u0433\\u043e \\u0430\\u0432\\u0442\\u043e\",\"\\u0414\\u043b\\u044f \\u0432\\u043d\\u0435\\u0434\\u043e\\u0440\\u043e\\u0436\\u043d\\u0438\\u043a\\u0430\"]', '[\"legkoe-avto\",\"vnedorojnik\",\"autosetup\",\"block-engine-can\"]', '[\"without-auto\",\"auto\",\"starline\"]', '2025-07-18 09:55:05', '2025-10-02 09:49:01');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
