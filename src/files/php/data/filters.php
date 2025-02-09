<?php

include_once __DIR__ . '/products.php';

class FilterUpdater
{
    private array $filters;

    private array $products;
    private $filter_correct;

    public function __construct(array $products, $filter_correct = null)
    {
        $this->filters = [
            ["text" => "Автозапуск", "name" => "autosetup", "count" => "1"],
            ["text" => "УПРАВЛЕНИЕ С ТЕЛЕФОНА", "name" => "control-phone", "count" => "1"],
            ["text" => "БЕСПЛАТНЫЙ МОНИТОРИНГ", "name" => "free-monitoring", "count" => "1"],
            ["text" => "УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART", "name" => "bluetooth-smart", "count" => "1"],
            ["text" => "БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN", "name" => "block-engine-can", "count" => "1"],
            ["text" => "УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ", "name" => "control-before-start", "count" => "1"],
            ["text" => "УМНАЯ АВТОДИАГНОСТИКА", "name" => "smart-diagnostic", "count" => "1"],
            ["text" => "ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА", "name" => "data-level-bensin", "count" => "1"],
        ];
        $this->products = $products;
        $this->filter_correct = $filter_correct;
    }

    /**
     * Обновляет значения count в фильтрах на основе данных из продуктов.
     *
     * @return array
     */
    public function updateFilterCounts(): array
    {
        $count = [];

        // Подсчитываем количество товаров для каждого фильтра
        if ($this->filter_correct !== null) {
            foreach ($this->products['category'] as $items) {
                foreach ($items as $product) {
                    if (isset($product['options-filters']) && is_array($product['options-filters']) && in_array($this->filter_correct, $product['autosygnals'])) {
                        foreach ($product['options-filters'] as $filter) {
                            $count[$filter] = ($count[$filter] ?? 0) + 1;
                        }
                    }
                }
            }
        } elseif ($this->filter_correct === null) {
            foreach ($this->products['category'] as $items) {
                foreach ($items as $product) {
                    if (isset($product['options-filters']) && is_array($product['options-filters'])) {
                        foreach ($product['options-filters'] as $filter) {
                            $count[$filter] = ($count[$filter] ?? 0) + 1;
                        }
                    }
                }
            }
        }


        // Обновляем значения count в фильтрах
        foreach ($this->filters as &$category) {
            $filterKey = $category['name'];
            $category['count'] = $count[$filterKey] ?? 0;
        }

        return $this->filters;
    }

    public function getArrayFilters()
    {
        return $this->filters;
    }
}

// Исходные данные

// Создание объекта класса и обновление данных
$filterUpdater = new FilterUpdater($products, "gsm");
