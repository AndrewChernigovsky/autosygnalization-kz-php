-- Migration: add two test products for developer 2
-- Run on shared database to insert sample products for dev2
START TRANSACTION;

-- Insert product 1 if not exists
INSERT INTO `Products` (`id`, `category`, `model`, `title`, `description`, `price`, `price_list`, `currency`, `quantity`, `link`, `is_popular`, `is_special`, `gallery`, `functions`, `options`, `options_filters`, `autosygnals`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 
    'product_test_dev2_001' AS id,
    'test' AS category,
    'dev2-001' AS model,
    'Test Product Dev2 - 001' AS title,
    'Тестовый продукт для разработчика 2 - запись 001' AS description,
    1500 AS price,
    '[{"title":"","price":"","currency":"","content":""}]' AS price_list,
    '₸' AS currency,
    1 AS quantity,
    '/product?category=test&id=product_test_dev2_001' AS link,
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
WHERE NOT EXISTS (SELECT 1 FROM `Products` WHERE id = 'product_test_dev2_001');

-- Insert product 2 if not exists
INSERT INTO `Products` (`id`, `category`, `model`, `title`, `description`, `price`, `price_list`, `currency`, `quantity`, `link`, `is_popular`, `is_special`, `gallery`, `functions`, `options`, `options_filters`, `autosygnals`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 
    'product_test_dev2_002' AS id,
    'test' AS category,
    'dev2-002' AS model,
    'Test Product Dev2 - 002' AS title,
    'Тестовый продукт для разработчика 2 - запись 002' AS description,
    2500 AS price,
    '[{"title":"","price":"","currency":"","content":""}]' AS price_list,
    '₸' AS currency,
    1 AS quantity,
    '/product?category=test&id=product_test_dev2_002' AS link,
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
WHERE NOT EXISTS (SELECT 1 FROM `Products` WHERE id = 'product_test_dev2_002');

COMMIT;

-- Rollback statements:
-- DELETE FROM `Products` WHERE id = 'product_test_dev2_001' AND title LIKE 'Test Product Dev2%';
-- DELETE FROM `Products` WHERE id = 'product_test_dev2_002' AND title LIKE 'Test Product Dev2%';

