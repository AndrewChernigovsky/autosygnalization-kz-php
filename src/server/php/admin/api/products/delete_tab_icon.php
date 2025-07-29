<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\Database;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

header('Content-Type: application/json');

$dbConnection = Database::getConnection();
$pdo = $dbConnection->getPdo();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['productId'], $data['tabIndex'], $data['itemIndex'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Missing required parameters.']);
  exit;
}

$productId = $data['productId'];
$tabIndex = (int) $data['tabIndex'];
$itemIndex = (int) $data['itemIndex'];

try {
  $pdo->beginTransaction();

  // 1. Get current tabs data
  $stmt = $pdo->prepare("SELECT tabs_data FROM TabsAdditionalProductsData WHERE product_id = :product_id");
  $stmt->execute([':product_id' => $productId]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$result) {
    throw new Exception("Product tabs data not found.");
  }

  $tabs = json_decode($result['tabs_data'], true);

  // 2. Check for icon and delete it
  if (isset($tabs[$tabIndex]['description'][$itemIndex]['icon'])) {
    $iconPath = $tabs[$tabIndex]['description'][$itemIndex]['icon'];
    if ($iconPath) {
      $fileFullPath = __DIR__ . '/../../../..' . $iconPath;
      if (file_exists($fileFullPath)) {
        unlink($fileFullPath);
      }
    }
  }

  // 3. Update the path in the tabs array to be empty
  $tabs[$tabIndex]['description'][$itemIndex]['icon'] = '';

  // 4. Save updated tabs data back to DB
  $updateStmt = $pdo->prepare("UPDATE TabsAdditionalProductsData SET tabs_data = :tabs_data WHERE product_id = :product_id");
  $updateStmt->execute([
    ':tabs_data' => json_encode($tabs),
    ':product_id' => $productId
  ]);

  $pdo->commit();

  http_response_code(200);
  echo json_encode(['message' => 'Icon deleted successfully.']);

} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
}