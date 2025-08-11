USE `autosygnals`;

DROP TABLE IF EXISTS `LinksData`;

CREATE TABLE `LinksData` (
  `links_data_id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL COMMENT 'Название ссылки',
  `link` VARCHAR(255) NOT NULL COMMENT 'URL ссылки',
  `source_table` ENUM('Sections-Product', 'Services', 'Contacts') NULL COMMENT 'Источник данных (для синхронизации)',
  `source_id` INT NULL COMMENT 'ID из исходной таблицы (для синхронизации)',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `source_unique` (`source_table`, `source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Триггеры для таблицы Sections-Product
-- --------------------------------------------------------

-- Триггер на ДОБАВЛЕНИЕ записи в Sections-Product
DROP TRIGGER IF EXISTS `after_sections_product_insert_to_linksdata`;
DELIMITER $$
CREATE TRIGGER `after_sections_product_insert_to_linksdata`
AFTER INSERT ON `Sections-Product`
FOR EACH ROW
BEGIN
  IF NEW.link IS NOT NULL AND NEW.link != '' THEN
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.link, 'Sections-Product', NEW.section_product_id)
    ON DUPLICATE KEY UPDATE name=NEW.name, link=NEW.link;
  END IF;
END$$
DELIMITER ;

-- Триггер на ОБНОВЛЕНИЕ записи в Sections-Product
DROP TRIGGER IF EXISTS `after_sections_product_update_to_linksdata`;
DELIMITER $$
CREATE TRIGGER `after_sections_product_update_to_linksdata`
AFTER UPDATE ON `Sections-Product`
FOR EACH ROW
BEGIN
  IF NEW.link IS NULL OR NEW.link = '' THEN
    DELETE FROM `LinksData`
    WHERE source_table = 'Sections-Product' AND source_id = OLD.section_product_id;
  ELSE
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.link, 'Sections-Product', NEW.section_product_id)
    ON DUPLICATE KEY UPDATE name = VALUES(name), link = VALUES(link);
  END IF;
END$$
DELIMITER ;

-- Триггер на УДАЛЕНИЕ записи из Sections-Product
DROP TRIGGER IF EXISTS `after_sections_product_delete_to_linksdata`;
DELIMITER $$
CREATE TRIGGER `after_sections_product_delete_to_linksdata`
AFTER DELETE ON `Sections-Product`
FOR EACH ROW
BEGIN
  DELETE FROM `LinksData`
  WHERE source_table = 'Sections-Product' AND source_id = OLD.section_product_id;
END$$
DELIMITER ;

-- --------------------------------------------------------
-- Триггеры для таблицы Services
-- --------------------------------------------------------

-- Триггер на ДОБАВЛЕНИЕ записи в Services
DROP TRIGGER IF EXISTS `after_services_insert_to_linksdata`;
DELIMITER $$
CREATE TRIGGER `after_services_insert_to_linksdata`
AFTER INSERT ON `Services`
FOR EACH ROW
BEGIN
  IF NEW.href IS NOT NULL AND NEW.href != '' THEN
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.href, 'Services', NEW.id)
    ON DUPLICATE KEY UPDATE name=NEW.name, link=NEW.href;
  END IF;
END$$
DELIMITER ;

-- Триггер на ОБНОВЛЕНИЕ записи в Services
DROP TRIGGER IF EXISTS `after_services_update_to_linksdata`;
DELIMITER $$
CREATE TRIGGER `after_services_update_to_linksdata`
AFTER UPDATE ON `Services`
FOR EACH ROW
BEGIN
  IF NEW.href IS NULL OR NEW.href = '' THEN
    DELETE FROM `LinksData`
    WHERE source_table = 'Services' AND source_id = OLD.id;
  ELSE
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.name, NEW.href, 'Services', NEW.id)
    ON DUPLICATE KEY UPDATE name = VALUES(name), link = VALUES(link);
  END IF;
END$$
DELIMITER ;

-- Триггер на УДАЛЕНИЕ записи из Services
DROP TRIGGER IF EXISTS `after_services_delete_to_linksdata`;
DELIMITER $$
CREATE TRIGGER `after_services_delete_to_linksdata`
AFTER DELETE ON `Services`
FOR EACH ROW
BEGIN
  DELETE FROM `LinksData`
  WHERE source_table = 'Services' AND source_id = OLD.id;
END$$
DELIMITER ;

-- --------------------------------------------------------
-- Триггеры для таблицы Contacts
-- --------------------------------------------------------

-- Триггер на ДОБАВЛЕНИЕ записи в Contacts
DROP TRIGGER IF EXISTS `after_contacts_insert_to_linksdata`;
DELIMITER $$
CREATE TRIGGER `after_contacts_insert_to_linksdata`
AFTER INSERT ON `Contacts`
FOR EACH ROW
BEGIN
  IF NEW.link IS NOT NULL AND NEW.link != '' THEN
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.title, NEW.link, 'Contacts', NEW.contact_id)
    ON DUPLICATE KEY UPDATE name=NEW.title, link=NEW.link;
  END IF;
END$$
DELIMITER ;

-- Триггер на ОБНОВЛЕНИЕ записи в Contacts
DROP TRIGGER IF EXISTS `after_contacts_update_to_linksdata`;
DELIMITER $$
CREATE TRIGGER `after_contacts_update_to_linksdata`
AFTER UPDATE ON `Contacts`
FOR EACH ROW
BEGIN
  IF NEW.link IS NULL OR NEW.link = '' THEN
    DELETE FROM `LinksData`
    WHERE source_table = 'Contacts' AND source_id = OLD.contact_id;
  ELSE
    INSERT INTO `LinksData` (name, link, source_table, source_id)
    VALUES (NEW.title, NEW.link, 'Contacts', NEW.contact_id)
    ON DUPLICATE KEY UPDATE name = VALUES(name), link = VALUES(link);
  END IF;
END$$
DELIMITER ;

-- Триггер на УДАЛЕНИЕ записи из Contacts
DROP TRIGGER IF EXISTS `after_contacts_delete_to_linksdata`;
DELIMITER $$
CREATE TRIGGER `after_contacts_delete_to_linksdata`
AFTER DELETE ON `Contacts`
FOR EACH ROW
BEGIN
  DELETE FROM `LinksData`
  WHERE source_table = 'Contacts' AND source_id = OLD.contact_id;
END$$
DELIMITER ;

-- --------------------------------------------------------
-- Триггеры для синхронизации LinksData -> Footer
-- --------------------------------------------------------

-- Триггер на ОБНОВЛЕНИЕ записи в LinksData
-- Обновляет соответствующую запись в Footer, если она там есть
DROP TRIGGER IF EXISTS `after_linksdata_update_to_footer`;
DELIMITER $$
CREATE TRIGGER `after_linksdata_update_to_footer`
AFTER UPDATE ON `LinksData`
FOR EACH ROW
BEGIN
    UPDATE `Footer`
    SET
        name = NEW.name,
        link = NEW.link
    WHERE source_table = 'LinksData' AND source_id = NEW.links_data_id;
END$$
DELIMITER ;

-- Триггер на УДАЛЕНИЕ записи из LinksData
-- Удаляет соответствующую запись из Footer, если она там есть
DROP TRIGGER IF EXISTS `after_linksdata_delete_to_footer`;
DELIMITER $$
CREATE TRIGGER `after_linksdata_delete_to_footer`
AFTER DELETE ON `LinksData`
FOR EACH ROW
BEGIN
    DELETE FROM `Footer`
    WHERE source_table = 'LinksData' AND source_id = OLD.links_data_id;
END$$
DELIMITER ;


-- Первоначальное заполнение таблицы
-- --------------------------------------------------------

-- Заполнение данными из Sections-Product
-- Используем IGNORE, чтобы пропустить дубликаты, если они уже есть
-- Добавлено условие WHERE для фильтрации пустых ссылок
INSERT IGNORE INTO `LinksData` (name, link, source_table, source_id)
SELECT name, link, 'Sections-Product', section_product_id
FROM `Sections-Product`
WHERE link IS NOT NULL AND link != '';

-- Заполнение данными из Services
-- Добавлено условие WHERE для фильтрации пустых ссылок
INSERT IGNORE INTO `LinksData` (name, link, source_table, source_id)
SELECT name, href, 'Services', id
FROM `Services`
WHERE href IS NOT NULL AND href != '';

-- Заполнение данными из Contacts
-- Добавлено условие WHERE для фильтрации пустых ссылок
INSERT IGNORE INTO `LinksData` (name, link, source_table, source_id)
SELECT title, link, 'Contacts', contact_id
FROM `Contacts`
WHERE link IS NOT NULL AND link != '';
