<?php

// Тестовый скрипт для проверки API навигации
// Запуск: php test_navigation.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Тестирование Navigation API ===\n\n";

// Функция для выполнения HTTP запросов
function makeRequest($url, $method = 'GET', $data = null)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

  if ($data) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  }

  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  return [
    'code' => $httpCode,
    'body' => json_decode($response, true)
  ];
}

// Базовый URL для тестирования
$baseUrl = 'http://localhost/admin/api/navigation.php';

// Тест 1: Получение всех элементов навигации
echo "1. Получение всех элементов навигации:\n";
$response = makeRequest($baseUrl);
echo "HTTP Code: " . $response['code'] . "\n";
echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// Тест 2: Создание нового элемента навигации
echo "2. Создание нового элемента навигации:\n";
$newNavigation = [
  'title' => 'Тестовый раздел',
  'slug' => 'test-section',
  'href' => '/test',
  'parent_id' => null,
  'position' => 10,
  'is_active' => true,
  'icon' => 'test',
  'target' => '_self'
];
$response = makeRequest($baseUrl, 'POST', $newNavigation);
echo "HTTP Code: " . $response['code'] . "\n";
echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// Сохраняем ID созданного элемента для дальнейших тестов
$createdId = $response['body']['data']['navigation_id'] ?? null;

if ($createdId) {
  // Тест 3: Получение конкретного элемента
  echo "3. Получение элемента с ID $createdId:\n";
  $response = makeRequest($baseUrl . "?id=$createdId");
  echo "HTTP Code: " . $response['code'] . "\n";
  echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

  // Тест 4: Обновление элемента
  echo "4. Обновление элемента с ID $createdId:\n";
  $updatedNavigation = [
    'title' => 'Обновленный тестовый раздел',
    'slug' => 'test-section-updated',
    'href' => '/test-updated',
    'parent_id' => null,
    'position' => 15,
    'is_active' => true,
    'icon' => 'test-updated',
    'target' => '_self'
  ];
  $response = makeRequest($baseUrl . "?id=$createdId", 'PUT', $updatedNavigation);
  echo "HTTP Code: " . $response['code'] . "\n";
  echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

  // Тест 5: Удаление элемента
  echo "5. Удаление элемента с ID $createdId:\n";
  $response = makeRequest($baseUrl . "?id=$createdId", 'DELETE');
  echo "HTTP Code: " . $response['code'] . "\n";
  echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
}

// Тест 6: Получение дерева навигации
echo "6. Получение дерева навигации:\n";
$treeUrl = 'http://localhost/admin/api/navigation_tree.php';
$response = makeRequest($treeUrl);
echo "HTTP Code: " . $response['code'] . "\n";
echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// Тест 7: Тестирование валидации (создание элемента с пустыми полями)
echo "7. Тестирование валидации (пустые поля):\n";
$invalidNavigation = [
  'title' => '',
  'slug' => '',
  'href' => ''
];
$response = makeRequest($baseUrl, 'POST', $invalidNavigation);
echo "HTTP Code: " . $response['code'] . "\n";
echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// Тест 8: Тестирование получения несуществующего элемента
echo "8. Тестирование получения несуществующего элемента:\n";
$response = makeRequest($baseUrl . "?id=99999");
echo "HTTP Code: " . $response['code'] . "\n";
echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "=== Тестирование завершено ===\n";
?>