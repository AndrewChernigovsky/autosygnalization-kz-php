-- Comments explaining the purpose of each column have been added for clarity.
CREATE TABLE `Advantage` (
  `advantage_id` INT AUTO_INCREMENT PRIMARY KEY,
  `content` TEXT COLLATE utf8mb4_general_ci NULL COMMENT 'Текстовое содержимое',
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Путь к изображению',
  `position` int(11) NULL COMMENT 'Порядок сортировки',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





INSERT INTO `Advantage` (`content`, `image_path`, `position`) VALUES
('ДОСТУПНАЯ СТОИМОСТЬ ПРОДУКЦИИ И УСЛУГ', '/client/vectors/economy-1.svg', 1),
('ПРЕДОСТАВЛЯЕМ КЛИЕНТАМ НАИЛУЧШЕЕ КАЧЕСТВО ТОВАРОВ И СЕРВИСА', '/client/vectors/economy-2.svg', 2),
('ИНДИВИДУАЛЬНЫЙ ПОДХОД К КАЖДОМУ КЛИЕНТУ', '/client/vectors/economy-3.svg', 3),
('Большой опыт работы', '/client/vectors/economy-4.svg', 4),
('Гарантия на все товары/услуги', '/client/vectors/economy-5.svg', 5);








