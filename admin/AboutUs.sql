-- Comments explaining the purpose of each column have been added for clarity.
CREATE TABLE `AboutUs` (
  `about_us_id` INT AUTO_INCREMENT PRIMARY KEY,
  `type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Тип контента (напр., slogan, advantage, comment)',
  `title` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Заголовок для элемента, если есть',
  `content` TEXT COLLATE utf8mb4_general_ci NULL COMMENT 'Текстовое содержимое',
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Путь к изображению',
  `position` int(11) NULL COMMENT 'Порядок сортировки внутри группы одного типа',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE INDEX idx_about_us_type ON `AboutUs` (`type`);


INSERT INTO `AboutUs` (`type`, `title`, `content`, `image_path`, `position`) VALUES
('present-slogan', NULL, '“Auto Security” – магазин и установочный центр автоэлектроники.', NULL, 1),
('present-slogan', NULL, 'Мы предлагаем лучшее!', NULL, 2),
('present-text', NULL, 'Наша компания была основана в 2004 году, в самый расцвет автосервисов.', NULL, 1),
('present-text', NULL, 'Миссия нашей компании – осуществлять качественные услуги в сфере продаж, установки и ремонта автоэлектроники.', NULL, 2),

('advantages-item', NULL, 'Наши мастера имеют богатый опыт по инсталляции разнообразного электронного оборудования на различные автомобили.', NULL, 1),
('advantages-item', NULL, 'Мы постоянно повышаем свою квалификацию, участвуем в конференциях.', NULL, 2),
('advantages-item', NULL, 'Аккуратность и ответственность – именно это сегодня является важными отличиями команды "Auto Security".', NULL, 3),
('advantages-item', NULL, 'Наш сервис укомплектован с овременным диагностическим оборудованием, позволяющим нам корректно работать с абсолютно новыми автомобилями.', NULL, 4),
('advantages-item', NULL, 'Нашим клиентам мы предлагаем услугу выезда для экономии времени и наибольшего комфорта.', NULL, 5),

('comment', NULL, 'Дружная команда опытных установщиков с удовольствием воплотит ваши мечты в реальность!', NULL, 1),
('comment', NULL, 'Обращайтесь к нам, будем рады Вам помочь!', NULL, 2),


('tech-photo-image', NULL, NULL, '/client/images/about_us/about_1.avif', 1),
('tech-photo-image', NULL, NULL, '/client/images/about_us/about_2.avif', 2),
('tech-photo-image', NULL, NULL, '/client/images/about_us/about_3.avif', 3),

('appeal-text', NULL, 'Вам будет оказана квалифицированная помощь по установке дополнительного электронного оборудования на Ваш автомобиль!', NULL, 1),
('appeal-text', NULL, 'Мы продиагностируем Ваш авто, отремонтируем, установим, настроим Ваше оборудование! Доверяйте профессионалам!', NULL, 2);






