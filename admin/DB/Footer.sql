USE `autosygnals`;

-- --------------------------------------------------------
-- Создание таблицы Footer для хранения всех ссылок футера
-- Теперь она зависит от LinksData, а не от каждой таблицы по отдельности.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `Footer`;
CREATE TABLE `Footer` (
  `footer_id` INT AUTO_INCREMENT PRIMARY KEY,
  `section` ENUM('shop', 'installation', 'client') NOT NULL COMMENT 'Раздел футера (Магазин, Установка, Клиенту)',
  `name` VARCHAR(255) NOT NULL COMMENT 'Название ссылки',
  `link` VARCHAR(255) NOT NULL COMMENT 'URL ссылки',
  `position` INT NULL COMMENT 'Порядок сортировки',
  `visible` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Видимость ссылки (1 - видна, 0 - скрыта)',
  `source_table` ENUM('LinksData', 'custom') NULL COMMENT 'Источник данных (для синхронизации)',
  `source_id` INT NULL COMMENT 'ID из исходной таблицы (links_data_id или null для custom)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  -- Уникальный ключ, чтобы избежать дублирования записей из исходных таблиц
  UNIQUE KEY `source_unique` (`source_table`, `source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------
-- Первоначальное заполнение таблицы статическими ссылками
-- --------------------------------------------------------

-- Динамические ссылки теперь добавляются из LinksData,
-- а здесь остаются только пользовательские (custom)
INSERT IGNORE INTO `Footer` (section, name, link, position, source_table) VALUES
-- Раздел 'shop'
('shop', 'Прайс на материал и установку', '/price', 99, 'custom'),
-- Раздел 'installation'
('installation', 'Прайс на услуги', '/price', 99, 'custom'),
-- Раздел 'client'
('client', 'Специальные предложения', '/special', 1, 'custom'),
('client', 'Корзина заказа', '/cart', 2, 'custom'),
('client', 'Оставить отзыв', 'https://2gis.kz/almaty/geo/70000001027313872', 3, 'custom'),
('client', 'Архив выполненных работ', 'https://drive.google.com/drive/folders/1gRjuirVES2pO6EMTNDrL5KNGC4RfBRPb', 4, 'custom'),
('client', 'Как к нам добраться', '/contacts#location', 5, 'custom'),
('client', 'Наши сертификаты', '/sertificates', 6, 'custom'),
('client', 'Оплата и доставка', '#', 7, 'custom');
