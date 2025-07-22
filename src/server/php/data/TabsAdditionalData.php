<?php

namespace DATA;

use DATABASE\Database;
use PDO;

class TabsAdditionalData
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  public function getTabsByProductId($productId)
  {
    $stmt = $this->db->prepare("SELECT tabs_data FROM TabsAdditionalProductsData WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $productId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['tabs_data']) {
      $tabsJson = json_decode($result['tabs_data'], true);
      $formattedTabs = [];
      if (is_array($tabsJson)) {
        foreach ($tabsJson as $title => $data) {
          // Assuming content is stored in a structured way, e.g., under 'items'
          $formattedTabs[] = [
            'title' => $title,
            'content' => json_encode($data) // Or format as needed for the frontend
          ];
        }
      }
      return $formattedTabs;
    }
    return [];
  }

  public function updateTabsForProduct($productId, $tabs)
  {
    $tabsToStore = [];
    foreach ($tabs as $tab) {
      // Attempt to decode content if it's a JSON string
      $content = json_decode($tab['content'], true);
      // If decoding fails, it's probably not a JSON string, keep as is
      if (json_last_error() !== JSON_ERROR_NONE) {
        $content = $tab['content'];
      }
      $tabsToStore[$tab['title']] = $content;
    }
    $tabsJson = json_encode($tabsToStore, JSON_UNESCAPED_UNICODE);

    $stmt = $this->db->prepare("SELECT product_id FROM TabsAdditionalProductsData WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $productId);
    $stmt->execute();

    if ($stmt->fetch()) {
      $updateStmt = $this->db->prepare("UPDATE TabsAdditionalProductsData SET tabs_data = :tabs_data WHERE product_id = :product_id");
      $updateStmt->bindParam(':tabs_data', $tabsJson);
      $updateStmt->bindParam(':product_id', $productId);
      return $updateStmt->execute();
    } else {
      $insertStmt = $this->db->prepare("INSERT INTO TabsAdditionalProductsData (product_id, tabs_data) VALUES (:product_id, :tabs_data)");
      $insertStmt->bindParam(':product_id', $productId);
      $insertStmt->bindParam(':tabs_data', $tabsJson);
      return $insertStmt->execute();
    }
  }
}
