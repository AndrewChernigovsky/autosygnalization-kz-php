CREATE TABLE `docs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `path` VARCHAR(255) NOT NULL,
    `key` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `docs` (`path`, `key`) VALUES
('/client/docs/deal.txt', 'deal'),
('/client/docs/delivary.txt', 'delivary'),
('/client/docs/policy.txt', 'policy');


