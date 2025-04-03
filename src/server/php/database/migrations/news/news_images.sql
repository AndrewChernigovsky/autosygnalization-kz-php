CREATE TABLE IF NOT EXISTS NewsImages (
    news_id INT NOT NULL,
    image_id INT NOT NULL,
    position INT NOT NULL DEFAULT 0,
    PRIMARY KEY (news_id, image_id),
    FOREIGN KEY (news_id) REFERENCES News(news_id) ON DELETE CASCADE,
    FOREIGN KEY (image_id) REFERENCES NewsImagesAll(image_id) ON DELETE CASCADE,
    INDEX idx_news_id (news_id),
    INDEX idx_image_id (image_id),
    INDEX idx_position (position)
); 