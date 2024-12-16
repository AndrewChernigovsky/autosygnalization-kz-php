<?php

function getProductCard($products, $model)
{
  $output = "";

  if (is_array($products) && isset($model)) {
    foreach ($products as $product) {
      if ($product['model'] === $model) {
        $output .= "<article class='product-card'>";
        $output .= "<h3>" . htmlspecialchars($product['title']) . "</h3>";

        if (isset($product['description'])) {
          $output .= "<p>" . htmlspecialchars($product['description']) . "</p>";
        }

        if (isset($product['price'])) {
          $output .= "<p>Цена: " . htmlspecialchars($product['price']) . " " . htmlspecialchars($product['currency']) . "</p>";
        }

        $output .= "</article>";
      }
    }
  }

  return $output;
}
?>