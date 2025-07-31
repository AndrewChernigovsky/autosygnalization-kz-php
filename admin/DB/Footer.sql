USE `autosygnals`;

-- --------------------------------------------------------
-- Создание таблицы Footer для хранения всех ссылок футера
-- --------------------------------------------------------
CREATE TABLE `Footer` (
  `footer_id` INT AUTO_INCREMENT PRIMARY KEY,
  `section` ENUM('shop', 'installation', 'client') NOT NULL COMMENT 'Раздел футера (Магазин, Установка, Клиенту)',
  `name` VARCHAR(255) NOT NULL COMMENT 'Название ссылки',
  `link` VARCHAR(255) NOT NULL COMMENT 'URL ссылки',
  `position` INT NULL COMMENT 'Порядок сортировки',
  `visible` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Видимость ссылки (1 - видна, 0 - скрыта)',
  `source_table` ENUM('Sections-Product', 'Services', 'custom') NULL COMMENT 'Источник данных (для синхронизации)',
  `source_id` INT NULL COMMENT 'ID из исходной таблицы (для синхронизации)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  -- Уникальный ключ, чтобы избежать дублирования записей из исходных таблиц
  UNIQUE KEY `source_unique` (`source_table`, `source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Триггеры для таблицы Sections-Product
-- --------------------------------------------------------

-- Триггер на ДОБАВЛЕНИЕ записи в Sections-Product
DELIMITER $$
CREATE TRIGGER `after_sections_product_insert`
AFTER INSERT ON `Sections-Product`
FOR EACH ROW
BEGIN
  INSERT INTO `Footer` (section, name, link, position, visible, source_table, source_id)
  VALUES ('shop', NEW.name, NEW.link, NEW.position, TRUE, 'Sections-Product', NEW.section_product_id)
  ON DUPLICATE KEY UPDATE name=NEW.name, link=NEW.link, position=NEW.position;
END$$
DELIMITER ;

-- Триггер на ОБНОВЛЕНИЕ записи в Sections-Product
DELIMITER $$
CREATE TRIGGER `after_sections_product_update`
AFTER UPDATE ON `Sections-Product`
FOR EACH ROW
BEGIN
  UPDATE `Footer`
  SET
    name = NEW.name,
    link = NEW.link,
    position = NEW.position
  WHERE source_table = 'Sections-Product' AND source_id = NEW.section_product_id;
END$$
DELIMITER ;

-- Триггер на УДАЛЕНИЕ записи из Sections-Product
DELIMITER $$
CREATE TRIGGER `after_sections_product_delete`
AFTER DELETE ON `Sections-Product`
FOR EACH ROW
BEGIN
  DELETE FROM `Footer`
  WHERE source_table = 'Sections-Product' AND source_id = OLD.section_product_id;
END$$
DELIMITER ;

-- --------------------------------------------------------
-- Триггеры для таблицы Services
-- --------------------------------------------------------

-- Триггер на ДОБАВЛЕНИЕ записи в Services
DELIMITER $$
CREATE TRIGGER `after_services_insert`
AFTER INSERT ON `Services`
FOR EACH ROW
BEGIN
  INSERT INTO `Footer` (section, name, link, position, visible, source_table, source_id)
  VALUES ('installation', NEW.name, NEW.href, NEW.id, TRUE, 'Services', NEW.id)
  ON DUPLICATE KEY UPDATE name=NEW.name, link=NEW.href, position=NEW.id;
END$$
DELIMITER ;

-- Триггер на ОБНОВЛЕНИЕ записи в Services
DELIMITER $$
CREATE TRIGGER `after_services_update`
AFTER UPDATE ON `Services`
FOR EACH ROW
BEGIN
  UPDATE `Footer`
  SET
    name = NEW.name,
    link = NEW.href
  WHERE source_table = 'Services' AND source_id = NEW.id;
END$$
DELIMITER ;

-- Триггер на УДАЛЕНИЕ записи из Services
DELIMITER $$
CREATE TRIGGER `after_services_delete`
AFTER DELETE ON `Services`
FOR EACH ROW
BEGIN
  DELETE FROM `Footer`
  WHERE source_table = 'Services' AND source_id = OLD.id;
END$$
DELIMITER ;

-- --------------------------------------------------------
-- Первоначальное заполнение таблицы
-- --------------------------------------------------------

-- Заполнение данными из Sections-Product
-- Используем IGNORE, чтобы пропустить дубликаты, если они уже есть
INSERT IGNORE INTO `Footer` (section, name, link, position, source_table, source_id)
SELECT 'shop', name, link, position, 'Sections-Product', section_product_id
FROM `Sections-Product`;

-- Заполнение данными из Services
INSERT IGNORE INTO `Footer` (section, name, link, position, source_table, source_id)
SELECT 'installation', name, href, id, 'Services', id
FROM `Services`;

-- Добавление статических ссылок
-- Используем IGNORE, чтобы не было ошибки, если такие записи уже есть
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
