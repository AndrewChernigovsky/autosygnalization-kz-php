-- Таблица контактов для хранения телефонных номеров
-- Содержит основную информацию о контактах с временными метками
CREATE TABLE `Contacts` (
  `contact_id` INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор контакта
  `type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,  -- Тип контакта (например: "main-phone", "email")
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,  -- Название/тип контакта (например: "Основной", "Запасной")
  `content` varchar(255) COLLATE utf8mb4_general_ci NULL,  -- Номер телефона в читаемом формате
  `link` varchar(255) COLLATE utf8mb4_general_ci NULL,   -- Ссылка для звонка (tel: формат)
  `icon_path` varchar(255) COLLATE utf8mb4_general_ci NULL,   -- Ссылка для иконки
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,     -- Время создания записи
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- Время последнего обновления
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Создание индекса ПОСЛЕ создания таблицы
CREATE INDEX idx_contact_type ON `Contacts` (`type`);

-- Начальные данные: добавление основных контактов
INSERT INTO `Contacts` (`type`,`title`, `content`, `link`,`icon_path`) VALUES
('main-phone', 'Основной', '+7 707 747 8212', 'tel:+77077478212',  NULL),
('main-phone', 'Запасной', '+7 701 747 8212', 'tel:+77017478212',  NULL),
('address', 'Адрес:', 'Казахстан, г.Алматы,<br/> пр.Абая 145/г, бокс №15', 'https://2gis.kz/almaty/geo/70000001027313872','/client/vectors/sprite.svg#geo'),
('contact-phone', 'BEELINE:', '+770 774 8212', 'tel:+7707748212',  '/client/vectors/phone-no-border.svg'),
('contact-phone', 'KCELL:', '+770 174 8212', 'tel:+7701748212',  '/client/vectors/phone-no-border.svg'),
('social','Whatsapp:','+77077478212','https://wa.me/77077478212','/client/vectors/whatsapp.svg'),
('social','Instagramm:','+77077478212','https://www.instagram.com/autosecurity_kz','/client/vectors/sprite.svg#instagramm-icon'),
('email','Почта:','autosecurity.site@mail.ru','mailto:autosecurity.site@mail.ru','/client/vectors/message-icon.svg'),
('schedule','График работы:','Вс. - Чт.: 9:30 - 18:00 <br> Пт.: 9:30-15:00 <br> <span>Сб.: Выходной</span>',NULL,'/client/vectors/clock.svg'),
('map','Карта',NULL,'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1453.4679397503296!2d76.8722813!3d43.231804!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3883693b733bff39%3A0x716633e11986b3f8!2sAuto%20Security!5e0!3m2!1sru!2sru!4v1735233649305!5m2!1sru!2sru',NULL),
('location-description','КАК К НАМ ДОБРАТЬСЯ','Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, <br> перед речкой поворот направо, заезжаем на территорию СТО. <br> Наш бокс №15.',NULL,NULL),
('site', 'Сайт:', 'www.starline-service.kz', 'https://starline-service.kz/', NULL)

