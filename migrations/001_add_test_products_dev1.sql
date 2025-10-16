-- Migration: add two test products for developer 1
-- Behavior: insert only if product id does not already exist (no overwrite)
-- Rollback: remove inserted test products only if they match the test title pattern

START TRANSACTION;

-- Insert product 1 if not exists
INSERT INTO `Products` (`id`, `category`, `model`, `title`, `description`, `price`, `price_list`, `currency`, `quantity`, `link`, `is_popular`, `is_special`, `gallery`, `functions`, `options`, `options_filters`, `autosygnals`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 
    'product_test_dev1_001' AS id,
    'test' AS category,
    'dev1-001' AS model,
    'Test Product Dev1 - 001' AS title,
    'Тестовый продукт для разработчика 1 - запись 001' AS description,
    1000 AS price,
    '[{"title":"","price":"","currency":"","content":""}]' AS price_list,
    '₸' AS currency,
    1 AS quantity,
    '/product?category=test&id=product_test_dev1_001' AS link,
    0 AS is_popular,
    0 AS is_special,
    '[]' AS gallery,
    '[]' AS functions,
    '[]' AS options,
    '[]' AS options_filters,
    '[]' AS autosygnals,
    NOW() AS created_at,
    NOW() AS updated_at
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `Products` WHERE id = 'product_test_dev1_001');

-- Insert product 2 if not exists
INSERT INTO `Products` (`id`, `category`, `model`, `title`, `description`, `price`, `price_list`, `currency`, `quantity`, `link`, `is_popular`, `is_special`, `gallery`, `functions`, `options`, `options_filters`, `autosygnals`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 
    'product_test_dev1_002' AS id,
    'test' AS category,
    'dev1-002' AS model,
    'Test Product Dev1 - 002' AS title,
    'Тестовый продукт для разработчика 1 - запись 002' AS description,
    2000 AS price,
    '[{"title":"","price":"","currency":"","content":""}]' AS price_list,
    '₸' AS currency,
    1 AS quantity,
    '/product?category=test&id=product_test_dev1_002' AS link,
    0 AS is_popular,
    0 AS is_special,
    '[]' AS gallery,
    '[]' AS functions,
    '[]' AS options,
    '[]' AS options_filters,
    '[]' AS autosygnals,
    NOW() AS created_at,
    NOW() AS updated_at
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `Products` WHERE id = 'product_test_dev1_002');

COMMIT;

-- Rollback section: delete only rows that were inserted by this migration
-- Safety: delete by id AND title pattern to avoid removing pre-existing rows with same id
-- To rollback, run the following statements:
-- DELETE FROM `Products` WHERE id = 'product_test_dev1_001' AND title LIKE 'Test Product Dev1%';
-- DELETE FROM `Products` WHERE id = 'product_test_dev1_002' AND title LIKE 'Test Product Dev1%';

