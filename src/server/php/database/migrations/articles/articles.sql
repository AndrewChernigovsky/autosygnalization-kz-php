CREATE TABLE IF NOT EXISTS Articles (
    articles_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    user_id INT,
    href VARCHAR(255),
    banner VARCHAR(255),
    date TIMESTAMP NOT NULL,
    description TEXT,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    meta_tags TEXT,
    shemas TEXT,
    post_by_day INT,
    post_by_week INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX (user_id)
); 