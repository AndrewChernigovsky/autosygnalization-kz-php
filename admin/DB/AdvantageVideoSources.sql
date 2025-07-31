CREATE TABLE `AdvantageVideoSources` (
  `source_id` INT AUTO_INCREMENT PRIMARY KEY,
  `video_id` INT NOT NULL,
  `src_path` VARCHAR(255) NOT NULL COMMENT 'Путь к видео-файлу',
  `src_type` VARCHAR(50) NOT NULL COMMENT 'MIME-тип видео, например, video/webm',
  FOREIGN KEY (`video_id`) REFERENCES `AdvantageVideos`(`video_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `AdvantageVideoSources` (`video_id`, `src_path`, `src_type`) VALUES
(1, '/client/videos/video-quality.webm', 'video/webm'),
(1, '/client/videos/video-quality.mp4', 'video/mp4'); 