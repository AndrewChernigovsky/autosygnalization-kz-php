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
('present-slogan-block', NULL, '<p>“Auto Security” – магазин и установочный центр автоэлектроники.</p><p>Мы предлагаем лучшее!</p>', NULL, 1),
('present-text-block', NULL, '<p>Наша компания была основана в 2004 году, в самый расцвет автосервисов.</p><p>Миссия нашей компании – осуществлять качественные услуги в сфере продаж, установки и ремонта автоэлектроники.</p>', NULL, 1),
('advantages-list', NULL, '<ol><li>Наши мастера имеют богатый опыт по инсталляции разнообразного электронного оборудования на различные автомобили.</li><li>Мы постоянно повышаем свою квалификацию, участвуем в конференциях.</li><li>Аккуратность и ответственность – именно это сегодня является важными отличиями команды "Auto Security".</li><li>Наш сервис укомплектован с овременным диагностическим оборудованием, позволяющим нам корректно работать с абсолютно новыми автомобилями.</li><li>Нашим клиентам мы предлагаем услугу выезда для экономии времени и наибольшего комфорта.</li></ol>', NULL, 1),
('comment-block', NULL, '<p>Дружная команда опытных установщиков с удовольствием воплотит ваши мечты в реальность!</p><p>Обращайтесь к нам, будем рады Вам помочь!</p>', NULL, 1),
('appeal-text-block', NULL, '<p>Вам будет оказана квалифицированная помощь по установке дополнительного электронного оборудования на Ваш автомобиль!</p><p>Мы продиагностируем Ваш авто, отремонтируем, установим, настроим Ваше оборудование! Доверяйте профессионалам!</p>', NULL, 1),

('tech-photo-image', NULL, NULL, '/client/images/about_us/about_1.avif', 1),
('tech-photo-image', NULL, NULL, '/client/images/about_us/about_2.avif', 2),
('tech-photo-image', NULL, NULL, '/client/images/about_us/about_3.avif', 3);







