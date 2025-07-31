-- Comments explaining the purpose of each column have been added for clarity.
CREATE TABLE `AdvantageVideos` (
  `video_id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` TEXT COLLATE utf8mb4_general_ci NULL COMMENT 'Текстовое содержимое',
  `title_icon` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Путь к изображению заголовка',
  `video_poster` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Путь к изображению заголовка для мобильной версии',
  `video_src_mob` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Путь к видео для мобильной версии',
  `position` int(11) NULL COMMENT 'Порядок сортировки',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `AdvantageVideos` (`title`, `title_icon`, `video_poster`, `video_src_mob`, `position`) VALUES
('Auto Security - АВТОРИЗИРОВАННЫЙ ПАРТНЕР STARLINE', '/client/images/logo-starline.png', '/client/images/video-images/poster-quality.avif', '/client/videos/video-quality-mob.webm', 1); 