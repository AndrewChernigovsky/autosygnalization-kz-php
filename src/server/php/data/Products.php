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
      $product['popular'] = (bool) $product['is_popular'];
      $product['is_special'] = (bool) $product['is_special'];

      // Декодируем JSON-строки в массивы, проверяя на null
      $product['gallery'] = $product['gallery'] ? json_decode($product['gallery'], true) : [];
      $product['functions'] = $product['functions'] ? json_decode($product['functions'], true) : [];
      $product['options'] = $product['options'] ? json_decode($product['options'], true) : [];
      $product['options-filters'] = $product['options_filters'] ? json_decode($product['options_filters'], true) : [];
      $product['autosygnals'] = $product['autosygnals'] ? json_decode($product['autosygnals'], true) : [];
      $product['tabs'] = $product['tabs_data'] ? json_decode($product['tabs_data'], true) : [];
      $product['category'] = $product['category'] ? json_decode($product['category'], true) : [];

      // Удаляем служебные поля, которых нет в исходной структуре
      unset($product['is_popular'], $product['created_at'], $product['updated_at'], $product['tabs_data']);

      $processedProducts[] = $product;
    }

    return $processedProducts;
  }
}
