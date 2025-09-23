USE `autosygnals`;

-- Comments explaining the purpose of each column have been added for clarity.
CREATE TABLE `Sections-Product` (
  `section_product_id` INT AUTO_INCREMENT PRIMARY KEY,
  `type` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Тип секции',
  `link` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Ссылка на секцию',
  `name` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Название секции',
  `count` int(11) NULL COMMENT 'Количество товаров в секции',
  `src` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Ссылка на изображение',
  `position` int(11) NULL COMMENT 'Порядок сортировки',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





INSERT INTO `Sections-Product` (`type`, `link`, `name`, `count`, `src`, `position`) VALUES
('auto', '/autosygnal?SELECT=name&type=auto', 'Автосигнализации с автозапуском', 0, '/client/images/autosygnals/autosygnals-1.avif', 1),
('gsm', '/autosygnal?SELECT=name&type=gsm', 'Автосигнализации с GSM', 0, '/client/images/autosygnals/autosygnals-2.avif', 2),
('without-auto', '/autosygnal?SELECT=name&ype=without-auto', 'Автосигнализации без автозапуска', 0, '/client/images/autosygnals/autosygnals-3.avif', 3),
('starline', '/autosygnal?SELECT=name&type=starline', 'Каталог автосигнализаций Starline', 0, '/client/images/autosygnals/autosygnals-4.avif', 4),
('acssesuars', '/autosygnal?SELECT=name&type=remote-controls', 'Пульты и аксессуары', 0, '/client/images/autosygnals/autosygnals-5.avif', 5);










