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
    $stmt = $this->db->prepare("SELECT * FROM Products ORDER BY category, title");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $structuredData = [
      "category" => []
    ];

    foreach ($products as $product) {
      $category = $product['category'];

      if (!isset($structuredData['category'][$category])) {
        $structuredData['category'][$category] = [];
      }

      // Преобразуем булевы значения из БД (0/1) в true/false
      $product['cart'] = false; // По умолчанию, или можно добавить поле в БД
      $product['popular'] = (bool)$product['is_popular'];
      $product['special'] = (bool)$product['is_special'];

      // Декодируем JSON-строки в массивы, проверяя на null
      $product['gallery'] = $product['gallery'] ? json_decode($product['gallery'], true) : [];
      $product['functions'] = $product['functions'] ? json_decode($product['functions'], true) : [];
      $product['options'] = $product['options'] ? json_decode($product['options'], true) : [];
      $product['options-filters'] = $product['options_filters'] ? json_decode($product['options_filters'], true) : [];
      $product['autosygnals'] = $product['autosygnals'] ? json_decode($product['autosygnals'], true) : [];

      // Удаляем служебные поля, которых нет в исходной структуре
      unset($product['is_popular'], $product['is_special'], $product['created_at'], $product['updated_at']);

      $structuredData['category'][$category][] = $product;
    }

    return $structuredData;
  }
}
