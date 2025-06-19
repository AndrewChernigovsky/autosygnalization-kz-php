<?php

namespace API\SERVICES;

require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\ServicesData;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $services = (new ServicesData())->getData();
  header('Content-Type: application/json');
  echo json_encode($services);
} else {
  echo json_encode(['message' => 'Данные не получены']);
}


?>