-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 15 2025 г., 15:23
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
  `navigation_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `href` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `parent_id` int DEFAULT NULL,
  `position` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `icon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `target` varchar(20) COLLATE utf8mb4_general_ci DEFAULT '_self',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Navigation`
--

INSERT INTO `Navigation` (`navigation_id`, `title`, `slug`, `href`, `parent_id`, `position`, `is_active`, `icon`, `target`, `created_at`, `updated_at`) VALUES
(1, 'Главная', 'home', '/', NULL, 1, 1, 'home', '_self', '2025-07-13 20:57:35', '2025-07-14 20:50:12'),
(2, 'Автосигнализации', 'autosygnals', '/autosygnals', NULL, 2, 1, 'grid', '_self', '2025-07-13 20:57:35', '2025-07-14 20:35:19'),
(3, 'Автосигнализации', 'autosignals', '/catalog/autosignals', 2, 1, 1, 'shield', '_self', '2025-07-13 20:57:35', '2025-07-13 20:57:35'),
(4, 'Брелоки', 'keychains', '/catalog/keychains', 2, 2, 1, 'key', '_self', '2025-07-13 20:57:35', '2025-07-13 20:57:35'),
(5, 'Модули', 'modules', '/catalog/modules', 2, 3, 1, 'cpu', '_self', '2025-07-13 20:57:35', '2025-07-13 20:57:35'),
(6, 'Видеорегистраторы', 'parking-systems', '/parking-systems?SELECT=name', NULL, 3, 1, 'settings', '_self', '2025-07-13 20:57:35', '2025-07-14 20:35:53'),
(7, 'Установка', 'installation', '/services/installation', 6, 1, 1, 'tool', '_self', '2025-07-13 20:57:35', '2025-07-13 20:57:35'),
(8, 'Диагностика', 'diagnostics', '/services/diagnostics', 6, 2, 1, 'search', '_self', '2025-07-13 20:57:35', '2025-07-13 20:57:35'),
(9, 'Гарантия', 'warranty', '/services/warranty', 6, 3, 1, 'shield-check', '_self', '2025-07-13 20:57:35', '2025-07-13 20:57:35'),
(10, 'О нас', 'about', '/about', NULL, 4, 1, 'info', '_self', '2025-07-13 20:57:35', '2025-07-13 20:57:35'),
(11, 'Наши услуги', 'services', '/services', NULL, 5, 1, 'phone', '_self', '2025-07-13 20:57:35', '2025-07-14 20:36:30'),
(12, 'Контакты', 'contacts', '/contacts', NULL, 6, 1, 'file-text', '_self', '2025-07-13 20:57:35', '2025-07-14 20:39:03'),
(13, 'Сертификаты', 'certificates', '/documents/certificates', 12, 1, 1, 'award', '_self', '2025-07-13 20:57:35', '2025-07-13 20:57:35'),
(14, 'Прайс-листы', 'price-lists', '/documents/price-lists', 12, 2, 1, 'dollar-sign', '_self', '2025-07-13 20:57:35', '2025-07-13 20:57:35');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Navigation`
--
ALTER TABLE `Navigation`
  ADD PRIMARY KEY (`navigation_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `position` (`position`),
  ADD KEY `is_active` (`is_active`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Navigation`
--
ALTER TABLE `Navigation`
  MODIFY `navigation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Navigation`
--
ALTER TABLE `Navigation`
  ADD CONSTRAINT `navigation_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `Navigation` (`navigation_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
