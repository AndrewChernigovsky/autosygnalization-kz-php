<?php

include_once __DIR__ . '/products.php';

$data_categories_filters = [
  ["text" => "Автозапуск", "name" => "autosetup", "count" => "1"],
  ["text" => "УПРАВЛЕНИЕ С ТЕЛЕФОНА", "name" => "control-phone", "count" => "1"],
  ["text" => "БЕСПЛАТНЫЙ МОНИТОРИНГ", "name" => "free-monitoring", "count" => "1"],
  ["text" => "УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART", "name" => "bluetooth-smart", "count" => "1"],
  ["text" => "БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN", "name" => "block-engine-can", "count" => "1"],
  ["text" => "УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ", "name" => "control-before-start", "count" => "1"],
  ["text" => "УМНАЯ АВТОДИАГНОСТИКА", "name" => "smart-diagnostic", "count" => "1"],
  ["text" => "ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА", "name" => "data-level-bensin", "count" => "1"],
];

/**
 * Обновляет значения count в $data_categories_filters на основе данных из $products.
 *
 * @param array $data_categories_filters
 * @param array $products
 * @return array
 */
function updateFilterCounts($data_categories_filters, $products)
{
    $count = [];

    // Подсчитываем количество товаров для каждого фильтра
    foreach ($products['category'] as $items) {
        foreach ($items as $product) {
            if (isset($product['options-filters']) && is_array($product['options-filters'])) {
                foreach ($product['options-filters'] as $filter) {
                    if (isset($count[$filter])) {
                        $count[$filter]++;
                    } else {
                        $count[$filter] = 1;
                    }
                }
            }
        }
    }

    // Обновляем значения count в $data_categories_filters
    foreach ($data_categories_filters as &$category) {
        $filterKey = $category['name'];
        $category['count'] = $count[$filterKey] ?? 0;
    }

    return $data_categories_filters;
}

// Пример использования
$data_categories_filters = updateFilterCounts($data_categories_filters, $products);
