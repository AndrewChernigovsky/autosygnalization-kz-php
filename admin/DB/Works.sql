-- Comments explaining the purpose of each column have been added for clarity.
CREATE TABLE `Works` (
  `work_id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Заголовок для элемента, если есть',
  `link` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Ссылка на страницу',
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Путь к изображению',
  `position` int(11) NULL COMMENT 'Порядок сортировки',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `Works` (`title`, `link`, `image_path`, `position`) VALUES
('УСТАНОВКА И РЕМОНТ АВТОСИГНАЛИЗАЦИЙ', '/service?service=setup', '/client/images/works/setup.avif', 1),
('УСТАНОВКА АВТОЗВУКА И МУЛЬТИМЕДИА', '/service?service=setup-media', '/client/images/works/setup-media.avif', 2),
('РЕМОНТ ЦЕНТРОЗАМКОВ', '/service?service=locks', '/client/images/works/locks.avif', 3),
('РУСИФИКАЦИЯ АВТО И ЧИПТЮНИНГ', '/service?service=rus', '/client/images/works/rus.avif', 4),
('УСТАНОВКА СИСТЕМ ПАРКИНГА', '/service?service=setup-system-parking', '/client/images/works/system-parking.avif', 5),
('УСЛУГИ АВТОЭЛЕКТРИКА', '/service?service=autoelectric', '/client/images/works/autoelectric.avif', 6),
('КОМПЬЮТЕРНАЯ ДИАГНОСТИКА', '/service?service=diagnostic', '/client/images/works/diagnostic.avif', 7),
('ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ', '/service?service=disabled-autosynal', '/client/images/works/disabled-autosynal.avif', 8),
('УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ И АНТИРАДАРОВ', '/service?service=setup-videoregistration', '/client/images/works/setup-videoregistration.avif', 9);








