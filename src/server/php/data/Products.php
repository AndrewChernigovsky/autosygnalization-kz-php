<?php

namespace DATA;

require_once __DIR__ . '/../database/Database.php';

use DATABASE\DataBase; // Изменено на DataBase
use PDO;

class Products
{
  private $db;

  public function __construct()
  {
    $this->db = DataBase::getConnection();
  }

  public function getData(): array
  {
    $stmt = $this->db->prepare("
        SELECT p.*, t.tabs_data 
        FROM Products p
        LEFT JOIN TabsAdditionalProductsData t ON p.id = t.product_id
        ORDER BY p.category, p.title
    ");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $processedProducts = [];

    foreach ($products as $product) {
      // Преобразуем булевы значения из БД (0/1) в true/false
      $product['cart'] = false; // По умолчанию, или можно добавить поле в БД
      $product['is_popular'] = (bool) $product['is_popular'];
      $product['is_special'] = (bool) $product['is_special'];

      // Декодируем JSON-строки в массивы, проверяя на null
      $product['gallery'] = $product['gallery'] ? json_decode($product['gallery'], true) : [];
      $product['functions'] = $product['functions'] ? json_decode($product['functions'], true) : [];
      $product['options'] = $product['options'] ? json_decode($product['options'], true) : [];
      $product['autosygnals'] = $product['autosygnals'] ? json_decode($product['autosygnals'], true) : [];

      // Handle keys with different names
      $product['options-filters'] = !empty($product['options_filters']) ? json_decode($product['options_filters'], true) : [];
      unset($product['options_filters']);

      $product['tabs'] = !empty($product['tabs_data']) ? json_decode($product['tabs_data'], true) : [];
      unset($product['tabs_data']);

      // Получаем услуги (цены) для товара
      $pricesStmt = $this->db->prepare('
        SELECT pr.id, pr.content
        FROM Products_prices pp
        JOIN Prices pr ON pp.price_id = pr.id
        WHERE pp.product_id = :product_id
      ');
      $pricesStmt->execute([':product_id' => $product['id']]);
      $product['prices'] = $pricesStmt->fetchAll(PDO::FETCH_ASSOC);

      // Удаляем служебные поля, которых нет в исходной структуре
      unset($product['created_at'], $product['updated_at']);

      $processedProducts[] = $product;
    }

    return $processedProducts;
  }
}
