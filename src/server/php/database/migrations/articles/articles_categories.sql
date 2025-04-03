CREATE TABLE IF NOT EXISTS ArticlesCategories (
    articles_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (articles_id, category_id),
    FOREIGN KEY (articles_id) REFERENCES Articles(articles_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES Categories(category_id) ON DELETE CASCADE
);