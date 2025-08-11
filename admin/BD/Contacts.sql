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
-- Структура таблицы `Contacts`
--

CREATE TABLE `Contacts` (
  `contact_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_order` int NOT NULL,
  `on_page` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Contacts`
--

INSERT INTO `Contacts` (`contact_id`, `title`, `type`, `content`, `link`, `icon_path`, `sort_order`, `on_page`, `created_at`, `updated_at`) VALUES
(3, 'Адрес:', 'Адрес', '<p>Казахстан, г.Алматы,</p><p> пр.Абая 145/г, бокс №15</p>', 'https://2gis.kz/almaty/geo/70000001027313872', '', 1, 0, '2025-07-21 18:16:35', '2025-08-07 20:36:17'),
(4, 'BEELINE:', 'Контактный телефон', '+770 774 8212', '+7707748212', '/server/uploads/contact/icons/icon-1754067799.svg', 1, 1, '2025-07-21 18:16:35', '2025-08-07 20:22:33'),
(5, 'KCELL:', 'Контактный телефон', '+770 174 8212', '+7701748212', '/server/uploads/contact/icons/icon-1754067806.svg', 2, 1, '2025-07-21 18:16:35', '2025-08-07 20:22:39'),
(6, 'Whatsapp:', 'Мессенджер', '+77077478212', 'https://wa.me/77077478212', '/client/vectors/whatsapp.svg', 1, 1, '2025-07-21 18:16:35', '2025-08-07 20:22:44'),
(7, 'Instagramm:', 'Социальные сети', '+77077478212', 'https://www.instagram.com/autosecurity_kz', '/client/vectors/sprite.svg#instagramm-icon', 2, 1, '2025-07-21 18:16:35', '2025-08-07 20:22:53'),
(8, 'Почта:', 'Электронная почта', 'autosecurity.site@mail.ru', 'autosecurity.site@mail.ru', '/client/vectors/message-icon.svg', 1, 1, '2025-07-21 18:16:35', '2025-08-07 20:22:57'),
(9, 'График работы:', 'Расписание', '<p>Вс. - Чт.: 9:30 - 18:00 </p><p>Пт.: 9:30-15:00 </p><p>Сб.: Выходной<span class=\"ql-cursor\">﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿</span></p>', '', '/server/uploads/contact/icons/icon-1754067789.svg', 1, 1, '2025-07-21 18:16:35', '2025-08-07 20:22:49'),
(10, 'Карта', 'Карта', NULL, 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1453.4679397503296!2d76.8722813!3d43.231804!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3883693b733bff39%3A0x716633e11986b3f8!2sAuto%20Security!5e0!3m2!1sru!2sru!4v1735233649305!5m2!1sru!2sru', NULL, 1, 1, '2025-07-21 18:16:35', '2025-08-07 20:22:32'),
(11, 'Как к нам добраться', 'Как к нам добраться', 'Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, <br> перед речкой поворот направо, заезжаем на территорию СТО. <br> Наш бокс №15.', NULL, NULL, 1, 1, '2025-07-21 18:16:35', '2025-08-07 20:22:30'),
(12, 'Star Line', 'Сайт', 'www.starline-service.kz', 'https://starline-service.kz/', NULL, 1, 1, '2025-07-21 18:16:35', '2025-08-07 20:22:52'),
(83, 'test3', 'Основной телефон', '', '', NULL, 1, 0, '2025-08-08 15:40:23', '2025-08-09 15:48:35'),
(85, '22', 'Основной телефон', '', '', NULL, 2, 1, '2025-08-08 17:55:26', '2025-08-09 15:48:30');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Contacts`
--
ALTER TABLE `Contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `idx_contact_type` (`type`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Contacts`
--
ALTER TABLE `Contacts`
  MODIFY `contact_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
