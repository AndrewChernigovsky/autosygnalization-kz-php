<?php

namespace API\SERVICES;

require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\ServicesData;

// CORS headers are handled by .htaccess

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $servicesData = new ServicesData();
  $services = $servicesData->getData(); // Assumes getData now returns the correct structure

  header('Content-Type: application/json');
  echo json_encode($services);
} else {
  header('Content-Type: application/json');
  http_response_code(405); // Method Not Allowed
  echo json_encode(['message' => 'Method Not Allowed']);
}

?>