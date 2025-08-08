-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 25 2025 г., 17:32
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
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Contacts`
--

INSERT INTO `Contacts` (`contact_id`, `type`, `title`, `content`, `link`, `icon_path`, `created_at`, `updated_at`, `order`) VALUES
(1, 'Основной телефон', 'Основной', '+7 707 747 8212', '+77077478212', '/server/uploads/contact/icons/icon-1753285392.svg', '2025-07-21 18:16:35', '2025-07-25 07:07:01', 3),
(3, 'Адрес', 'Адрес:', '<p>Казахстан, г.Алматы,</p><p><br></p><p> пр.Абая 145/г, бокс №15</p>', 'https://2gis.kz/almaty/geo/70000001027313872', '/server/uploads/contact/icons/icon-1753287049.svg', '2025-07-21 18:16:35', '2025-07-25 07:04:59', 2),
(4, 'Контактный телефон', 'BEELINE:', '+770 774 8212', '+7707748212', '/client/vectors/phone-no-border.svg', '2025-07-21 18:16:35', '2025-07-24 13:53:55', 1),
(5, 'Контактный телефон', 'KCELL:', '+770 174 8212', '+7701748212', '/client/vectors/phone-no-border.svg', '2025-07-21 18:16:35', '2025-07-24 13:53:56', 2),
(6, 'Социальные сети', 'Whatsapp:', '+77077478212', 'https://wa.me/77077478212', '/client/vectors/whatsapp.svg', '2025-07-21 18:16:35', '2025-07-24 13:54:06', 1),
(7, 'Социальные сети', 'Instagramm:', '+77077478212', 'https://www.instagram.com/autosecurity_kz', '/client/vectors/sprite.svg#instagramm-icon', '2025-07-21 18:16:35', '2025-07-24 13:54:10', 2),
(8, 'Электронная почта', 'Почта:', 'autosecurity.site@mail.ru', 'autosecurity.site@mail.ru', '/client/vectors/message-icon.svg', '2025-07-21 18:16:35', '2025-07-24 13:54:15', 1),
(9, 'Расписание', 'График работы:', '<p>Вс. - Чт.: 9:30 - 18:00 </p><p><br></p><p> Пт.: 9:30-15:00 </p><p><br></p><p> </p><p>Сб.: Выходной</p>', '', '/client/vectors/clock.svg', '2025-07-21 18:16:35', '2025-07-24 14:28:07', 1),
(10, 'Карта', 'Карта', NULL, 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1453.4679397503296!2d76.8722813!3d43.231804!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3883693b733bff39%3A0x716633e11986b3f8!2sAuto%20Security!5e0!3m2!1sru!2sru!4v1735233649305!5m2!1sru!2sru', NULL, '2025-07-21 18:16:35', '2025-07-24 13:54:21', 1),
(11, 'Как к нам добраться', 'КАК К НАМ ДОБРАТЬСЯ', 'Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, <br> перед речкой поворот направо, заезжаем на территорию СТО. <br> Наш бокс №15.', NULL, NULL, '2025-07-21 18:16:35', '2025-07-24 13:54:30', 1),
(12, 'Сайт', 'Сайт:', 'www.starline-service.kz', 'https://starline-service.kz/', NULL, '2025-07-21 18:16:35', '2025-07-24 13:54:33', 1),
(58, 'Основной телефон', '8', '', '', NULL, '2025-07-24 14:39:00', '2025-07-25 07:07:01', 2),
(59, 'Адрес', '2', '', '', NULL, '2025-07-25 07:04:36', '2025-07-25 07:04:59', 1),
(61, 'Основной телефон', 'za', '', '', NULL, '2025-07-25 07:06:42', '2025-07-25 07:07:01', 1);

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
  MODIFY `contact_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
