-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 17 2025 г., 19:27
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
-- Структура таблицы `Videos_intro_slider`
--

CREATE TABLE `Videos_intro_slider` (
  `id` int NOT NULL,
  `video_filename` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `video_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `advantages` json NOT NULL,
  `button_text` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `button_link` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `position` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `poster_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `video_path_mob` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Videos_intro_slider`
--

INSERT INTO `Videos_intro_slider` (`id`, `video_filename`, `video_path`, `title`, `advantages`, `button_text`, `button_link`, `position`, `created_at`, `updated_at`, `poster_path`, `video_path_mob`) VALUES
(24, 'video-1752760786.mp4', '/server/uploads/slider-intro/video-1752760786.mp4', 'Новый слайд', '[]', 'Подробнее', '#', 2, '2025-07-17 10:31:15', '2025-07-17 15:56:11', '/server/uploads/slider-intro/posters/poster-1752767771.avif', ''),
(32, 'video-1752761095.mp4', '/server/uploads/slider-intro/video-1752761095.mp4', '222', '[]', 'Подробнее', '#', 1, '2025-07-17 13:56:12', '2025-07-17 15:44:45', '/server/uploads/slider-intro/posters/poster-1752767085.avif', ''),
(33, 'video-1752768367.mp4', '/server/uploads/slider-intro/video-1752768367.mp4', 'Новый слай22д', '[]', 'Подробнее', '#', 3, '2025-07-17 16:01:57', '2025-07-17 16:08:20', '', '/server/uploads/slider-intro/video-1752768367_mob.webm'),
(34, 'video-1752768668.mp4', '/server/uploads/slider-intro/video-1752768668.mp4', 'Новый слайд', '[]', 'Подробнее', '#', 4, '2025-07-17 16:11:00', '2025-07-17 16:11:39', '', '/server/uploads/slider-intro/video-1752768668_mob.webm');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Videos_intro_slider`
--
ALTER TABLE `Videos_intro_slider`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Videos_intro_slider`
--
ALTER TABLE `Videos_intro_slider`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
