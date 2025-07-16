<?php

namespace API\ADMIN;
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');


require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../database/DataBase.php';

use DATABASE\DataBase;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit(0);
}

class ContactAPI extends DataBase
{
    protected $pdo;
    
      public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }
}

