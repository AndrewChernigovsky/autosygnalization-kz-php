-- Comments explaining the purpose of each column have been added for clarity.
CREATE TABLE `Sertificates` (
  `sertificate_id` INT AUTO_INCREMENT PRIMARY KEY,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Путь к изображению',
  `pdf_path` varchar(255) COLLATE utf8mb4_general_ci NULL COMMENT 'Путь к PDF',
  `position` int(11) NULL COMMENT 'Порядок сортировки',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





INSERT INTO `Sertificates` (`image_path`, `pdf_path`, `position`) VALUES
('/client/images/sertificates/sertificate-1.avif', '/client/pdf/sertificate-1.pdf', 1),
('/client/images/sertificates/sertificate-2.avif', '/client/pdf/sertificate-2.pdf', 2),
('/client/images/sertificates/sertificate-3.avif', '/client/pdf/sertificate-3.pdf', 3);








