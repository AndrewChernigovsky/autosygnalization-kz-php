CREATE TABLE IF NOT EXISTS NewsContent (
    news_id INT NOT NULL,
    content_id INT NOT NULL,
    position INT NOT NULL DEFAULT 0,
    PRIMARY KEY (news_id, content_id),
    FOREIGN KEY (news_id) REFERENCES News(news_id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES NewsContentAll(content_id) ON DELETE CASCADE,
    INDEX idx_news_id (news_id),
    INDEX idx_content_id (content_id),
    INDEX idx_position (position)
); 