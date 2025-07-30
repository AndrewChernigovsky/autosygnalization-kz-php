<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Обработка preflight запроса
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

function getAvailablePages() {
    $pagesDir = __DIR__ . '/../../../pages';
    $pages = [];
    
    // Сопоставление папок с названиями страниц
    $pageMapping = [
        'about' => 'О нас',
        'autosygnals' => 'Автосигнализации',
        'cart' => 'Корзина',
        'checkout' => 'Оформление заказа',
        'contacts' => 'Контакты',
        'parking-systems' => 'Парковочные системы',
        'price' => 'Прайс',
        'sertificates' => 'Сертификаты',
        'services' => 'Услуги',
    ];
    
    // Страницы, которые нужно исключить
    $excludePages = ['products', 'service', 'special', 'autosygnal', 'catalog'];
    
    try {
        if (is_dir($pagesDir)) {
            $directories = array_diff(scandir($pagesDir), ['.', '..']);
            
            foreach ($directories as $dir) {
                if (is_dir($pagesDir . '/' . $dir) && !in_array($dir, $excludePages)) {
                    
                    // Проверяем обычные страницы
                    if (isset($pageMapping[$dir])) {
                        $pages[] = [
                            'title' => $pageMapping[$dir],
                            'link' => '/' . $dir
                        ];
                    }
                }
            }
            
            // Сортируем по названию
            usort($pages, function($a, $b) {
                return strcmp($a['title'], $b['title']);
            });
            
        } else {
            throw new Exception('Pages directory not found');
        }
        
    } catch (Exception $e) {
        throw new Exception('Error scanning pages: ' . $e->getMessage());
    }
    
    return $pages;
}

try {
    $pages = getAvailablePages();
    
    echo json_encode([
        'success' => true,
        'data' => $pages
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 