CREATE TABLE IF NOT EXISTS ArticlesTags (
    articles_id INT,
    tag_id INT,
    PRIMARY KEY (articles_id, tag_id),
    FOREIGN KEY (articles_id) REFERENCES Articles(articles_id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES Tags(tag_id) ON DELETE CASCADE
); 