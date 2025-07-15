-- Создание таблицы Videos_intro_slider
CREATE TABLE IF NOT EXISTS Videos_intro_slider (
    id INT AUTO_INCREMENT PRIMARY KEY,
    video_filename VARCHAR(255) NOT NULL,
    video_path VARCHAR(500) NOT NULL,
    title VARCHAR(255) NOT NULL,
    advantages TEXT,
    button_text VARCHAR(100) DEFAULT 'Подробнее',
    button_link VARCHAR(500) DEFAULT '#',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Индексы для оптимизации
CREATE INDEX IF NOT EXISTS idx_videos_intro_slider_active ON Videos_intro_slider(is_active);
CREATE INDEX IF NOT EXISTS idx_videos_intro_slider_created ON Videos_intro_slider(created_at); 