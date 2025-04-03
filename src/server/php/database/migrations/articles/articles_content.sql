CREATE TABLE IF NOT EXISTS ArticlesContent (
    articles_id INT NOT NULL,
    content_id INT NOT NULL,
    position INT NOT NULL DEFAULT 0,
    PRIMARY KEY (articles_id, content_id),
    FOREIGN KEY (articles_id) REFERENCES Articles(articles_id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES ArticlesContentAll(content_id) ON DELETE CASCADE,
    INDEX idx_articles_id (articles_id),
    INDEX idx_content_id (content_id),
    INDEX idx_position (position)
); 