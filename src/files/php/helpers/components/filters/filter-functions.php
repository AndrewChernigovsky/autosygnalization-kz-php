<?php

include_once __DIR__ . '/../../../data/products.php';

class FilterFunctions
{
    private array $data_filters;
    private array $products;

    public function __construct(array $products = [], $filter_correct = null, array $data_filters = [])
    {
        $this->data_filters = !empty($data_filters) ? $data_filters : [
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

    public function renderFilters()
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
        } else {
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
        foreach ($this->data_filters as &$category) {
            $filterKey = $category['name'];
            $category['count'] = $count[$filterKey] ?? 0;
        }

        ob_start();
        ?>
        <div class="filter-functions">
            <p class="filter-functions__title">Функции</p>
            <?php foreach ($this->data_filters as $category): ?>
                <div class="filter-functions__item">
                    <label class="filter-functions__item-element">
                        <input type="checkbox" class="filter-functions__checkbox" id="<?= htmlspecialchars($category['name']) ?>"
                               name="<?= htmlspecialchars($category['name']) ?>">
                        <span class="filter-functions__item-title"><?= htmlspecialchars($category['text']) ?></span>
                    </label>
                    <span class="filter-functions__count"><?= $category['count'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
}

?>