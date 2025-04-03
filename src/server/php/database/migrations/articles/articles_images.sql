CREATE TABLE IF NOT EXISTS ArticlesImages (
    articles_id INT NOT NULL,
    image_id INT NOT NULL,
    position INT NOT NULL DEFAULT 0,
    PRIMARY KEY (articles_id, image_id),
    FOREIGN KEY (articles_id) REFERENCES Articles(articles_id) ON DELETE CASCADE,
    FOREIGN KEY (image_id) REFERENCES ArticlesImagesAll(image_id) ON DELETE CASCADE,
    INDEX idx_articles_id (articles_id),
    INDEX idx_image_id (image_id),
    INDEX idx_position (position)
); 