<?php

namespace DATA;

require_once __DIR__ . '/../database/Database.php';

use DATABASE\DataBase;
use PDO;

class ServicesData
{
  private $db;

  public function __construct()
  {
    $this->db = DataBase::getConnection()->getPdo();
  }

  public function getData(): array
  {
    $stmt = $this->db->prepare("SELECT * FROM Services ORDER BY id");
    $stmt->execute();
    $servicesFromDb = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $structuredServices = [];
    foreach ($servicesFromDb as $service) {
      $serviceId = $service['id'];
      $structuredServices[$serviceId] = [
        'id' => $serviceId,
        'name' => $service['name'],
        'description' => $service['description'],
        'image' => [
          'src' => $service['image_src'],
          'description' => $service['image_alt'],
        ],
        'href' => $service['href'],
        'services' => $service['services'] ?? '',
        'cost' => (int) $service['cost'],
        'currency' => $service['currency'],
      ];
    }

    // Получаем дополнительные услуги
    $addedServices = $this->getDataAddedServices();

    // Возвращаем основной массив + отдельный ключ для дополнительных услуг
    return [
      'main' => array_values($structuredServices),
      'added' => array_values($addedServices),
    ];
  }
  public function getDataAddedServices(): array
  {
    $stmt = $this->db->prepare("SELECT * FROM add_services ORDER BY id");
    $stmt->execute();
    $servicesFromDb = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $structuredServices = [];
    foreach ($servicesFromDb as $service) {
      $serviceId = $service['id'];

      $structuredServices[$serviceId] = [
        'id' => $serviceId,
        'title' => $service['title'],
        'price' => $service['price']
      ];
    }

    return $structuredServices;
  }

  public function getServiceByType(string $type): ?array
  {
    $stmt = $this->db->prepare("SELECT * FROM Services WHERE type = :type LIMIT 1");
    $stmt->execute([':type' => $type]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
      return null;
    }

    return [
      'id' => $service['id'],
      'name' => $service['name'],
      'description' => $service['description'],
      'image' => [
        'src' => $service['image_src'],
        'description' => $service['image_alt'],
      ],
      'href' => $service['href'],
      'services' => $service['services'] ?? '',
      'cost' => (int) $service['cost'],
      'currency' => $service['currency'],
    ];
  }
}
