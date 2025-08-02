CREATE TABLE IF NOT EXISTS Navigation (
    navigation_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    href VARCHAR(255) NOT NULL,
    parent_id INT NULL,
    position INT NOT NULL DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    icon VARCHAR(255) NULL,
    target VARCHAR(20) DEFAULT '_self',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES Navigation(navigation_id) ON DELETE CASCADE,
    INDEX (parent_id),
    INDEX (position),
    INDEX (is_active)
); 