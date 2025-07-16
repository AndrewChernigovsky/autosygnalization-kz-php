-- Таблица контактов для хранения телефонных номеров
-- Содержит основную информацию о контактах с временными метками
CREATE TABLE `Contacts` (
  `contact_id` INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор контакта
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,  -- Название/тип контакта (например: "Основной", "Запасной")
  `phone` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,  -- Номер телефона в читаемом формате
  `link` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,   -- Ссылка для звонка (tel: формат)
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,     -- Время создания записи
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- Время последнего обновления
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Начальные данные: добавление основных контактов
INSERT INTO `Contacts` (`title`, `phone`, `link`) VALUES
('Основной', '+7 707 747 8212', 'tel:+77077478212'),
('Запасной', '+7 701 747 8212', 'tel:+77017478212');
