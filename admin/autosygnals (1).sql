-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 03 2025 г., 03:35
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";







--
-- База данных: `autosygnals`
--

-- --------------------------------------------------------

--
-- Структура таблицы `AboutUs`
--

DROP TABLE IF EXISTS `AboutUs`;
CREATE TABLE `AboutUs` (
  `about_us_id` int NOT NULL,
  `type` varchar(255) NOT NULL COMMENT 'Тип контента (напр., slogan, advantage, comment)',
  `title` varchar(255) DEFAULT NULL COMMENT 'Заголовок для элемента, если есть',
  `content` text COMMENT 'Текстовое содержимое',
  `image_path` varchar(255) DEFAULT NULL COMMENT 'Путь к изображению',
  `position` int DEFAULT NULL COMMENT 'Порядок сортировки внутри группы одного типа',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `AboutUs`
--

INSERT INTO `AboutUs` (`about_us_id`, `type`, `title`, `content`, `image_path`, `position`, `created_at`, `updated_at`) VALUES
(1, 'present-slogan-block', NULL, '<p>“Auto Security” – магазин и установочный центр автоэлектроники.</p><p>Мы предлагаем лучшее!</p>', NULL, 1, '2025-07-23 15:44:18', '2025-07-23 15:44:18'),
(2, 'present-text-block', NULL, '<p>Наша компания была основана в 2004 году, в самый расцвет автосервисов.</p><p>Миссия нашей компании – осуществлять качественные услуги в сфере продаж, установки и ремонта автоэлектроники.</p>', NULL, 1, '2025-07-23 15:44:18', '2025-07-23 15:44:18'),
(3, 'advantages-list', NULL, '<ul><li>Наши мастера имеют богатый опыт по инсталляции разнообразного электронного оборудования на различные автомобили.</li><li>Мы постоянно повышаем свою квалификацию, участвуем в конференциях.</li><li>Аккуратность и ответственность – именно это сегодня является важными отличиями команды \"Auto Security\".</li><li>Наш сервис укомплектован с овременным диагностическим оборудованием, позволяющим нам корректно работать с абсолютно новыми автомобилями.</li><li>Нашим клиентам мы предлагаем услугу выезда для экономии времени и наибольшего комфорта.</li></ul>', NULL, 1, '2025-07-23 15:44:18', '2025-07-23 15:44:18'),
(4, 'comment-block', NULL, '<p>Дружная команда опытных установщиков с удовольствием воплотит ваши мечты в реальность!</p><p>Обращайтесь к нам, будем рады Вам помочь!</p>', NULL, 1, '2025-07-23 15:44:18', '2025-07-23 15:44:18'),
(5, 'appeal-text-block', NULL, '<p>Вам будет оказана квалифицированная помощь по установке дополнительного электронного оборудования на Ваш автомобиль!</p><p>Мы продиагностируем Ваш авто, отремонтируем, установим, настроим Ваше оборудование! Доверяйте профессионалам!</p>', NULL, 1, '2025-07-23 15:44:18', '2025-07-23 15:44:18'),
(7, 'tech-photo-image', NULL, NULL, '/server/uploads/about_us/images/about_68810ccfc508d5.95163566.svg', 2, '2025-07-23 15:44:18', '2025-07-23 16:24:47'),
(9, 'tech-photo-image', NULL, NULL, '/server/uploads/about_us/images/about_68810cd57ba198.96017597.png', 3, '2025-07-23 15:51:54', '2025-07-23 16:24:53');

-- --------------------------------------------------------

--
-- Структура таблицы `add_services`
--

DROP TABLE IF EXISTS `add_services`;
CREATE TABLE `add_services` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL
);

--
-- Дамп данных таблицы `add_services`
--

INSERT INTO `add_services` (`id`, `title`, `price`) VALUES
(1, 'Новая дополнительная услуга3333', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `admin_id` int NOT NULL,
  `user_id` int NOT NULL,
  `admin_level` int DEFAULT '1',
  `permissions` text,
  `last_login` timestamp NULL DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Структура таблицы `Advantage`
--

DROP TABLE IF EXISTS `Advantage`;
CREATE TABLE `Advantage` (
  `advantage_id` int NOT NULL,
  `content` text COMMENT 'Текстовое содержимое',
  `image_path` varchar(255) DEFAULT NULL COMMENT 'Путь к изображению',
  `position` int DEFAULT NULL COMMENT 'Порядок сортировки',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `Advantage`
--

INSERT INTO `Advantage` (`advantage_id`, `content`, `image_path`, `position`, `created_at`, `updated_at`) VALUES
(1, 'ДОСТУПНАЯ СТОИМОСТЬ ПРОДУКЦИИ И УСЛУГ', '/client/vectors/economy-1.svg', 1, '2025-07-24 14:01:44', '2025-07-25 11:02:08'),
(2, 'ПРЕДОСТАВЛЯЕМ КЛИЕНТАМ НАИЛУЧШЕЕ КАЧЕСТВО ТОВАРОВ И СЕРВИСА', '/client/vectors/economy-2.svg', 4, '2025-07-24 14:01:44', '2025-07-25 11:01:47'),
(3, 'ИНДИВИДУАЛЬНЫЙ ПОДХОД К КАЖДОМУ КЛИЕНТУ', '/client/vectors/economy-3.svg', 2, '2025-07-24 14:01:44', '2025-07-25 11:02:08'),
(4, 'Большой опыт работы', '/client/vectors/economy-4.svg', 3, '2025-07-24 14:01:44', '2025-07-25 11:01:47'),
(5, 'Гарантия на все товары/услуги', '/client/vectors/economy-5.svg', 5, '2025-07-24 14:01:44', '2025-07-24 14:01:44');

-- --------------------------------------------------------

--
-- Структура таблицы `AdvantageVideos`
--

DROP TABLE IF EXISTS `AdvantageVideos`;
CREATE TABLE `AdvantageVideos` (
  `video_id` int NOT NULL,
  `title` text COMMENT 'Текстовое содержимое',
  `title_icon` varchar(255) DEFAULT NULL COMMENT 'Путь к изображению заголовка',
  `video_poster` varchar(255) DEFAULT NULL COMMENT 'Путь к изображению заголовка для мобильной версии',
  `video_src_mob` varchar(255) DEFAULT NULL COMMENT 'Путь к видео для мобильной версии',
  `position` int DEFAULT NULL COMMENT 'Порядок сортировки',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `AdvantageVideos`
--

INSERT INTO `AdvantageVideos` (`video_id`, `title`, `title_icon`, `video_poster`, `video_src_mob`, `position`, `created_at`, `updated_at`) VALUES
(1, 'Auto Security', '/server/uploads/advantage-video/icon_6883adf1127ea.svg', '/server/uploads/advantage-video/video_68855dba286a5.avif', '/server/uploads/advantage-video/video_68855dba286a5_mob.webm', 1, '2025-07-24 14:00:58', '2025-07-26 23:03:16');

-- --------------------------------------------------------

--
-- Структура таблицы `AdvantageVideoSources`
--

DROP TABLE IF EXISTS `AdvantageVideoSources`;
CREATE TABLE `AdvantageVideoSources` (
  `source_id` int NOT NULL,
  `video_id` int NOT NULL,
  `src_path` varchar(255) NOT NULL COMMENT 'Путь к видео-файлу',
  `src_type` varchar(50) NOT NULL COMMENT 'MIME-тип видео, например, video/webm'
);

--
-- Дамп данных таблицы `AdvantageVideoSources`
--

INSERT INTO `AdvantageVideoSources` (`source_id`, `video_id`, `src_path`, `src_type`) VALUES
(25, 1, '/server/uploads/advantage-video/video_68855dba286a5_desktop.webm', 'video/webm'),
(26, 1, '/server/uploads/advantage-video/video_68855dba286a5_desktop.mp4', 'video/mp4');

-- --------------------------------------------------------

--
-- Структура таблицы `Contacts`
--

DROP TABLE IF EXISTS `Contacts`;
CREATE TABLE `Contacts` (
  `contact_id` int NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `order` int NOT NULL
);

--
-- Дамп данных таблицы `Contacts`
--

INSERT INTO `Contacts` (`contact_id`, `type`, `title`, `content`, `link`, `icon_path`, `created_at`, `updated_at`, `order`) VALUES
(1, 'Основной телефон', 'Основной', '+7 707 747 8212', '+77077478212', '/server/uploads/contact/icons/icon-1753285392.svg', '2025-07-21 18:16:35', '2025-07-25 17:01:55', 2),
(3, 'Адрес', 'Адрес:', '<p>Казахстан, г.Алматы,</p><p><br></p><p> пр.Абая 145/г, бокс №15</p>', 'https://2gis.kz/almaty/geo/70000001027313872', '/server/uploads/contact/icons/icon-1753287049.svg', '2025-07-21 18:16:35', '2025-07-25 16:51:16', 2),
(4, 'Контактный телефон', 'BEELINE:', '+770 774 8212', '+7707748212', '/client/vectors/phone-no-border.svg', '2025-07-21 18:16:35', '2025-07-24 13:53:55', 1),
(5, 'Контактный телефон', 'KCELL:', '+770 174 8212', '+7701748212', '/client/vectors/phone-no-border.svg', '2025-07-21 18:16:35', '2025-07-24 13:53:56', 2),
(6, 'Социальные сети', 'Whatsapp:', '+77077478212', 'https://wa.me/77077478212', '/client/vectors/whatsapp.svg', '2025-07-21 18:16:35', '2025-07-24 13:54:06', 1),
(7, 'Социальные сети', 'Instagramm:', '+77077478212', 'https://www.instagram.com/autosecurity_kz', '/client/vectors/sprite.svg#instagramm-icon', '2025-07-21 18:16:35', '2025-07-24 13:54:10', 2),
(8, 'Электронная почта', 'Почта:', 'autosecurity.site@mail.ru', 'autosecurity.site@mail.ru', '/client/vectors/message-icon.svg', '2025-07-21 18:16:35', '2025-07-24 13:54:15', 1),
(9, 'Расписание', 'График работы:', '<p>Вс. - Чт.: 9:30 - 18:00 </p><p><br></p><p> Пт.: 9:30-15:00 </p><p><br></p><p> </p><p>Сб.: Выходной</p>', '', '/client/vectors/clock.svg', '2025-07-21 18:16:35', '2025-07-24 14:28:07', 1),
(10, 'Карта', 'Карта', NULL, 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1453.4679397503296!2d76.8722813!3d43.231804!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3883693b733bff39%3A0x716633e11986b3f8!2sAuto%20Security!5e0!3m2!1sru!2sru!4v1735233649305!5m2!1sru!2sru', NULL, '2025-07-21 18:16:35', '2025-07-24 13:54:21', 1),
(11, 'Как к нам добраться', 'КАК К НАМ ДОБРАТЬСЯ', 'Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, <br> перед речкой поворот направо, заезжаем на территорию СТО. <br> Наш бокс №15.', NULL, NULL, '2025-07-21 18:16:35', '2025-07-24 13:54:30', 1),
(12, 'Сайт', 'Сайт:', 'www.starline-service.kz', 'https://starline-service.kz/', NULL, '2025-07-21 18:16:35', '2025-07-24 13:54:33', 1),
(62, 'Основной телефон', 'Основной', '+7 707 747 8212', '+77077478212', '/server/uploads/contact/icons/icon-1753285392.svg', '2025-07-21 18:16:35', '2025-08-01 13:05:04', 1),
(63, 'Адрес', '', '', '', NULL, '2025-08-02 13:44:50', '2025-08-02 13:44:50', 3),
(64, 'Основной телефон', '', '', '', NULL, '2025-08-02 14:19:23', '2025-08-02 14:19:23', 3),
(65, 'Контактный телефон', '', '', '', NULL, '2025-08-02 14:22:59', '2025-08-02 14:22:59', 3);

--
-- Триггеры `Contacts`
--
DELIMITER $$
CREATE TRIGGER `after_contacts_delete` AFTER DELETE ON `Contacts` FOR EACH ROW BEGIN
  DELETE FROM `LinksData`
  WHERE source_table = 'Contacts' AND source_id = OLD.contact_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_contacts_delete_to_linksdata` AFTER DELETE ON `Contacts` FOR EACH ROW BEGIN
  DELETE FROM `LinksData`
  WHERE source_table = 'Contacts' AND source_id = OLD.contact_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_contacts_insert` AFTER INSERT ON `Contacts` FOR EACH ROW BEGIN
  IF NEW.link IS NOT NULL AND NEW.link != '' THEN
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.title, NEW.link, 'Contacts', NEW.contact_id)
    ON DUPLICATE KEY UPDATE name=NEW.title, link=NEW.link;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_contacts_insert_to_linksdata` AFTER INSERT ON `Contacts` FOR EACH ROW BEGIN
  IF NEW.link IS NOT NULL AND NEW.link != '' THEN
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.title, NEW.link, 'Contacts', NEW.contact_id)
    ON DUPLICATE KEY UPDATE name=NEW.title, link=NEW.link;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_contacts_update` AFTER UPDATE ON `Contacts` FOR EACH ROW BEGIN
  IF NEW.link IS NULL OR NEW.link = '' THEN
    DELETE FROM `LinksData`
    WHERE source_table = 'Contacts' AND source_id = OLD.contact_id;
  ELSE
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.title, NEW.link, 'Contacts', NEW.contact_id)
    ON DUPLICATE KEY UPDATE name = VALUES(name), link = VALUES(link);
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_contacts_update_to_linksdata` AFTER UPDATE ON `Contacts` FOR EACH ROW BEGIN
  IF NEW.link IS NULL OR NEW.link = '' THEN
    DELETE FROM `LinksData`
    WHERE source_table = 'Contacts' AND source_id = OLD.contact_id;
  ELSE
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.title, NEW.link, 'Contacts', NEW.contact_id)
    ON DUPLICATE KEY UPDATE name = VALUES(name), link = VALUES(link);
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `Footer`
--

DROP TABLE IF EXISTS `Footer`;
CREATE TABLE `Footer` (
  `footer_id` int NOT NULL,
  `section` enum('shop','installation','client') NOT NULL COMMENT 'Раздел футера (Магазин, Установка, Клиенту)',
  `name` varchar(255) NOT NULL COMMENT 'Название ссылки',
  `link` varchar(255) NOT NULL COMMENT 'URL ссылки',
  `position` int DEFAULT NULL COMMENT 'Порядок сортировки',
  `visible` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Видимость ссылки (1 - видна, 0 - скрыта)',
  `source_table` enum('LinksData','custom') DEFAULT NULL COMMENT 'Источник данных (для синхронизации)',
  `source_id` int DEFAULT NULL COMMENT 'ID из исходной таблицы (links_data_id или null для custom)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `Footer`
--

INSERT INTO `Footer` (`footer_id`, `section`, `name`, `link`, `position`, `visible`, `source_table`, `source_id`, `created_at`, `updated_at`) VALUES
(2, 'installation', 'Прайс на услуги1', '/price', 99, 1, 'custom', NULL, '2025-07-31 17:05:39', '2025-08-01 16:48:33'),
(3, 'client', 'Специальные предложения', '/special', 2, 1, 'custom', NULL, '2025-07-31 17:05:39', '2025-08-01 17:28:23'),
(4, 'client', 'Корзина заказа', '/cart', 3, 1, 'custom', NULL, '2025-07-31 17:05:39', '2025-08-01 17:28:23'),
(5, 'client', 'Оставить отзыв', 'https://2gis.kz/almaty/geo/70000001027313872', 4, 1, 'custom', NULL, '2025-07-31 17:05:39', '2025-08-01 17:28:23'),
(6, 'client', 'Архив выполненных работ', 'https://drive.google.com/drive/folders/1gRjuirVES2pO6EMTNDrL5KNGC4RfBRPb', 5, 1, 'custom', NULL, '2025-07-31 17:05:39', '2025-08-01 17:28:21'),
(7, 'client', 'Как к нам добраться', '/contacts#location', 1, 1, 'custom', NULL, '2025-07-31 17:05:39', '2025-08-01 17:28:23'),
(8, 'client', 'Наши сертификаты', '/sertificates', 6, 1, 'custom', NULL, '2025-07-31 17:05:39', '2025-07-31 17:05:39'),
(9, 'client', 'Оплата и доставка', '#', 7, 1, 'custom', NULL, '2025-07-31 17:05:39', '2025-07-31 17:05:39'),
(11, 'shop', 'РУСИФИКАЦИЯ АВТО И ЧИПТЮНИНГ', '/service?service=rus', 2, 1, 'custom', 12, '2025-08-01 17:09:51', '2025-08-01 17:27:32'),
(12, 'shop', 'ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ', '/service?service=disabled-autosynal', 3, 1, 'custom', 10, '2025-08-01 17:20:58', '2025-08-01 17:27:31'),
(13, 'shop', 'УСТАНОВКА АВТОЗВУКА И МУЛЬТИМЕДИА', '/service?service=setup-media', 99, 1, 'custom', 14, '2025-08-02 13:31:14', '2025-08-02 13:31:14');

-- --------------------------------------------------------

--
-- Структура таблицы `LinksData`
--

DROP TABLE IF EXISTS `LinksData`;
CREATE TABLE `LinksData` (
  `links_data_id` int NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Название ссылки',
  `link` varchar(255) NOT NULL COMMENT 'URL ссылки',
  `source_table` enum('Sections-Product','Services','Contacts') DEFAULT NULL COMMENT 'Источник данных (для синхронизации)',
  `source_id` int DEFAULT NULL COMMENT 'ID из исходной таблицы (для синхронизации)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `LinksData`
--

INSERT INTO `LinksData` (`links_data_id`, `name`, `link`, `source_table`, `source_id`, `created_at`, `updated_at`) VALUES
(1, 'Автосигнализации с автозапуском', '/autosygnal?SELECT=name&type=auto', 'Sections-Product', 1, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(2, 'Автосигнализации с GSM', '/autosygnal?SELECT=name&type=gsm', 'Sections-Product', 2, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(3, 'Автосигнализации без автозапуска', '/autosygnal?SELECT=name&ype=without-auto', 'Sections-Product', 3, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(4, 'Каталог автосигнализаций Starline', '/autosygnal?SELECT=name&type=starline', 'Sections-Product', 4, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(5, 'Пульты и аксессуары', '/autosygnal?SELECT=name&type=remote-controls', 'Sections-Product', 5, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(8, 'УСЛУГИ АВТОЭЛЕКТРИКА', '/service?service=autoelectric', 'Services', 1, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(9, 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА', '/service?service=diagnostic', 'Services', 2, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(10, 'ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ', '/service?service=disabled-autosynal', 'Services', 3, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(11, 'РЕМОНТ ЦЕНТРОЗАМКОВ', '/service?service=locks', 'Services', 4, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(12, 'РУСИФИКАЦИЯ АВТО И ЧИПТЮНИНГ', '/service?service=rus', 'Services', 5, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(13, 'УСТАНОВКА И РЕМОНТ АВТОСИГНАЛИЗАЦИЙ', '/service?service=setup', 'Services', 6, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(14, 'УСТАНОВКА АВТОЗВУКА И МУЛЬТИМЕДИА', '/service?service=setup-media', 'Services', 7, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(15, 'УСТАНОВКА СИСТЕМ ПАРКИНГА', '/service?service=setup-system-parking', 'Services', 8, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(16, 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ И АНТИРАДАРОВ', '/service?service=setup-videoregistration', 'Services', 9, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(23, 'Основной', '+77077478212', 'Contacts', 1, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(24, 'Адрес:', 'https://2gis.kz/almaty/geo/70000001027313872', 'Contacts', 3, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(25, 'BEELINE:', '+7707748212', 'Contacts', 4, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(26, 'KCELL:', '+7701748212', 'Contacts', 5, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(27, 'Whatsapp:', 'https://wa.me/77077478212', 'Contacts', 6, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(28, 'Instagramm:', 'https://www.instagram.com/autosecurity_kz', 'Contacts', 7, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(29, 'Почта:', 'autosecurity.site@mail.ru', 'Contacts', 8, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(30, 'Карта', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1453.4679397503296!2d76.8722813!3d43.231804!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3883693b733bff39%3A0x716633e11986b3f8!2sAuto%20Security!5e0!3m2!1sru!2sru!4v1735233649305!5m2!1sru!2sru', 'Contacts', 10, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(31, 'Сайт:', 'https://starline-service.kz/', 'Contacts', 12, '2025-07-31 16:05:02', '2025-07-31 16:05:02'),
(32, 'Основной', '+77077478212', 'Contacts', 62, '2025-08-01 12:56:42', '2025-08-01 12:56:42'),
(42, 'Новая услуга', '/service?service=novaya-usluga', 'Services', 19, '2025-08-01 15:01:51', '2025-08-01 15:01:51');

-- --------------------------------------------------------

--
-- Структура таблицы `Navigation`
--

DROP TABLE IF EXISTS `Navigation`;
CREATE TABLE `Navigation` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `isActive` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `Navigation`
--

INSERT INTO `Navigation` (`id`, `title`, `link`, `icon_path`, `sort_order`, `isActive`, `created_at`, `updated_at`) VALUES
(12, 'Автосигнализации', '/autosygnals', '/server/uploads/navigation/icons/icon-1753975011.svg', 2, 1, '2025-07-31 15:16:51', '2025-08-01 13:54:38'),
(13, 'Главная', '/', '/server/uploads/navigation/icons/icon-1754055507.svg', 1, 1, '2025-08-01 13:38:27', '2025-08-01 13:54:38'),
(14, 'Услуги', '/services', NULL, 4, 1, '2025-08-01 13:39:30', '2025-08-02 15:02:57'),
(15, '22222', '/autosygnals', NULL, 3, 1, '2025-08-02 15:00:15', '2025-08-02 15:02:57');

-- --------------------------------------------------------

--
-- Структура таблицы `Prices`
--

DROP TABLE IF EXISTS `Prices`;
CREATE TABLE `Prices` (
  `id` int NOT NULL,
  `content` text NOT NULL,
  `price` int NOT NULL
);

--
-- Дамп данных таблицы `Prices`
--

INSERT INTO `Prices` (`id`, `content`, `price`) VALUES
(5, '<ul><li>Описание услуги33333333444</li><li>23234</li><li>234</li><li>2342</li><li>34555533</li></ul>', 2000012333);

-- --------------------------------------------------------

--
-- Структура таблицы `Products`
--

DROP TABLE IF EXISTS `Products`;
CREATE TABLE `Products` (
  `id` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `price` int NOT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `link` varchar(255) DEFAULT NULL,
  `is_popular` tinyint(1) DEFAULT '0',
  `is_special` tinyint(1) DEFAULT '0',
  `gallery` TEXT DEFAULT NULL,
  `functions` TEXT DEFAULT NULL,
  `options` TEXT DEFAULT NULL,
  `options_filters` TEXT DEFAULT NULL,
  `autosygnals` TEXT DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `Products`
--

INSERT INTO `Products` (`id`, `category`, `model`, `title`, `description`, `price`, `currency`, `quantity`, `link`, `is_popular`, `is_special`, `gallery`, `functions`, `options`, `options_filters`, `autosygnals`, `created_at`, `updated_at`) VALUES
('product_keychain_176280a0-ac34-415f-ad1c-018f9fa56633', 'keychain', '222', 'Новый товар33', 'Введите описание...ыфвфывфыв', 1222222, '₸', 1, '/product?category=keychain&id=product_keychain_176280a0-ac34-415f-ad1c-018f9fa56633', 0, 1, '[\"/server/uploads/products/gallery/product_keychain_176280a0-ac34-415f-ad1c-018f9fa56633/70ef2561-46f7-463f-8027-098ac509e6b9.jpg\"]', '[]', '[]', '[]', '[]', '2025-07-29 15:08:20', '2025-08-02 20:34:37'),
('product_keychain_6a86bd58-86cc-4cfa-adb0-84fc3c4aad63', 'keychain', '', 'Новый товарwww', 'Введите описание...', 0, '₸', 1, '/product?category=keychain&id=product_keychain_6a86bd58-86cc-4cfa-adb0-84fc3c4aad63', 0, 0, '[]', '[]', '[]', '[]', '[]', '2025-07-29 15:30:13', '2025-07-29 15:30:13'),
('product_keychain_a60', 'keychain', 'a60', 'Starline A60 Eco', 'Умный охранно-телематический комплекс StarLine А60 включает лучшие решения в области автобезопасности и гарантирует надежную защиту от угона благодаря устойчивому к электронному взлому диалоговому коду управления и широким возможностям для авторских блокировок двигателя. А гибкие настройки сервисных функций подарят вашему автомобилю уровень комфорта премиум-класса', 43700, '₸', 1, '/product?category=keychain&id=product_keychain_a60', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a60/package_Starline-A60-Eco.avif\", \"http://localhost:3000/client/images/products/starline_a60/Starline-A60-Eco-3.avif\", \"http://localhost:3000/client/images/products/starline_a60/Starline-A60-Eco-2.avif\", \"http://localhost:3000/client/images/products/starline_a60/Starline-A60-Eco-1.avif\"]', '[]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"without-auto\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 11:13:16'),
('product_keychain_a63', 'keychain', 'a63', 'Starline A63 V2', 'Надежный автомобильный охранно-телематический комплекс с несканируемым диалоговым кодом управления, опциональными 2CAN+2LIN и GSM интерфейсами, опциональным GPS-ГЛОНАСС мониторингом, ударопрочным брелком управления, 128-канальным помехозащищенным трансивером с дальностью оповещения до 2000 м', 58300, '₸', 1, '/product?category=keychain&id=product_keychain_a63', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a63/package-starline-a63.avif\", \"http://localhost:3000/client/images/products/starline_a63/keychain-Starline-A63-ECO-V2.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-1.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-2.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain_a93-v2-3.avif\"]', '[]', '[]', '[]', '[\"without-auto\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_a63-eco-v2', 'keychain', 'a63-eco-v2', 'Starline A63 ECO V2', 'Надежный автомобильный охранно-телематический комплекс с несканируемым диалоговым кодом управления, опциональными 2CAN+2LIN и GSM интерфейсами, опциональным GPS-ГЛОНАСС мониторингом, ударопрочным брелком управления, 128-канальным помехозащищенным трансивером с дальностью оповещения до 2000 м. Комплектуется только с 1 пультом ДУ.', 50300, '₸', 1, '/product?category=keychain&id=product_keychain_a63-eco-v2', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a63/package-starline-a63-eco.avif\", \"http://localhost:3000/client/images/products/starline_a63/keychain-Starline-A63-ECO-V2.avif\"]', '[\"БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN\"]', '[]', '[\"block-engine-can\"]', '[\"without-auto\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_a90', 'keychain', 'a90', 'Starline A90 Eco', 'Умный охранно-телематический комплекс StarLine B97 с интегрированными GSM или LTE и GPS+ГЛОНАСС интерфейсами включает лучшие решения в области автобезопасности и гарантирует надежную защиту от угона благодаря устойчивому к электронному взлому диалоговому коду управления, широким возможностям для авторских блокировок двигателя, обеспечивающим защиту от ретрансляции, уникальному трансиверу для работы в условиях экстремальных городских радиопомех. Вашему автомобилю подарят функции комфорта премиум-класса: Умная цифровая интеграция 3CAN+4LIN 60 программ гибкой логики Bluetooth Smart-интерфейс', 60200, '₸', 1, '/product?category=keychain&id=product_keychain_a90', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a90/package-Starline-A90-Eco.avif\", \"http://localhost:3000/client/images/products/starline_a60/Starline-A60-Eco-3.avif\", \"http://localhost:3000/client/images/products/starline_a60/Starline-A60-Eco-2.avif\", \"http://localhost:3000/client/images/products/starline_a60/Starline-A60-Eco-1.avif\"]', '[\"Автозапуск\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\"]', '[\"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_a93-2can-2lin', 'keychain', 'a93-2can-2lin', 'Starline A93 2CAN 2LIN ECO V2', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском, несканируемым диалоговым кодом управления, интегрированным 2CAN+2LIN интерфейсом, опциональным GSM интерфейсами, опциональным GPS-ГЛОНАСС мониторингом, SUPER SLAVE авторизацией, ударопрочным брелком управления, 128-канальным помехозащищенным трансивером с дальностью оповещения до 2000 м', 99800, '₸', 1, '/product?category=keychain&id=product_keychain_a93-2can-2lin', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a93/package-starline-a93-2can-2lin-eco.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-1.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-2.avif\"]', '[\"Автозапуск\", \"БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\", \"block-engine-can\", \"control-before-start\"]', '[\"auto\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_a93-2can-2lin-gsm', 'keychain', 'a93-2can-2lin-gsm', 'Starline A93 V2 2CAN+2LIN GSM ECO', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском, несканируемым диалоговым кодом управления, интегрированными 2CAN+2LIN и GSM интерфейсами, опциональным GPS-ГЛОНАСС мониторингом, SUPER SLAVE авторизацией, ударопрочным брелком управления, 128-канальным помехозащищенным трансивером с дальностью оповещения до 2000 м', 159000, '₸', 1, '/product?category=keychain&id=product_keychain_a93-2can-2lin-gsm', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-1.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-2.avif\", \"http://localhost:3000/client/images/products/starline_a93/starline-А93-v2-ECO.avif\"]', '[\"Автозапуск\", \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"БЕСПЛАТНЫЙ МОНИТОРИНГ\", \"БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\", \"control-phone\", \"free-monitoring\", \"block-engine-can\", \"control-before-start\"]', '[\"auto\", \"gsm\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_a93-eco', 'keychain', 'a93-eco', 'Starline A93 ECO V2', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском, несканируемым диалоговым кодом управления, опциональными 2CAN+2LIN и GSM интерфейсами, опциональным GPS-ГЛОНАСС мониторингом, ударопрочным брелком управления, 128-канальным помехозащищенным трансивером с дальностью оповещения до 2000 м', 64900, '₸', 1, '/product?category=keychain&id=product_keychain_a93-eco', 1, 1, '[\"http://localhost:3000/client/images/products/starline_a93/starline-A93-ECO-V2-main.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-1.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-2.avif\", \"http://localhost:3000/client/images/products/starline_a93/package-Starline-A93-2CAN-2LIN-ECO-V2.avif\"]', '[\"Автозапуск\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\", \"control-before-start\"]', '[\"auto\", \"gsm\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_a93-v2', 'keychain', 'a93-v2', 'StarLine A93 V2', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском, несканируемым диалоговым кодом управления, опциональными 2CAN+LIN и GSM интерфейсами, опциональным GPS-ГЛОНАСС мониторингом, ударопрочным брелком управления, 128-канальным помехозащищенным трансивером с дальностью оповещения до 2000 м', 74300, '₸', 1, '/product?category=keychain&id=product_keychain_a93-v2', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a93/starline-A93-ECO-V2-main.avif\", \"http://localhost:3000/client/images/products/starline_a93/package-Starline-A93-2CAN-2LIN-ECO-V2.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-1.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-2.avif\"]', '[\"Автозапуск\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\", \"control-before-start\"]', '[\"auto\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_a93-v2-gsm', 'keychain', 'a93-v2-gsm', 'Starline A93 V2 GSM', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском, несканируемым диалоговым кодом управления, GSM интерфейсом, опциональными 2CAN+2LIN интерфейсами, опциональным GPS-ГЛОНАСС мониторингом, ударопрочным брелком управления, 128-канальным помехозащищенным трансивером с дальностью оповещения до 2000 м', 122700, '₸', 1, '/product?category=keychain&id=product_keychain_a93-v2-gsm', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a93/starline-A93-ECO-V2-main.avif\", \"http://localhost:3000/client/images/products/starline_a93/package-Starline-A93-2CAN-2LIN-ECO-V2.avif\"]', '[\"Автозапуск\", \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\", \"control-phone\", \"control-before-start\"]', '[\"auto\", \"gsm\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_b97', 'keychain', 'b97', 'Автосигнализация StarLine B97 2SIM BT LTE-GPS', 'Умный охранно-телематический комплекс StarLine B97 с интегрированными GSM или LTE и GPS+ГЛОНАСС интерфейсами включает лучшие решения в области автобезопасности и гарантирует надежную защиту от угона благодаря устойчивому к электронному взлому диалоговому коду управления, широким возможностям для авторских блокировок двигателя, обеспечивающим защиту от ретрансляции, уникальному трансиверу для работы в условиях экстремальных городских радиопомех. Вашему автомобилю подарят функции комфорта премиум-класса: Умная цифровая интеграция 3CAN+4LIN 60 программ гибкой логики Bluetooth Smart-интерфейс', 298700, '₸', 1, '/product?category=keychain&id=product_keychain_b97', 0, 0, '[\"http://localhost:3000/client/images/products/starline_b97/StarLine-B97-2SIM-BT-LTE-GPS.avif\", \"http://localhost:3000/client/images/products/starline_b97/StarLine-B97-2SIM-BT-LTE-GPS-1.avif\", \"http://localhost:3000/client/images/products/starline_b97/StarLine-B97-2SIM-BT-LTE-GPS-2.avif\", \"http://localhost:3000/client/images/products/starline_b97/StarLine-B97-2SIM-BT-LTE-GPS-3.avif\", \"http://localhost:3000/client/images/products/starline_s96/StarLine-S96-V2-BT-2CAN-2LIN-GSM.avif\", \"http://localhost:3000/client/images/products/starline_b97/package-StarLine-B97-2SIM-BT-LTE-GPS.avif\"]', '[\"Автозапуск\", \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"БЕСПЛАТНЫЙ МОНИТОРИНГ\", \"УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART\", \"БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\", \"УМНАЯ АВТОДИАГНОСТИКА\", \"ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\", \"control-phone\", \"free-monitoring\", \"bluetooth-smart\", \"block-engine-can\", \"control-before-start\", \"smart-diagnostic\", \"data-level-bensin\"]', '[\"gsm\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_e96-2can-4lin-gsm', 'keychain', 'e96-2can-4lin-gsm', 'StarLine E96 V2 BT 2CAN+4LIN 2SIM GSM+GPS', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском с вашего смартфона, несканируемым диалоговым кодом управления, возможностью авторизации по защищенной технологии через смартфон и метку, интегрированным 2CAN+4LIN. Благодаря GSM+GPS интерфейсу обеспечивается возможность управления комплексом из любой точки мира.', 167200, '₸', 1, '/product?category=keychain&id=product_keychain_e96-2can-4lin-gsm', 1, 0, '[\"http://localhost:3000/client/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN.avif\", \"http://localhost:3000/client/images/products/starline_e96/package-StarLine-E96-v2-BT-2CAN-4LIN-2SIM-GSM-GPS.avif\", \"http://localhost:3000/client/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN-1.avif\"]', '[\"Автозапуск\", \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"БЕСПЛАТНЫЙ МОНИТОРИНГ\", \"УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART\", \"БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\", \"УМНАЯ АВТОДИАГНОСТИКА\", \"ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА\"]', '[\"Для легкового авто\"]', '[\"legkoe-avto\", \"autosetup\", \"control-phone\", \"free-monitoring\", \"bluetooth-smart\", \"block-engine-can\", \"control-before-start\", \"smart-diagnostic\", \"data-level-bensin\"]', '[\"auto\", \"gsm\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_e96-eco', 'keychain', 'e96-eco', 'StarLine E96 V2 BT ECO 2CAN+4LIN', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском, несканируемым диалоговым кодом управления, возможностью авторизации по защищенному протоколу через персональную метку или смартфон на iOS и Android с мобильным приложением StarLine, интегрированным 2CAN+4LIN интерфейсом, ударопрочным брелком управления, помехозащищенным трансивером с малошумящим усилителем', 99800, '₸', 1, '/product?category=keychain&id=product_keychain_e96-eco', 1, 1, '[\"http://localhost:3000/client/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN.avif\", \"http://localhost:3000/client/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN-1.avif\", \"http://localhost:3000/client/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN-2.avif\", \"http://localhost:3000/client/images/products/starline_e96/ppackage-StarLine-E96-V2-BT-ECO-2CAN-4LIN.avif\"]', '[]', '[]', '[]', '[\"auto\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_e96-gsm', 'keychain', 'e96-gsm', 'Starline E96 V2 BT GSM', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском с вашего смартфона, несканируемым диалоговым кодом управления, возможностью авторизации по защищенной технологии через смартфон и метку, интегрированным 2CAN+4LIN. Благодаря GSM интерфейсу обеспечивается возможность управления комплексом из любой точки мира.', 147100, '₸', 1, '/product?category=keychain&id=product_keychain_e96-gsm', 0, 1, '[\"http://localhost:3000/client/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN.avif\", \"http://localhost:3000/client/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN-1.avif\", \"http://localhost:3000/client/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN-2.avif\", \"http://localhost:3000/client/images/products/starline_e96/package-Starline-E96-V2-BT-GSM.avif\"]', '[\"Автозапуск\", \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"БЕСПЛАТНЫЙ МОНИТОРИНГ\", \"УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART\", \"БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\", \"УМНАЯ АВТОДИАГНОСТИКА\", \"ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\", \"control-phone\", \"free-monitoring\", \"bluetooth-smart\", \"block-engine-can\", \"control-before-start\", \"smart-diagnostic\", \"data-level-bensin\"]', '[\"auto\", \"gsm\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_s96-2can-2lin-gsm', 'keychain', 's96-2can-2lin-gsm', 'StarLine S96 V2 BT 2CAN2LIN GSM', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском с вашего смартфона, несканируемым диалоговым кодом управления, возможностью авторизации по технологии Bluetooth Smart, интегрированными 2CAN+2LIN и GSM интерфейсами.', 113600, '₸', 1, '/product?category=keychain&id=product_keychain_s96-2can-2lin-gsm', 1, 1, '[\"http://localhost:3000/client/images/products/starline_s96/StarLine-S96-V2-BT-2CAN-2LIN-GSM.avif\", \"http://localhost:3000/client/images/products/starline_s96/StarLine-S96-V2-BT-2CAN-2LIN-GSM-2.avif\"]', '[\"Автозапуск\", \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART\", \"БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\", \"УМНАЯ АВТОДИАГНОСТИКА\", \"ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\", \"control-phone\", \"bluetooth-smart\", \"block-engine-can\", \"control-before-start\", \"smart-diagnostic\", \"data-level-bensin\"]', '[\"auto\", \"gsm\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_s96-2can-4lin', 'keychain', 's96-2can-4lin', 'Starline S96 V2 BT 2CAN 4LIN 2SIM GPS', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском с вашего смартфона, несканируемым диалоговым кодом управления, интегрированными 2CAN+2LIN и GSM интерфейсами, авторизацией по технологии Bluetooth Smar', 140000, '₸', 1, '/product?category=keychain&id=product_keychain_s96-2can-4lin', 0, 0, '[\"http://localhost:3000/client/images/products/starline_s96/StarLine-S96-V2-BT-2CAN-2LIN-GSM.avif\", \"http://localhost:3000/client/images/products/starline_s96/StarLine-S96-V2-BT-2CAN-2LIN-GSM-2.avif\", \"http://localhost:3000/client/images/products/starline_s96/package-StarLine-S96-V2-BT-2CAN-2LIN-GSM.avif\"]', '[\"Автозапуск\", \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"БЕСПЛАТНЫЙ МОНИТОРИНГ\", \"УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART\", \"БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\", \"УМНАЯ АВТОДИАГНОСТИКА\", \"ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\", \"control-phone\", \"free-monitoring\", \"bluetooth-smart\", \"block-engine-can\", \"control-before-start\", \"smart-diagnostic\", \"data-level-bensin\"]', '[\"auto\", \"gsm\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_keychain_s96-lte', 'keychain', 's96-lte', 'Starline S96 V2 BT LTE', 'Надежный автомобильный охранно-телематический комплекс с интеллектуальным автозапуском с вашего смартфона, несканируемым диалоговым кодом управления, возможностью авторизации по защищенной технологии через смартфон и метку, интегрированным 2CAN+4LIN. Благодаря GSM интерфейсу обеспечивается возможность управления комплексом из любой точки мира.м', 128200, '₸', 1, '/product?category=keychain&id=product_keychain_s96-lte', 0, 0, '[\"http://localhost:3000/client/images/products/starline_s96/StarLine-S96-V2-BT-2CAN-2LIN-GSM-2.avif\", \"http://localhost:3000/client/images/products/starline_s96/StarLine-S96-V2-BT-2CAN-2LIN-GSM.avif\", \"http://localhost:3000/client/images/products/starline_s96/package-starline-S96-v2-lte.avif\"]', '[\"Автозапуск\", \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"БЕСПЛАТНЫЙ МОНИТОРИНГ\", \"УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART\", \"БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN\", \"УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ\", \"УМНАЯ АВТОДИАГНОСТИКА\", \"ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА\"]', '[\"Для легкового авто\", \"Для внедорожника\"]', '[\"vnedorojnik\", \"legkoe-avto\", \"autosetup\", \"control-phone\", \"free-monitoring\", \"bluetooth-smart\", \"block-engine-can\", \"control-before-start\", \"smart-diagnostic\", \"data-level-bensin\"]', '[\"auto\", \"gsm\", \"starline\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_park-systems_camera-c1100', 'park-systems', 'camera-c1100', 'Камера заднего вида Alpine HCE C1100', 'Камера заднего вида Alpine HCE C1100 - превосходное качество, широкий угол обзора.', 65000, '₸', 1, '/product?category=park-systems&id=product_park-systems_camera-c1100', 0, 0, '[\"http://localhost:3000/client/images/products/park-systems/camera-rear-view-alpine-hce-c1-100/camera-rear-view-alpine-hce-c1-100-1.avif\", \"http://localhost:3000/client/images/products/park-systems/camera-rear-view-alpine-hce-c1-100/camera-rear-view-alpine-hce-c1-100-2.avif\", \"http://localhost:3000/client/images/products/park-systems/camera-rear-view-alpine-hce-c1-100/camera-rear-view-alpine-hce-c1-100-3.avif\", \"http://localhost:3000/client/images/products/park-systems/camera-rear-view-alpine-hce-c1-100/camera-rear-view-alpine-hce-c1-100-4.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"for-park-systems\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_park-systems_camera-ccd', 'park-systems', 'camera-ccd', 'Камера заднего вида CCD', 'Камера заднего вида с четким изображением и CCD матрицей', 12000, '₸', 1, '/product?category=park-systems&id=product_park-systems_camera-ccd', 0, 0, '[\"http://localhost:3000/client/images/products/park-systems/camera-rear-view-ccd/camera-rear-view-ccd.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"for-park-systems\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_park-systems_camera-cmos130', 'park-systems', 'camera-cmos130', 'Камера заднего вида Kenwood CMOS-130', 'Kenwood CMOS-130 - обычная камера заднего вида, без разметки.', 35000, '₸', 1, '/product?category=park-systems&id=product_park-systems_camera-cmos130', 0, 0, '[\"http://localhost:3000/client/images/products/park-systems/camera-rear-view-kenwood-cmos-130/camera-rear-view-kenwood-cmos-130.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"for-park-systems\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_park-systems_camera-prado150', 'park-systems', 'camera-prado150', 'Камера заднего вида на Toyota Prado 150', 'Инфракрасная камера заднего вида для Toyota Prado 150, оборудованная 4 красными светодиодами для ночного виденья, прекрасно поможет Вам запарковаться в любое время суток! Камера выполнена в черном пластиковом водонепроницаемом корпусе. Данная модель может подойти и на другие автомобили, имеющие небольшое квадратное отверстие на крышке багажника для крепления камеры.', 15000, '₸', 1, '/product?category=park-systems&id=product_park-systems_camera-prado150', 0, 0, '[\"http://localhost:3000/client/images/products/park-systems/camera-rear-view-toyota-prado-150/camera-rear-view-toyota-prado-150-1.avif\", \"http://localhost:3000/client/images/products/park-systems/camera-rear-view-toyota-prado-150/camera-rear-view-toyota-prado-150-2.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"for-park-systems\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_park-systems_cj-178', 'park-systems', 'cj-178', 'Камера заднего вида CJ-178', 'Камера заднего вида с инфракрасной подсветкой и углом обзора 140 градусов. Модель CJ-178 снабжена четырьмя LED диодами инфракрасной подсветки и имеется возможность регулировки камеры по вертикали, т.о. это делает камеру универсальной для установки на широкий круг автомобилей.', 10000, '₸', 1, '/product?category=park-systems&id=product_park-systems_cj-178', 0, 0, '[\"http://localhost:3000/client/images/products/park-systems/camera-rear-view-cj-178/camera-rear-view-cj-178-1.avif\", \"http://localhost:3000/client/images/products/park-systems/camera-rear-view-cj-178/rear-view-camera-cj-178-2.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"for-park-systems\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_park-systems_cj-188', 'park-systems', 'cj-188', 'Камера заднего вида CJ-188', 'Видеокамера заднего обзора универсальная с инфракрасной подсветкой CJ-188 хорошо впишется в пространство рядом с фонарем подсветки номера. Особенность модели в том, что она самая маленькая среди своих собратьев имеющих инфракрасную подсветку, камера незаметна при монтаже. Благодаря инфракрасной подсветке парковка ночью будет безопасной и приятной, так как подсветка помогает камере видеть ночью до 5 метров в черно-белом изображении, а днем показывает цветным.', 10000, '₸', 1, '/product?category=park-systems&id=product_park-systems_cj-188', 0, 0, '[\"http://localhost:3000/client/images/products/park-systems/camera-rear-view-cj-188/full_camera-rear-view-CJ-188-1.avif\", \"http://localhost:3000/client/images/products/park-systems/camera-rear-view-cj-188/full_camera-rear-view-CJ-188-2.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"for-park-systems\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_park-systems_number-plate-with-camera', 'park-systems', 'number-plate-with-camera', 'Подномерник с камерой заднего вида', 'Камера в номерной рамке завоевала большую популярность среди автомобилистов благодаря своей универсальности. Основное ее преимущество - это особенность установки. Ведь ее можно установить практически на любой автомобиль вместо рамки номерного знака. Камера в номерной рамке имеет центральное расположение камеры, что дает возможность максимально по центру получать изображение с камеры.', 15000, '₸', 1, '/product?category=park-systems&id=product_park-systems_number-plate-with-camera', 0, 0, '[\"http://localhost:3000/client/images/products/park-systems/number-plate-with-camera/number-plate-with-rear-view-camera-1.avif\", \"http://localhost:3000/client/images/products/park-systems/number-plate-with-camera/number-plate-with-rear-view-camera-2.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"for-park-systems\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_20w', 'remote-controls', '20w', 'Сирена 20W', 'Сирена для автосигнализаций', 3500, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_20w', 0, 0, '[\"http://localhost:3000/client/images/products/remote-controls/alarm-siren-20w-1.avif\", \"http://localhost:3000/client/images/products/remote-controls/alarm-siren-20w-2.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_a91-61', 'remote-controls', 'a91-61', 'Пульт Starline A91/61', 'Пульт на автосигнализацию Starline A91/A61 с жидкокристаллическим дисплеем', 27000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_a91-61', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a91/remote-controls-starline-a91.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_a93-a63', 'remote-controls', 'a93-a63', 'Пульт Starline A93/A63', 'Пульт на автосигнализацию Starline A93/A63 с жидкокристаллическим дисплеем', 27000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_a93-a63', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a93/starline-A93-ECO-V2-main.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-1.avif\", \"http://localhost:3000/client/images/products/starline_a93/keychain-Starline-A93-ECO-V2-2.avif\", \"http://localhost:3000/client/images/products/starline_a93/package-starline-a93-2can-2lin-eco.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_a96-a66', 'remote-controls', 'a96-a66', 'Пульт Starline A96/A66', 'Пульт на автосигнализацию Starline A96/A66 с жидкокристаллическим дисплеем', 27000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_a96-a66', 0, 0, '[\"http://localhost:3000/client/images/products/starline_a96/remote-controls-Starline-A96-A66.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_b96-b66', 'remote-controls', 'b96-b66', 'Пульт Starline B96/B66', 'Пульт на автосигнализацию Starline B96/B66 с жидкокристаллическим дисплеем', 27000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_b96-b66', 0, 0, '[\"http://localhost:3000/client/images/products/starline_b96/remote-controls-starline-b96-b66.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_d96', 'remote-controls', 'd96', 'Пульт Starline D96', 'Пульт на автосигнализацию Starline D96 с жидкокристаллическим дисплеем', 27000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_d96', 0, 0, '[\"http://localhost:3000/client/images/products/starline_d96/remote-controls-starline-d96.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_e96-e66', 'remote-controls', 'e96-e66', 'Пульт Starline E96/E66', 'Пульт на автосигнализацию Starline E96/E66 c жидкокристаллическим дисплеем', 27000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_e96-e66', 0, 0, '[\"http://localhost:3000/client/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_module-2can-2lin', 'remote-controls', 'module-2can-2lin', 'StarLine 2CAN 2LIN Встраиваемый модуль', 'Встраиваемый модуль StarLine GSM+BT предназначен для установки на 5 и 6 поколение автосигнализаций Starline.', 34000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_module-2can-2lin', 0, 0, '[\"http://localhost:3000/client/images/products/module_starline/module-StarLine-2CAN-2LIN.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_module-bp-03', 'remote-controls', 'module-bp-03', 'Модуль обхода штатного иммобилайзера StarLine BP-03', 'Данный модуль предназначен для обхода штатного иммобилайзера и может работать с любой автосигнализацией с автозапуском. Для работы модуля необходимо поместить в него штатный ключ от автомобиля или чип.', 7000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_module-bp-03', 0, 0, '[\"http://localhost:3000/client/images/products/module_bp03/module-StarLine-BP-03.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_module-gps', 'remote-controls', 'module-gps', 'StarLine GPS Встраиваемый модуль', 'Внешняя GPS/ГЛОНАСС антенна для автосигнализаций Starline. Устанавливается только совместно c GSM-модулем Starline.', 25000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_module-gps', 0, 0, '[\"http://localhost:3000/client/images/products/module_starline/module-starline-gps.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_module-gsm3', 'remote-controls', 'module-gsm3', 'StarLine GSM3 Встраиваемый модуль', 'GSM модуль для установки на 3 и 4 поколение автосигнализаций Starline.', 52600, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_module-gsm3', 0, 0, '[\"http://localhost:3000/client/images/products/module_starline/module-starline-gsm3.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_module-gsm6', 'remote-controls', 'module-gsm6', 'StarLine GSM6+BT Встраиваемый модуль', 'Встраиваемый модуль StarLine GSM+BT предназначен для установки на 5 и 6 поколение автосигнализаций Starline.', 52600, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_module-gsm6', 0, 0, '[\"http://localhost:3000/client/images/products/module_starline/module-starline-gsm3.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_push-motor', 'remote-controls', 'push-motor', 'Моторчик-толкатель центрального замка', 'Моторчик-толкатель центрального замка для ремонта систем центральных замков', 5000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_push-motor', 0, 0, '[\"http://localhost:3000/client/images/products/remote-controls/central-lock-motor.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"for-park-systems\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_sd-card', 'remote-controls', 'sd-card', 'Sd-карта памяти для видеорегистраторов', '16GB Micro SD Memory Card UHS-I U1 CLASS 10 SDXC w/ Adapter 100MB/s', 7000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_sd-card', 0, 0, '[\"http://localhost:3000/client/images/products/sd-card/toshiba-sd-card-16-1.avif\", \"http://localhost:3000/client/images/products/sd-card/toshiba-sd-card-16-2.avif\", \"http://localhost:3000/client/images/products/sd-card/toshiba-sd-card-16-3.avif\", \"http://localhost:3000/client/images/products/sd-card/toshiba-sd-card-16-4.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05'),
('product_remote-controls_sheriff', 'remote-controls', 'sheriff', 'Sheriff Доводчик стекол 4х', 'Sheriff - аналоговый доводчик четырех стекол', 15000, '₸', 1, '/product?category=remote-controls&id=product_remote-controls_sheriff', 0, 0, '[\"http://localhost:3000/client/images/products/remote-controls/sheriff-4x.avif\"]', NULL, NULL, '[\"vnedorojnik\", \"legkoe-avto\"]', '[\"remote-controls\"]', '2025-07-18 09:55:05', '2025-07-18 09:55:05');

-- --------------------------------------------------------

--
-- Структура таблицы `Products_prices`
--

DROP TABLE IF EXISTS `Products_prices`;
CREATE TABLE `Products_prices` (
  `product_id` varchar(255) NOT NULL,
  `price_id` int NOT NULL
);

--
-- Дамп данных таблицы `Products_prices`
--

INSERT INTO `Products_prices` (`product_id`, `price_id`) VALUES
('product_keychain_176280a0-ac34-415f-ad1c-018f9fa56633', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `Sections-Product`
--

DROP TABLE IF EXISTS `Sections-Product`;
CREATE TABLE `Sections-Product` (
  `section_product_id` int NOT NULL,
  `type` varchar(255) DEFAULT NULL COMMENT 'Тип секции',
  `link` varchar(255) DEFAULT NULL COMMENT 'Ссылка на секцию',
  `name` varchar(255) DEFAULT NULL COMMENT 'Название секции',
  `count` int DEFAULT NULL COMMENT 'Количество товаров в секции',
  `src` varchar(255) DEFAULT NULL COMMENT 'Ссылка на изображение',
  `position` int DEFAULT NULL COMMENT 'Порядок сортировки',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `Sections-Product`
--

INSERT INTO `Sections-Product` (`section_product_id`, `type`, `link`, `name`, `count`, `src`, `position`, `created_at`, `updated_at`) VALUES
(1, 'auto', '/autosygnal?SELECT=name&type=auto', 'Автосигнализации с автозапуском', 0, '/client/images/autosygnals/autosygnals-1.avif', 1, '2025-07-30 04:23:35', '2025-07-30 04:23:35'),
(2, 'gsm', '/autosygnal?SELECT=name&type=gsm', 'Автосигнализации с GSM', 0, '/client/images/autosygnals/autosygnals-2.avif', 2, '2025-07-30 04:23:35', '2025-07-30 04:23:35'),
(3, 'without-auto', '/autosygnal?SELECT=name&ype=without-auto', 'Автосигнализации без автозапуска', 0, '/client/images/autosygnals/autosygnals-3.avif', 3, '2025-07-30 04:23:35', '2025-07-30 04:23:35'),
(4, 'starline', '/autosygnal?SELECT=name&type=starline', 'Каталог автосигнализаций Starline', 0, '/client/images/autosygnals/autosygnals-4.avif', 4, '2025-07-30 04:23:35', '2025-07-30 04:23:35'),
(5, 'acssesuars', '/autosygnal?SELECT=name&type=remote-controls', 'Пульты и аксессуары', 0, '/client/images/autosygnals/autosygnals-5.avif', 5, '2025-07-30 04:23:35', '2025-07-30 04:23:35');

--
-- Триггеры `Sections-Product`
--
DELIMITER $$
CREATE TRIGGER `after_sections_product_delete` AFTER DELETE ON `Sections-Product` FOR EACH ROW BEGIN
  DELETE FROM `LinksData`
  WHERE source_table = 'Sections-Product' AND source_id = OLD.section_product_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sections_product_delete_to_linksdata` AFTER DELETE ON `Sections-Product` FOR EACH ROW BEGIN
  DELETE FROM `LinksData`
  WHERE source_table = 'Sections-Product' AND source_id = OLD.section_product_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sections_product_insert` AFTER INSERT ON `Sections-Product` FOR EACH ROW BEGIN
  IF NEW.link IS NOT NULL AND NEW.link != '' THEN
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.link, 'Sections-Product', NEW.section_product_id)
    ON DUPLICATE KEY UPDATE name=NEW.name, link=NEW.link;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sections_product_insert_to_linksdata` AFTER INSERT ON `Sections-Product` FOR EACH ROW BEGIN
  IF NEW.link IS NOT NULL AND NEW.link != '' THEN
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.link, 'Sections-Product', NEW.section_product_id)
    ON DUPLICATE KEY UPDATE name=NEW.name, link=NEW.link;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sections_product_update` AFTER UPDATE ON `Sections-Product` FOR EACH ROW BEGIN
  IF NEW.link IS NULL OR NEW.link = '' THEN
    DELETE FROM `LinksData`
    WHERE source_table = 'Sections-Product' AND source_id = OLD.section_product_id;
  ELSE
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.link, 'Sections-Product', NEW.section_product_id)
    ON DUPLICATE KEY UPDATE name = VALUES(name), link = VALUES(link);
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sections_product_update_to_linksdata` AFTER UPDATE ON `Sections-Product` FOR EACH ROW BEGIN
  IF NEW.link IS NULL OR NEW.link = '' THEN
    DELETE FROM `LinksData`
    WHERE source_table = 'Sections-Product' AND source_id = OLD.section_product_id;
  ELSE
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.link, 'Sections-Product', NEW.section_product_id)
    ON DUPLICATE KEY UPDATE name = VALUES(name), link = VALUES(link);
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `Sertificates`
--

DROP TABLE IF EXISTS `Sertificates`;
CREATE TABLE `Sertificates` (
  `sertificate_id` int NOT NULL,
  `image_path` varchar(255) DEFAULT NULL COMMENT 'Путь к изображению',
  `position` int DEFAULT NULL COMMENT 'Порядок сортировки',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `Sertificates`
--

INSERT INTO `Sertificates` (`sertificate_id`, `image_path`, `position`, `created_at`, `updated_at`) VALUES
(1, '/server/uploads/sertificates/sertificate-688394c618413.avif', 2, '2025-07-25 14:26:24', '2025-07-29 16:21:09');

-- --------------------------------------------------------

--
-- Структура таблицы `Services`
--

DROP TABLE IF EXISTS `Services`;
CREATE TABLE `Services` (
  `id` int NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image_src` varchar(255) DEFAULT NULL,
  `image_alt` text,
  `href` varchar(255) NOT NULL,
  `services` text,
  `cost` int NOT NULL,
  `currency` varchar(5) NOT NULL
);

--
-- Дамп данных таблицы `Services`
--

INSERT INTO `Services` (`id`, `type`, `name`, `description`, `image_src`, `image_alt`, `href`, `services`, `cost`, `currency`) VALUES
(1, 'autoelectric', 'УСЛУГИ АВТОЭЛЕКТРИКА', '<ol><li>12312123</li><li>123</li><li>123</li><li><br></li></ol>', '/server/uploads/services/service-shawarma-slide-1753200591.jpg', 'Картинка на которой изображена услуга Услуги Автоэлектрика', '/service?service=autoelectric', '<ul><li>Диагностика электрической системы автомобиля.,</li><li>Ремонт электропроводки.,Замена аккумуляторов.,</li><li>Установка дополнительного оборудования.,</li><li>Решение проблем с запуском двигателя, связанных с автоэлектрикой.,</li><li>Услугу \"прикурить авто\".,Компьютерную диагностику и устранение неполадок.,</li><li>Установку ксенона, LED-ламп.,Ремонт стартеров и генераторов.,</li><li>Активацию ходовых огней.,Установку противотуманок.,</li><li>Замену лампочек освещения.,</li><li>Установку автомагнитол.,</li><li>Поиск и ремонт различных неисправностей.,</li><li>Мелкие работы по автоэлектрике.,</li><li>Установку автосигнализаций.,</li><li>Отключение блокировок двигателя.,</li><li>Прошивка блоков srs, восстановление  блоков srs airbag.,Русификация магнитол KIA, Hyundai, Toyota, Lexus, GM.,</li><li>Ремонт центральных замков.,Выезд мастера к Вам на место - от 5000 тг и выше.</li></ul>', 15000, '₸'),
(2, 'diagnostic', 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА', '<p>wwww</p>', '/client/images/services/service-7.avif', 'Картинка на которой изображена услуга Компьютерная диагностика.', '/service?service=diagnostic', '<p>[\"Полная диагностика автомобиля.\", \"Определение ошибок системы.\", \"Рекомендации по устранению неисправностей.\", \"Комплексную компьютерную диагностику автомобилей с 1998 г. с разъемом OBD2- от 5000 тг.\", \"Сброс ошибок.\", \"Диагностику отдельных электронных блоков*.\", \"Всегда в работе обновленный мультимарочный сканер Launch PRO с расширенными возможностями и функционалом.\", \"Консультации и советы по ремонту Вашего авто.\", \"Выезд квалифицированного специалиста к Вам на место- от 3000 тг и выше.\"]</p>', 15000, '₸'),
(3, 'disabled-autosynal', 'ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ', '<ul>\n                <li>Отказала сигнализация?</li>\n                <li>Хотите снять неисправное оборудование?</li>\n                <li>Вы продаете свой автомобиль и желаете аккуратно снять сигнализацию для последующей установки на другую машину?</li>\n                <li>Обращайтесь к нам, и вы получите квалифицированную помощь в отключении сигнализации!</li>\n              </ul>', '/client/images/services/service-8.avif', 'Картинка на которой изображена услуга Отключение сигнализации.', '/service?service=disabled-autosynal', '[\"Отключение старых сигнализаций.\", \"Настройка новых систем безопасности.\", \"Отключение автосигнализации.\", \"Полный или частичный демонтаж неисправного оборудования, согласно Вашим пожеланиям.\", \"Аккуратное снятие рабочей сигнализации для последующей установки на другой автомобиль.\", \"Восстановление проводки после снятия сигнализации в целостный вид.\", \"Отключение блокировок двигателя.\", \"Выезд квалифицированного мастера на место - от 3.000 тг и выше.\", \"Бесплатные консультации по телефону.\"]', 15000, '₸'),
(4, 'locks', 'РЕМОНТ ЦЕНТРОЗАМКОВ', '<ul>\n                <li>Вы испытываете постоянные неудобства из-за неработающего центрального замка?</li>\n                <li>Вы ищете специалистов в области ремонта систем центрозамков?</li>\n                <li>У вас нет времени приехать в квалифицированный сервис для ремонта центрального замка?</li>\n                <li>Обращайтесь к нам, и Вы получите качественные услуги по ремонту электронной части центральных замков!</li>\n              </ul>', '/client/images/services/service-2.avif', 'Картинка на которой изображена услуга Ремонт Центрозамков', '/service?service=locks', '[\"Ремонт центральных замков различных автомобилей.\", \"Установка новых замков.\", \"Замена личинок замков.\", \"Диагностику неисправностей.\", \"Замену реле центрозамка.\", \"Ремонт и замену толкателей (моторчиков) центрального замка в дверях автомобиля.\", \"Ремонт проводки центрозамка.\", \"Бесплатную консультацию по телефону.\", \"Выезд профессионала к Вам на место - от 5.000 тг и выше.\"]', 15000, '₸'),
(5, 'rus', 'РУСИФИКАЦИЯ АВТО И ЧИПТЮНИНГ', '<ul>\n                <li>Вы желали бы поменять язык на штатном головном устройстве Kia, Hyundai, Toyota, Lexus, GM?</li>\n                <li>Ваш авто после аварии и необходимо удалить ошибку по SRS?</li>\n                <li>Необходимо убрать ошибки по мотору и катализатору?</li>\n              </ul>', '/client/images/services/service-6.avif', 'Картинка на которой изображена услуга Русификация авто.', '/service?service=rus', '[\"Русификация бортового компьютера.\", \"Чип-тюнинг для улучшения производительности.\", \"Настройка параметров двигателя.\", \"Русификация Kia, Hyundai\", \"Русификация Toyota, Lexus, Chevrolet и мн.др.\", \"Русификация китайских авто\", \"Установка приложений на Android\", \"Програмное удаление вторых датчиков кислорода (удаление лямда-зондов)\", \"Програмное удаление катализаторов, EGR, AdBlue, EVAP\", \"Перепрошивку блоков SRS\", \"Выезд мастера к Вам на место\"]', 15000, '₸'),
(6, 'setup', 'УСТАНОВКА И РЕМОНТ АВТОСИГНАЛИЗАЦИЙ', '<ul>\n                <li>Вы купили автомобиль и желаете защитить его, установив сигнализацию?</li>\n                <li>Вы любите комфорт и хотите установить автозапуск на Ваше авто?</li>\n                <li>Вам необходимо отслеживать Ваш транспорт по GPS?</li>\n                <li>Обращайтесь к нам, и мы поможем Вам решить эти задачи!</li>\n              </ul>', '/client/images/services/service-1.avif', 'Картинка на которой изображена услуга Auto Security', '/service?service=setup', '[\"Продажу автосигнализаций, охранных комплексов, маяков GPS и мн.др.\", \"Профессиональную установку, ремонт, и настройку автосигнализаций различных брендов.\", \"Установку иммобилайзеров и противоугонных систем.\", \"Установку автозапуска, в том числе и со штатных пультов от автомобилей (некоторых видов).\", \"Привязку штатных охранных систем к современным охранно-телематическим комплексам.\", \"Установку сигнализаций с функцией GSM и GPS.\", \"Подключение модулей закрывания стекол.\", \"Подключение функции открывания багажника и др.\", \"Монтаж маячков и систем слежения за автомобилем.\", \"Диагностику неисправностей автосигнализаций.\", \"Ремонт автозапуска и обхода иммобилайзера.\", \"Настройку и запись пультов.\", \"Настройку чувствительности датчика удара.\", \"Замену сирен и клаксонов.\", \"Демонтаж старых автосигнализаций.\", \"Отключение блокировок двигателя.\", \"Установку оборудования с выездом.\", \"Гарантию на работу и на материал.\", \"Бесплатные консультации.\", \"Возможность выезда квалифицированного мастера к Вам на место для установки оборудования - от 3.000 тг.\"]', 15000, '₸'),
(7, 'setup-media', 'УСТАНОВКА АВТОЗВУКА И МУЛЬТИМЕДИА', '<ul>\n                <li>Вы желаете подобрать и купить новую магнитолу на Ваше авто?</li>\n                <li>Вам необходимо установить имеющуюся магнитолу?</li>\n                <li>Вы хотите получить качественные услуги в сфере установки автозвука?</li>\n                <li>Вы желаете обновить музыку на Вашем авто?</li>\n                <li>Обращайтесь к нам, и мы осуществим Ваши планы!</li>\n              </ul>', '/client/images/services/service-3.avif', 'Картинка на которой изображена услуга Установка Автозвука и Мультимедиа', '/service?service=setup-media', '[\"Установка аудиосистем.\", \"Настройка мультимедийных систем.\", \"Интеграция с мобильными устройствами.\", \"Продажу, установку и настройку автомагнитол.\", \"Изготовление креплений для магнитол.\", \"Подключение функций мультируля.\", \"Подключение/отключение усилителей.\", \"Установку сабвуферов.\", \"Замену и первичную установку колонок.\", \"Установку мониторов и телевизоров (DVD/TV).\", \"Установку и замену камер заднего и переднего вида.\", \"Установку и замену антенн для радио.\", \"Выезд квалифицированного мастера к Вам на место - от 3.000 тг и выше.\", \"Доставку выбранного на нашем сайте оборудования.\"]', 15000, '₸'),
(8, 'setup-system-parking', 'УСТАНОВКА СИСТЕМ ПАРКИНГА', '<ul>\n                <li>Вы часто разбиваете или царапаете бампер?</li>\n                <li>Вам необходим \'ассистент\' парковки?</li>\n                <li>Обращайтесь к нам, и мы поможем Вам решить эти проблемы!</li>\n              </ul>', '/client/images/services/service-4.avif', 'Картинка на которой изображена услуга Установка Систем Паркинга', '/service?service=setup-system-parking', '[\"Установка датчиков парковки.\", \"Настройка камер заднего вида.\", \"Продажу систем паркинга.\", \"Установку и замену камер заднего вида.\", \"Бесплатные консультации.\", \"Выезд специалиста к Вам на место для монтажа оборудования - от 3.000 тг и выше.\"]', 15000, '₸'),
(9, 'setup-videoregistration', 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ И АНТИРАДАРОВ', '<ul>\n                <li>Вы хотите купить и установить видеорегистратор или антирадар?</li>\n                <li>Вам надоели болтающиеся провода?</li>\n                <li>Вы хотите освободить гнездо (розетку) прикуривателя?</li>\n                <li>Обращайтесь к нам, и мы сможем Вам помочь в решении этих вопросов!</li>\n              </ul>', '/client/images/services/service-9.avif', 'Картинка на которой изображена услуга Установка видеорегистраторов и антирадаров.', '/service?service=setup-videoregistration', '[\"Установка видеорегистраторов.\", \"Настройка антирадаров.\", \"Аккуратную установку видеорегистраторов и антирадаров.\", \"Настройку оборудования.\", \"Скрытое подключение: cпрячем все лишние провода от видеорегистратора или антирадара и освободим гнездо прикуривателя.\", \"Бесплатные консультации.\", \"Услугу выезда мастера к Вам на место - от 5000 тг и выше.\", \"Доставку выбранного Вами оборудования на нашем сайте.\"]', 15000, '₸'),
(19, 'novaya-usluga', 'Новая услуга', '<p>вфыв</p>', '/server/uploads/services/service-arrow-slider-prev-1754060508.svg', '', '/service?service=novaya-usluga', '<p>вфыв</p>', 0, 'KZT');

--
-- Триггеры `Services`
--
DELIMITER $$
CREATE TRIGGER `after_services_delete` AFTER DELETE ON `Services` FOR EACH ROW BEGIN
  DELETE FROM `LinksData`
  WHERE source_table = 'Services' AND source_id = OLD.id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_services_delete_to_linksdata` AFTER DELETE ON `Services` FOR EACH ROW BEGIN
  DELETE FROM `LinksData`
  WHERE source_table = 'Services' AND source_id = OLD.id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_services_insert` AFTER INSERT ON `Services` FOR EACH ROW BEGIN
  IF NEW.href IS NOT NULL AND NEW.href != '' THEN
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.href, 'Services', NEW.id)
    ON DUPLICATE KEY UPDATE name=NEW.name, link=NEW.href;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_services_insert_to_linksdata` AFTER INSERT ON `Services` FOR EACH ROW BEGIN
  IF NEW.href IS NOT NULL AND NEW.href != '' THEN
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.href, 'Services', NEW.id)
    ON DUPLICATE KEY UPDATE name=NEW.name, link=NEW.href;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_services_update` AFTER UPDATE ON `Services` FOR EACH ROW BEGIN
  IF NEW.href IS NULL OR NEW.href = '' THEN
    DELETE FROM `LinksData`
    WHERE source_table = 'Services' AND source_id = OLD.id;
  ELSE
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.href, 'Services', NEW.id)
    ON DUPLICATE KEY UPDATE name = VALUES(name), link = VALUES(link);
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_services_update_to_linksdata` AFTER UPDATE ON `Services` FOR EACH ROW BEGIN
  IF NEW.href IS NULL OR NEW.href = '' THEN
    DELETE FROM `LinksData`
    WHERE source_table = 'Services' AND source_id = OLD.id;
  ELSE
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.href, 'Services', NEW.id)
    ON DUPLICATE KEY UPDATE name = VALUES(name), link = VALUES(link);
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `TabsAdditionalProductsData`
--

DROP TABLE IF EXISTS `TabsAdditionalProductsData`;
CREATE TABLE `TabsAdditionalProductsData` (
  `id` int NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `tabs_data` TEXT NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `TabsAdditionalProductsData`
--

INSERT INTO `TabsAdditionalProductsData` (`id`, `product_id`, `tabs_data`, `created_at`) VALUES
(1, 'product_keychain_a93-eco', '{\"ГАРАНТИЯ\": [{\"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим\"}], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"ДИАЛОГОВАЯ ЗАЩИТА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов.\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.\"}, {\"title\": \"SUPER SLAVE (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим.\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений.\"}, {\"title\": \"КОНТРОЛЬ КАНАЛА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования.\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля. <br> Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}, {\"title\": \"3D ДАТЧИК УДАРА И НАКЛОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства.\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}], \"items-service\": [{\"title\": \"ТЕЛЕМАТИКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опциональных <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\\" target=\\\"_blank\\\">GSM</a>,<a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> и наличии опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a>.\"}, {\"title\": \"АВТОЗАПУСК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически.\"}, {\"title\": \"2CAN+2LIN (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.\"}, {\"title\": \"ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.\"}, {\"title\": \"УДАРОПРОЧНЫЙ БРЕЛОК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление предпусковым подогревом\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:32'),
(2, 'product_keychain_e96-eco', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим\"}, {\"title\": \"УМНАЯ АВТОРИЗАЦИЯ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Только владелец получает разрешение на поездку после авторизации по защищенному протоколу через персональную метку или смартфон на iOS и Android с мобильным приложением StarLine.\"}, {\"title\": \"УМНЫЙ ДИАЛОГ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Умный диалоговый код управления c индивидуальными ключами шифрования гарантирует надежную защиту от всех известных кодграбберов\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному узкополосному трансиверу с малошумящим усилителем\"}, {\"title\": \"УМНЫЙ 3D КОНТРОЛЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный 3D-контроль с дистанционной настройкой регистрирует удары, поддомкрачивание и эвакуацию автомобиля и оценивает безопасность вождения\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных прогрессивных технологий и программных решений\"}, {\"title\": \"SUPER SLAVE\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной авторизацией по основному брелку StarLine\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля\"}], \"items-service\": [{\"title\": \"УМНЫЙ КЛИМАТ-КОНТРОЛЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время, по просадке АКБ или дням недели. Комфорт гарантирован\"}, {\"title\": \"60 ГИБКИХ НАСТРОЕК И СЦЕНАРИЕВ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал при постановке на охрану и многое другое для комфорта автовладельца\"}, {\"title\": \"УМНАЯ ЦИФРОВАЯ ИНТЕГРАЦИЯ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"2CAN+4LIN интерфейс обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Экономьте на покупке дополнительного обходчика или дубликате ключа, необходимых для реализации функции автозапуска двигателя. StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера и запуск двигателя по цифровым шинам CAN или LIN*<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:32'),
(3, 's96-2can-2lin-gsm', '{\"ГАРАНТИЯ\": [{\"description\": \"Гарантия 5 лет при условии установки нашим специалистом и соответствующей регистрацией на my.starline.ru\"}], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"ДИАЛОГОВАЯ ЗАЩИТА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.\"}, {\"title\": \"SUPER SLAVE (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим.\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений.\"}, {\"title\": \"КОНТРОЛЬ КАНАЛА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования.\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля. <br><i>Опция доступна при интеграции 2CAN+2LIN интерфейса.</i>\"}, {\"title\": \"3D ДАТЧИК УДАРА И НАКЛОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства.\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}], \"items-service\": [{\"title\": \"ТЕЛЕМАТИКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опциональных <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\\" target=\\\"_blank\\\">GSM</a>,<a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> и наличии опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a>.\"}, {\"title\": \"АВТОЗАПУСК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически.\"}, {\"title\": \"2CAN+2LIN (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.\"}, {\"title\": \"ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.\"}, {\"title\": \"УДАРОПРОЧНЫЙ БРЕЛОК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление с телефона, Умная авторизация по Bluetooth Smart , Управление предпусковым подогревом , Блокировка двигателя по CAN, Умная автодиагностика, Данные о пробеге и уровне топлива\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(4, 'product_keychain_a93-v2', '{\"ГАРАНТИЯ\": [{\"description\": \"Гарантия 5 лет при условии установки нашим специалистом и соответствующей регистрацией на my.starline.ru\"}], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"ДИАЛОГОВАЯ ЗАЩИТА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов.\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.\"}, {\"title\": \"SUPER SLAVE (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений\"}, {\"title\": \"КОНТРОЛЬ КАНАЛА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля <br> <i>Опция доступна при интеграции 2CAN+2LIN интерфейса</i>\"}, {\"title\": \"3D ДАТЧИК УДАРА И НАКЛОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}], \"items-service\": [{\"title\": \"ТЕЛЕМАТИКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опциональных <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\\" target=\\\"_blank\\\">GSM</a>,<a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> и наличии опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a>.\"}, {\"title\": \"АВТОЗАПУСК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически.\"}, {\"title\": \"2CAN+2LIN (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.\"}, {\"title\": \"ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.\"}, {\"title\": \"УДАРОПРОЧНЫЙ БРЕЛОК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление предпусковым подогревом\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(5, 'product_keychain_e96-gsm', '{\"ГАРАНТИЯ\": [{\"description\": \"Гарантия 5 лет при условии установки в нашем авторизованном центре.\"}], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим\"}, {\"title\": \"УМНЫЙ ДИАЛОГ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Умный диалоговый код управления c индивидуальными ключами шифрования гарантирует надежную защиту от всех известных кодграбберов\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех\"}, {\"title\": \"УМНЫЙ 3D КОНТРОЛЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный 3D-контроль с дистанционной настройкой регистрирует удары, поддомкрачивание и эвакуацию автомобиля и оценивает безопасность вождения\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений\"}, {\"title\": \"SUPER SLAVE\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной авторизацией по основному брелку StarLine\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля\"}], \"items-service\": [{\"title\": \"УМНЫЙ КЛИМАТ-КОНТРОЛЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время, по просадке АКБ или дням недели. Комфорт гарантирован\"}, {\"title\": \"ГИБКИЕ НАСТРОЙКИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал при постановке на охрану и многое другое\"}, {\"title\": \"УМНАЯ ЦИФРОВАЯ ИНТЕГРАЦИЯ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"2CAN+4LIN интерфейс обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN\"}, {\"title\": \"ТЕЛЕМАТИКА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управляйте охраной автомобиля из любой точки мира. Передовые технологии GSM-GPRS, GPS-ГЛОНАСС на страже вашего спокойствия\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Экономьте на покупке дополнительного обходчика или дубликате ключа, необходимых для реализации функции автозапуска двигателя. StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера и запуск двигателя по цифровым шинам CAN или LIN*<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управляйте охранными и сервисными функциями, получайте оповещения о статусе охраны на ваш мобильный телефон\"}, {\"title\": \"ВСЕГДА НА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Режим автоматического переключения SIM-карт обеспечивает постоянный контроль транспортного средства, даже в случае потери сигнала одного из сотовых операторов.\"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление с телефона, Бесплатный мониторинг, Умная авторизация по Bluetooth Smart , Управление предпусковым подогревом , Блокировка двигателя по CAN, Умная автодиагностика, Данные о пробеге и уровне топлива\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(6, 'product_keychain_s96-lte', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление с телефона, Бесплатный мониторинг, Умная авторизация по Bluetooth Smart , Управление предпусковым подогревом , Блокировка двигателя по CAN, Умная автодиагностика, Данные о пробеге и уровне топлива\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(7, 'product_keychain_a93-2can-2lin', '{\"ГАРАНТИЯ\": [{\"description\": \"Гарантия 5 лет при условии установки нашим специалистом и соответствующей регистрацией на my.starline.ru\"}], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"ДИАЛОГОВАЯ ЗАЩИТА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу\"}, {\"title\": \"SUPER SLAVE (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений\"}, {\"title\": \"КОНТРОЛЬ КАНАЛА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Автоматический контроль канала связи обеспечивает проверку нахождения брелка в зоне действия приемопередатчика охранного оборудования\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля<br><i>Опция доступна при интеграции 2CAN+2LIN интерфейса</i>\"}, {\"title\": \"3D ДАТЧИК УДАРА И НАКЛОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}], \"items-service\": [{\"title\": \"ТЕЛЕМАТИКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опциональных <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\\" target=\\\"_blank\\\">GSM</a>,<a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> и наличии опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a>.\"}, {\"title\": \"АВТОЗАПУСК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически.\"}, {\"title\": \"2CAN+2LIN (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.\"}, {\"title\": \"ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.\"}, {\"title\": \"УДАРОПРОЧНЫЙ БРЕЛОК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление предпусковым подогревом , Блокировка двигателя по CAN\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(8, 'product_keychain_e96-2can-4lin-gsm', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим\"}, {\"title\": \"УМНАЯ АВТОРИЗАЦИЯ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Только владелец получает разрешение на поездку после авторизации по персональной метке\"}, {\"title\": \"УМНЫЙ ДИАЛОГ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Умный диалоговый код управления c индивидуальными ключами шифрования гарантирует надежную защиту от всех известных кодграбберов\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех\"}, {\"title\": \"УМНЫЙ 3D КОНТРОЛЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный 3D-контроль с дистанционной настройкой регистрирует удары, поддомкрачивание и эвакуацию автомобиля и оценивает безопасность вождения\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений\"}, {\"title\": \"SUPER SLAVE\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной авторизацией по основному брелку StarLine\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля\"}], \"items-service\": [{\"title\": \"УМНЫЙ КЛИМАТ-КОНТРОЛЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время, по просадке АКБ или дням недели. Комфорт гарантирован\"}, {\"title\": \"ГИБКИЕ НАСТРОЙКИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал при постановке на охрану и многое другое\"}, {\"title\": \"УМНАЯ ЦИФРОВАЯ ИНТЕГРАЦИЯ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"2CAN+4LIN интерфейс обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN\"}, {\"title\": \"ТЕЛЕМАТИКА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управляйте охраной автомобиля из любой точки мира. Передовые технологии GSM-GPRS, GPS-ГЛОНАСС на страже вашего спокойствия\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Экономьте на покупке дополнительного обходчика или дубликате ключа, необходимых для реализации функции автозапуска двигателя. StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера и запуск двигателя по цифровым шинам CAN или LIN*<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управляйте охранными и сервисными функциями, получайте оповещения о статусе охраны на ваш мобильный телефон\"}, {\"title\": \"УМНЫЙ БЕСПЛАТНЫЙ МОНИТОРИНГ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего автомобиля, посмотреть маршрут передвижения\"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление с телефона, Бесплатный мониторинг, Умная авторизация по Bluetooth Smart , Управление предпусковым подогревом , Блокировка двигателя по CAN, Умная автодиагностика, Данные о пробеге и уровне топлива\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(9, 'product_keychain_s96-2can-4lin', '{\"ГАРАНТИЯ\": [{\"description\": \"Гарантия 5 лет при условии установки нашим специалистом и соответствующей регистрацией на my.starline.ru\"}], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"ДИАЛОГОВАЯ ЗАЩИТА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов.\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.\"}, {\"title\": \"SUPER SLAVE (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим.\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений.\"}, {\"title\": \"КОНТРОЛЬ КАНАЛА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования.\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля. <br><i>Опция доступна при интеграции 2CAN+2LIN интерфейса</i>\"}, {\"title\": \"3D ДАТЧИК УДАРА И НАКЛОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства.\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}], \"items-service\": [{\"title\": \"ТЕЛЕМАТИКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опциональных <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\\" target=\\\"_blank\\\">GSM</a>,<a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> и наличии опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a>.\"}, {\"title\": \"АВТОЗАПУСК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически.\"}, {\"title\": \"2CAN+2LIN (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.\"}, {\"title\": \"ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.\"}, {\"title\": \"УДАРОПРОЧНЫЙ БРЕЛОК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление с телефона, Бесплатный мониторинг, Умная авторизация по Bluetooth Smart , Управление предпусковым подогревом , Блокировка двигателя по CAN, Умная автодиагностика, Данные о пробеге и уровне топлива\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33');
INSERT INTO `TabsAdditionalProductsData` (`id`, `product_id`, `tabs_data`, `created_at`) VALUES
(10, 'product_keychain_a93-v2-gsm', '{\"ГАРАНТИЯ\": [{\"description\": \"Гарантия 5 лет при условии установки нашим специалистом и соответствующей регистрацией на my.starline.ru\"}], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"ДИАЛОГОВАЯ ЗАЩИТА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов.\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.\"}, {\"title\": \"SUPER SLAVE (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим.\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений.\"}, {\"title\": \"КОНТРОЛЬ КАНАЛА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования.\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля. <br><i>Опция доступна при интеграции 2CAN+2LIN интерфейса</i>\"}, {\"title\": \"3D ДАТЧИК УДАРА И НАКЛОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства.\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}], \"items-service\": [{\"title\": \"ТЕЛЕМАТИКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опциональных <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\\" target=\\\"_blank\\\">GSM</a>,<a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> и наличии опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a>.\"}, {\"title\": \"АВТОЗАПУСК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически.\"}, {\"title\": \"2CAN+2LIN (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.\"}, {\"title\": \"ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.\"}, {\"title\": \"УДАРОПРОЧНЫЙ БРЕЛОК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление с телефона, Управление предпусковым подогревом\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(11, 'product_keychain_a93-2can-2lin-gsm', '{\"ГАРАНТИЯ\": [{\"description\": \"Гарантия 5 лет при условии установки в нашем сервисе.\"}], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"ДИАЛОГОВАЯ ЗАЩИТА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов.\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.\"}, {\"title\": \"SUPER SLAVE\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной авторизацией по брелку StarLine\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим.\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений.\"}, {\"title\": \"КОНТРОЛЬ КАНАЛА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования.\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Дополнительная авторизация надежно защищает автомобиль от угона, усиливая охранные функции штатной сигнализации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля\"}, {\"title\": \"3D ДАТЧИК УДАРА И НАКЛОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства.\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля\"}], \"items-service\": [{\"title\": \"ТЕЛЕМАТИКА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"GSM-GPRS телематический интерфейс позволяет дистанционно управлять охраной вашего автомобиля\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный GSM-интерфейс позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> и наличии опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a>.\"}, {\"title\": \"АВТОЗАПУСК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически.\"}, {\"title\": \"2CAN+2LIN\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Обеспечивает бережную установку и минимальное вмешательство в электронику автомобиля. Постоянно обновляемая библиотека 2CAN+2LIN протоколов содержит самый полный список автомобилей, продающихся на территории России и стран Содружества\"}, {\"title\": \"ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое\"}, {\"title\": \"УДАРОПРОЧНЫЙ БРЕЛОК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Экономьте на покупке дополнительного обходчика или дубликате ключа, необходимых для реализации функции автозапуска двигателя. StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера и запуск двигателя по цифровым шинам CAN или LIN*<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление с телефона, Бесплатный мониторинг, Управление предпусковым подогревом , Блокировка двигателя по CAN\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(12, 'product_keychain_a60', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(13, 'product_keychain_a63-eco-v2', '{\"ГАРАНТИЯ\": [{\"description\": \"Гарантия 5 лет при условии установки нашим специалистом и регистрации на сайте my.starline.ru\"}], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"ДИАЛОГОВАЯ ЗАЩИТА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов.\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.\"}, {\"title\": \"SUPER SLAVE (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим.\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля<br><i>Опция доступна при интеграции 2CAN+2LIN интерфейса</i>\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений.\"}, {\"title\": \"КОНТРОЛЬ КАНАЛА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования.\"}, {\"title\": \"3D ДАТЧИК УДАРА И НАКЛОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства.\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}], \"items-service\": [{\"title\": \"ТЕЛЕМАТИКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опциональных <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\\" target=\\\"_blank\\\">GSM</a>,<a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> и наличии опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a>.\"}, {\"title\": \"АВТОЗАПУСК (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически\"}, {\"title\": \"2CAN+2LIN (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.\"}, {\"title\": \"ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.\"}, {\"title\": \"УДАРОПРОЧНЫЙ БРЕЛОК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Блокировка двигателя по CAN\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(14, 'product_keychain_a63', '{\"ГАРАНТИЯ\": [{\"description\": \"Гарантия 5 лет при условии установки нашим специалистом и регистрации на сайте my.starline.ru\"}], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"ДИАЛОГОВАЯ ЗАЩИТА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов.\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.\"}, {\"title\": \"SUPER SLAVE (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим.\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля<br><i>Опция доступна при интеграции 2CAN+2LIN интерфейса</i>\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений.\"}, {\"title\": \"КОНТРОЛЬ КАНАЛА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования.\"}, {\"title\": \"3D ДАТЧИК УДАРА И НАКЛОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства.\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}], \"items-service\": [{\"title\": \"ТЕЛЕМАТИКА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опциональных <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\\" target=\\\"_blank\\\">GSM</a>,<a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> и наличии опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a>.\"}, {\"title\": \"АВТОЗАПУСК (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически\"}, {\"title\": \"2CAN+2LIN (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.\"}, {\"title\": \"ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.\"}, {\"title\": \"УДАРОПРОЧНЫЙ БРЕЛОК\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(15, 'product_keychain_b97', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": {\"items\": [{\"title\": \"УМНАЯ АВТОРИЗАЦИЯ ПО СМАРТФОНУ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Только владелец получает разрешение на поездку после авторизации по смартфону на платформе iOS или Android с мобильным приложением StarLine по защищенной технологии Bluetooth Smart\"}, {\"title\": \"ЗАЩИТА ОТ РЕТРАНСЛЯЦИИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Надежная защита от перехвата сигнала штатного брелока автомобиля благодаря умной блокировке KeyLess\"}, {\"title\": \"УМНЫЙ ДИАЛОГ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Умный диалоговый код управления c индивидуальными ключами шифрования гарантирует надежную защиту от всех известных кодграбберов\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному узкополосному трансиверу с малошумящим усилителем\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Дополнительная авторизация надежно защищает автомобиль от угона, усиливая охранные функции штатной сигнализации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля\"}, {\"title\": \"ВСТРОЕННЫЙ ЗВУКОВОЙ ИЗВЕЩАТЕЛЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"При реализации функции авторизации по PIN-коду настраивается звуковое оповещение владельца охранного комплекса о необходимости его введения штатными кнопками автомобиля\"}, {\"title\": \"SUPER SLAVE\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией по основному брелку StarLine\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных прогрессивных технологий и программных решений\"}], \"items-service\": [{\"title\": \"АВТОЗАПУСК ПО МУЛЬТИСЦЕНАРИЯМ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время, по просадке АКБ или дням недели. Комфорт гарантирован\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}, {\"title\": \"УМНАЯ ЦИФРОВАЯ ИНТЕГРАЦИЯ 3CAN+4LIN\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"3CAN+4LIN интерфейс обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный GSM-интерфейс позволяет управлять охранными и сервисными функциями комплекса, получать оповещения о статусе охраны на ваш мобильный телефон. 2 SIM-карты разных операторов гарантируют надежную связь\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства\"}, {\"title\": \"ВСЕГДА НА СВЯЗИ\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный SIM-чип МТС обеспечивает высокое качество сотовой связи, устойчивость к экстремальным температурам и вибрациям. Автоматическое переключение с SIM-чип на SIM-карту другого оператора гарантирует надежный прием сотовой связи и постоянный контроль транспортного средства, даже в случае потери сигнала одного из сотовых операторов\"}, {\"title\": \"УМНАЯ АВТОДИАГНОСТИКА\", \"path-icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Экономьте на диагностике транспортного средства — получайте информацию о состоянии автомобиля на мобильный телефон в приложении StarLine или на мониторинговом сайте <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> \"}]}, \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление с телефона, Бесплатный мониторинг, Умная авторизация по Bluetooth Smart , Управление предпусковым подогревом , Блокировка двигателя по CAN, Умная автодиагностика, Данные о пробеге и уровне топлива\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(16, 'product_keychain_a90', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [{\"title\": \"Функции\", \"description\": \"Автозапуск\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\"}], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(17, 'product_remote-controls_a93-a63', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(18, 'product_remote-controls_e96-e66', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(19, 'product_remote-controls_a96-a66', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(20, 'product_remote-controls_b96-b66', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(21, 'product_remote-controls_a91-61', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(22, 'product_remote-controls_d96', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(23, 'product_remote-controls_module-gsm3', '{\"ГАРАНТИЯ\": [{\"description\": \"1 Год при условии установки нашим специалистом.\"}], \"ОПИСАНИЕ\": {\"description\": \"Комплект включает в себя один GSM-модуль StarLine. Модуль предназначен для установки в сигнализации четвертого поколения: StarLine D94, StarLine D64, StarLine B94, StarLine E90 и т.д., включая StarLine A93 и StarLine A63, установив его на свой автомобиль, владелец получает возможность управлять своим транспортным средством на любом удалении от него посредством отправки команд с мобильного телефона и использования специального мобильного приложения StarLine для Android и iPhone.\"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(24, 'product_remote-controls_module-gsm6', '{\"ГАРАНТИЯ\": [{\"description\": \"1 Год при условии установки нашим специалистом.\"}], \"ОПИСАНИЕ\": {\"description\": \"Интеграция GSM-интерфейса StarLine позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на мобильный телефон без ограничения расстояния во всей зоне действия сетей GSM.<br><br>Предназначен для использования в моделях StarLine A66 2CAN+2LIN, StarLine A96 2CAN+2LIN, StarLine B66 2CAN+2LIN, StarLine B96 2CAN+2LIN.\"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(25, 'product_remote-controls_module-gps', '{\"ГАРАНТИЯ\": [{\"description\": \"1 Год при условии установки нашим специалистом.\"}], \"ОПИСАНИЕ\": {\"description\": \"Внешняя GPS/ГЛОНАСС антенна позволяет получить точную координату местоположения автомобиля. Может быть подключена к любой модели сигнализации StarLine при условии наличия GSM модуля. \"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(26, 'product_remote-controls_module-2can-2lin', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": {\"description\": \"\\n           Интерфейсы CAN и LIN обеспечивают наиболее полный функционал на более чем 900 моделей автомобилей,<br><br>\\n           Модули 2CAN, CAN+LIN предназначен для установки в сигнализации четвертого поколения: StarLine D94, StarLine D64, StarLine B94, <a href=\\\"https://caraudio.kg/index.php?route=product/product&path=72_74&product_id=234\\\" target=\\\"_blank\\\">StarLine E90</a> и т.д., включая <a href=\\\"https://caraudio.kg/index.php?route=product/product&path=72_74&product_id=231\\\" target=\\\"_blank\\\">StarLine A93</a> и <a href=\\\"https://caraudio.kg/index.php?route=product/product&path=72_73&product_id=215\\\" target=\\\"_blank\\\">StarLine A63</a>.<br><br> \\n           Для удобной настройки и обновления программного обеспечения модулей 2CAN, CAN+LIN скачайте приложение \\\"Программатор StarLine CAN Телематика\\\" с сайта <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">can.starline.ru</a>.<br><br>\\n\\n           Поддерживаемые функции:\\n           Чтение информации о состоянии концевиков дверей, капота и багажника, педали тормоза и стояночного тормоза, зажигания, работе двигателя.\\n           Управление центральным замком и штатной сигнализацией, аварийной сигнализацией, функцией \\\"комфорт\\\", отпиранием багажника, имитацией открывания двери водителя и запуском двигателя.\\n           Функции SUPER SLAVE и Иммобилайзер с валидатором<br><br>\\n\\n           Список поддерживаемых моделей автоомобилей и функций для каждого автомобиля смотрите на сайте <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">can.starline.ru</a>..\\n           \"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(27, 'product_remote-controls_module-bp-03', '{\"ГАРАНТИЯ\": [{\"description\": \"1 Год при условии установки нашим специалистом.\"}], \"ОПИСАНИЕ\": {\"description\": \"StarLine BP-03 — это модуль, облегчающий установку систем дистанционного или автоматического запуска двигателя на автомобили, оборудованные системой RFID (radio frequency identification). Система RFID применяется на большинстве современных автомобилей. В штатный ключ зажигания автомобиля встроен транспондер, код которого опрашивается при пуске двигателя ключом. При дистанционном или автоматическом запуске двигателя эта система не даст двигателю запуститься. Для решения этой проблемы и предназначен модуль ВР-03, автоматически передающий код штатного транспондера при дистанционном запуске двигателя.\"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(28, 'product_remote-controls_sd-card', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(29, 'product_remote-controls_20w', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": {\"description\": \"Данная модель сирены прекрасно подойдет к любой автосигнализации. Имеет 6 тонов звучания и мощность 20 W.\"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(30, 'product_remote-controls_sheriff', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": {\"description\": \"Sheriff - аналоговый доводчик  четырех стекол, предназначен для подключения к автомобилям, оборудованными обычными (аналоговыми) стеклоподъемниками. Как правило, данное оборудование устанавливается вместе с автосигналлизацией.\"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(31, 'product_remote-controls_push-motor', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": {\"description\": \"Моторчик-толкатель центрального замка предназначен для ремонта систем центральных замков, а также для установки в автомобили, не оборудованные системой центрального замка, либо имеющие не полный центрозамок, например в 3 из 4 дверей.\"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(32, 'product_park-systems_cj-178', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": {\"description\": \"\\n           <ul>\\n             <li>Матрица Color CCD PC3030</li>\\n             <li>Разрешение: 628x586 пикселей</li>\\n             <li>Количество ТВ-линий: 420</li> \\n             <li>Формат видеосигнала: PAL/NTSC</li>\\n             <li>Минимальная освещенность: 0.1lUX/F=1.2(OLux with ON)</li>\\n             <li>Цветная</li>\\n             <li>Инфракрасная подсветка: 4 светодиода</li>\\n             <li>Баланс белого: автоматический</li>\\n             <li>Угол обзора - 140 градусов</li>\\n             <li>Зеркальная: да</li>\\n             <li>Способ монтажа: накладная</li>\\n             <li>Регулировка угла наклона: да (без регулировки угла наклона для модели CJ-188)</li>\\n             <li>Разъем для подключения видео RCA (колокольчик, тюльпан)</li>\\n             <li>Питание: 12 В</li>\\n             <li>Рабочий диапазон температур: -20...+70</li>\\n             <li>Класс защиты от влаги и пыли: IP 67 (водонепронецаемая)</li>\\n             <li>Материал корпуса: пластик</li>\\n           </ul>\\n           \"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(33, 'product_park-systems_cj-188', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": {\"description\": \"\\n           <ul>\\n             <li>Матрица Color CCD PC3030</li>\\n             <li>Разрешение: 628x586 пикселей</li>\\n             <li>Количество ТВ-линий: 420</li> \\n             <li>Формат видеосигнала: PAL/NTSC</li>\\n             <li>Минимальная освещенность: 0.1lUX/F=1.2(OLux with ON)</li>\\n             <li>Цветная</li>\\n             <li>Инфракрасная подсветка: 4 светодиода</li>\\n             <li>Баланс белого: автоматический</li>\\n             <li>Угол обзора - 140 градусов</li>\\n             <li>Зеркальная: да</li>\\n             <li>Способ монтажа: накладная</li>\\n             <li>Регулировка угла наклона: да (без регулировки угла наклона для модели CJ-188)</li>\\n             <li>Разъем для подключения видео RCA (колокольчик, тюльпан)</li>\\n             <li>Питание: 12 В</li>\\n             <li>Рабочий диапазон температур: -20...+70</li>\\n             <li>Класс защиты от влаги и пыли: IP 67 (водонепронецаемая)</li>\\n             <li>Материал корпуса: пластик</li>\\n           </ul>\\n           \"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(34, 'product_park-systems_camera-prado150', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:33'),
(35, 'product_park-systems_number-plate-with-camera', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": {\"description\": \"\\n           <ul>\\n             <li>Матрица: 1/4\\\" Color CMOS</li>\\n             <li>Инфракрасная подсветка: 4шт светодиода</li>\\n             <li>Широкий угол обзора: 170°</li>\\n             <li>Разрешение изображения: 420 ТВ линий</li>\\n             <li>Система цветности PAL/NTSC</li>\\n             <li>Напряжение: DC 12V</li>\\n             <li>Водонепроницаемый стандарт: IP68</li>\\n             <li>Цвет рамки и камеры: Черный </li>\\n           </ul>\\n           \"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:34'),
(36, 'product_park-systems_camera-c1100', '{\"ГАРАНТИЯ\": [{\"description\": \"1 год при условии установки нашим специалистом\"}], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:34'),
(37, 'product_park-systems_camera-cmos130', '{\"ГАРАНТИЯ\": [{\"description\": \"1 год при условии установки нашим специалистом\"}], \"ОПИСАНИЕ\": {\"description\": \"Видеокамера Kenwood CMOS-130 является универсальной и может использоваться как камера заднего и переднего вида. Камера имеет широкоугольный объектив, который не позволит упустить ни единого момента. Дизайн камеры сдержанный и подойдет к любому автомобилю. Одной из особенностей камеры является наличие водонепроницаемого корпуса, что обезопасит её от промокания. Диапазон освещения у данной модели - от 0,9 до 100000 люкс. В комплектации данного товара отсутствует 5-ти метровый удлиняющий кабель, который был в наличии у модели CMOS-230. Переключение заднего вида осуществляется за счет сигнала реверса. Углы обзора у камеры: горизонтальный - 128 °, вертикальный - 103 °. Данная универсальная видеокамера является примером прекрасного соотношения цена/качество. Приятный дизайн, высокое качество и функциональность говорят сами за себя.\"}, \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:34'),
(38, 'product_park-systems_camera-ccd', '{\"ГАРАНТИЯ\": [], \"ОПИСАНИЕ\": [], \"ХАРАКТЕРИСТИКИ\": [], \"СОПУТСТВУЮЩИЕ ТОВАРЫ\": []}', '2025-07-19 01:23:34'),
(39, 'product_keychain_733794ff-0b39-4465-b5de-f6e58e3f338e', '[{\"title\": \"Новая вкладка\", \"description\": [{\"title\": \"Новый элемент\", \"path-icon\": \"/uploads/tabs/product_keychain_733794ff-0b39-4465-b5de-f6e58e3f338e/icon_687e0d233431e2.80511755.avif\", \"description\": \"\"}]}]', '2025-07-21 09:43:26'),
(43, 'product_keychain_176280a0-ac34-415f-ad1c-018f9fa56633', '[]', '2025-08-02 20:34:37');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `role` enum('user','admin','moderator') DEFAULT 'user'
);

-- --------------------------------------------------------

--
-- Структура таблицы `Videos_intro_slider`
--

DROP TABLE IF EXISTS `Videos_intro_slider`;
CREATE TABLE `Videos_intro_slider` (
  `id` int NOT NULL,
  `video_filename` varchar(255) NOT NULL,
  `video_path` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `advantages` TEXT NOT NULL,
  `button_text` varchar(100) NOT NULL,
  `button_link` varchar(255) NOT NULL,
  `position` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `poster_path` varchar(255) NOT NULL DEFAULT '',
  `video_path_mob` varchar(255) NOT NULL DEFAULT ''
);

--
-- Дамп данных таблицы `Videos_intro_slider`
--

INSERT INTO `Videos_intro_slider` (`id`, `video_filename`, `video_path`, `title`, `advantages`, `button_text`, `button_link`, `position`, `created_at`, `updated_at`, `poster_path`, `video_path_mob`) VALUES
(24, 'video-1752760786.mp4', '/server/uploads/slider-intro/video-1752760786.mp4', 'Новый слайд', '[]', 'Подробнее', '#', 2, '2025-07-17 10:31:15', '2025-07-17 15:56:11', '/server/uploads/slider-intro/posters/poster-1752767771.avif', ''),
(32, 'video-1752769781.mp4', '/server/uploads/slider-intro/video-1752769781.mp4', '222d', '[\"czxczxc\", \"czxcxzcxzc\", \"cxzcxzczxcxzc\"]', 'Подробнее', '#', 1, '2025-07-17 13:56:12', '2025-07-17 16:30:35', '/server/uploads/slider-intro/posters/poster-1752767085.avif', ''),
(33, 'video-1752768367.mp4', '/server/uploads/slider-intro/video-1752768367.mp4', 'Новый слай22д', '[]', 'Подробнее', '#', 3, '2025-07-17 16:01:57', '2025-07-17 16:08:20', '', '/server/uploads/slider-intro/video-1752768367_mob.webm'),
(34, 'video-1752768668.mp4', '/server/uploads/slider-intro/video-1752768668.mp4', 'Новый слайд', '[]', 'Подробнее', '#', 4, '2025-07-17 16:11:00', '2025-07-17 16:11:39', '', '/server/uploads/slider-intro/video-1752768668_mob.webm'),
(35, '', '', 'Новый слайд', '[]', 'Подробнее', '#', 5, '2025-07-17 16:45:45', '2025-07-17 16:45:45', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `Works`
--

DROP TABLE IF EXISTS `Works`;
CREATE TABLE `Works` (
  `work_id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT 'Заголовок для элемента, если есть',
  `link` varchar(255) DEFAULT NULL COMMENT 'Ссылка на страницу',
  `image_path` varchar(255) DEFAULT NULL COMMENT 'Путь к изображению',
  `position` int DEFAULT NULL COMMENT 'Порядок сортировки',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Дамп данных таблицы `Works`
--

INSERT INTO `Works` (`work_id`, `title`, `link`, `image_path`, `position`, `created_at`, `updated_at`) VALUES
(1, 'УСТАНОВКА И РЕМОНТ АВТОСИГНАЛИЗАЦИЙ', '/service?service=setup', '/server/uploads/works/images/works_6888de398ded57.48841112.webp', 2, '2025-07-28 12:28:59', '2025-07-29 14:44:09'),
(3, 'РЕМОНТ ЦЕНТРОЗАМКОВ', '/service?service=locks', '/client/images/works/locks.avif', 4, '2025-07-28 12:28:59', '2025-07-29 14:51:52'),
(5, 'УСТАНОВКА СИСТЕМ ПАРКИНГА', '/service?service=setup-system-parking', '/client/images/works/system-parking.avif', 5, '2025-07-28 12:28:59', '2025-07-29 14:51:52'),
(7, 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА', '/service?service=diagnostic', '/client/images/works/diagnostic.avif', 7, '2025-07-28 12:28:59', '2025-07-29 14:51:52'),
(8, 'ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ', '/service?service=disabled-autosynal', '/client/images/works/disabled-autosynal.avif', 6, '2025-07-28 12:28:59', '2025-07-29 14:51:52'),
(9, 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ И АНТИРАДАРОВ', '/service?service=setup-videoregistration', '/client/images/works/setup-videoregistration.avif', 8, '2025-07-28 12:28:59', '2025-07-29 14:51:52');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `AboutUs`
--
ALTER TABLE `AboutUs`
  ADD PRIMARY KEY (`about_us_id`),
  ADD KEY `idx_about_us_type` (`type`);

--
-- Индексы таблицы `add_services`
--
ALTER TABLE `add_services`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Advantage`
--
ALTER TABLE `Advantage`
  ADD PRIMARY KEY (`advantage_id`);

--
-- Индексы таблицы `AdvantageVideos`
--
ALTER TABLE `AdvantageVideos`
  ADD PRIMARY KEY (`video_id`);

--
-- Индексы таблицы `AdvantageVideoSources`
--
ALTER TABLE `AdvantageVideoSources`
  ADD PRIMARY KEY (`source_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Индексы таблицы `Contacts`
--
ALTER TABLE `Contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `idx_contact_type` (`type`);

--
-- Индексы таблицы `Footer`
--
ALTER TABLE `Footer`
  ADD PRIMARY KEY (`footer_id`),
  ADD UNIQUE KEY `source_unique` (`source_table`,`source_id`);

--
-- Индексы таблицы `LinksData`
--
ALTER TABLE `LinksData`
  ADD PRIMARY KEY (`links_data_id`),
  ADD UNIQUE KEY `source_unique` (`source_table`,`source_id`);

--
-- Индексы таблицы `Navigation`
--
ALTER TABLE `Navigation`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Prices`
--
ALTER TABLE `Prices`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Sections-Product`
--
ALTER TABLE `Sections-Product`
  ADD PRIMARY KEY (`section_product_id`);

--
-- Индексы таблицы `Sertificates`
--
ALTER TABLE `Sertificates`
  ADD PRIMARY KEY (`sertificate_id`);

--
-- Индексы таблицы `Services`
--
ALTER TABLE `Services`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `TabsAdditionalProductsData`
--
ALTER TABLE `TabsAdditionalProductsData`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `Videos_intro_slider`
--
ALTER TABLE `Videos_intro_slider`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Works`
--
ALTER TABLE `Works`
  ADD PRIMARY KEY (`work_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `AboutUs`
--
ALTER TABLE `AboutUs`
  MODIFY `about_us_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `add_services`
--
ALTER TABLE `add_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Advantage`
--
ALTER TABLE `Advantage`
  MODIFY `advantage_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `AdvantageVideos`
--
ALTER TABLE `AdvantageVideos`
  MODIFY `video_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `AdvantageVideoSources`
--
ALTER TABLE `AdvantageVideoSources`
  MODIFY `source_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `Contacts`
--
ALTER TABLE `Contacts`
  MODIFY `contact_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT для таблицы `Footer`
--
ALTER TABLE `Footer`
  MODIFY `footer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `LinksData`
--
ALTER TABLE `LinksData`
  MODIFY `links_data_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT для таблицы `Navigation`
--
ALTER TABLE `Navigation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `Prices`
--
ALTER TABLE `Prices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `Sections-Product`
--
ALTER TABLE `Sections-Product`
  MODIFY `section_product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `Sertificates`
--
ALTER TABLE `Sertificates`
  MODIFY `sertificate_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `Services`
--
ALTER TABLE `Services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `TabsAdditionalProductsData`
--
ALTER TABLE `TabsAdditionalProductsData`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Videos_intro_slider`
--
ALTER TABLE `Videos_intro_slider`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `Works`
--
ALTER TABLE `Works`
  MODIFY `work_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `AdvantageVideoSources`
--
ALTER TABLE `AdvantageVideoSources`
  ADD CONSTRAINT `advantagevideosources_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `AdvantageVideos` (`video_id`) ON DELETE CASCADE;
COMMIT;






