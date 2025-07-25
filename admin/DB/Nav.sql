-- Создание таблицы Main_Nav
CREATE TABLE IF NOT EXISTS `Main_Nav` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `link` VARCHAR(255) NOT NULL,
  `icon_path` VARCHAR(255) DEFAULT NULL,
  `order` INT(11) DEFAULT 0,
  `isActive` TINYINT(1) DEFAULT '1',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Вставка данных из JSON файла
INSERT INTO `Main_Nav` (`id`, `title`, `link`, `icon_path`, `order`, `isActive`, `created_at`, `updated_at`) VALUES
(1, 'Home', '/', '', 1, 1, '2025-01-25 10:00:00', '2025-01-25 10:00:00'),
(2, 'About', '/about', '', 2, 1, '2025-01-25 10:00:00', '2025-01-25 10:00:00'),
(3, 'Contact', '/contact', '', 3, 1, '2025-01-25 10:00:00', '2025-01-25 10:00:00'),
(4, 'Services', '/services', '', 4, 1, '2025-01-25 10:00:00', '2025-01-25 10:00:00'),
(5, 'Products', '/products', '', 5, 1, '2025-01-25 10:00:00', '2025-01-25 10:00:00'),
(6, 'Blog', '/blog', '', 6, 1, '2025-01-25 10:00:00', '2025-01-25 10:00:00'),
(7, 'Contact', '/contact', '', 7, 1, '2025-01-25 10:00:00', '2025-01-25 10:00:00');