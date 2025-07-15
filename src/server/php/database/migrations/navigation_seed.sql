INSERT INTO Navigation (title, slug, href, parent_id, position, is_active, icon, target) VALUES 
('Главная', 'home', '/', NULL, 1, TRUE, 'home', '_self'),
('Каталог', 'catalog', '/catalog', NULL, 2, TRUE, 'grid', '_self'),
('Автосигнализации', 'autosignals', '/catalog/autosignals', 2, 1, TRUE, 'shield', '_self'),
('Брелоки', 'keychains', '/catalog/keychains', 2, 2, TRUE, 'key', '_self'),
('Модули', 'modules', '/catalog/modules', 2, 3, TRUE, 'cpu', '_self'),
('Услуги', 'services', '/services', NULL, 3, TRUE, 'settings', '_self'),
('Установка', 'installation', '/services/installation', 6, 1, TRUE, 'tool', '_self'),
('Диагностика', 'diagnostics', '/services/diagnostics', 6, 2, TRUE, 'search', '_self'),
('Гарантия', 'warranty', '/services/warranty', 6, 3, TRUE, 'shield-check', '_self'),
('О нас', 'about', '/about', NULL, 4, TRUE, 'info', '_self'),
('Контакты', 'contacts', '/contacts', NULL, 5, TRUE, 'phone', '_self'),
('Документы', 'documents', '/documents', NULL, 6, TRUE, 'file-text', '_self'),
('Сертификаты', 'certificates', '/documents/certificates', 12, 1, TRUE, 'award', '_self'),
('Прайс-листы', 'price-lists', '/documents/price-lists', 12, 2, TRUE, 'dollar-sign', '_self')
ON DUPLICATE KEY UPDATE 
    title = VALUES(title),
    href = VALUES(href),
    parent_id = VALUES(parent_id),
    position = VALUES(position),
    is_active = VALUES(is_active),
    icon = VALUES(icon),
    target = VALUES(target); 