<?php
include_once __DIR__ . '/../../../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $docROOT . $variables->getPathFileURL();


include_once $path . '/files/php/data/products.php';


class FiltersRender
{
    private $filter_correct;
    private $path;
    private $filters_products_count;
    private $get_params;
    
    private $data_filters;
    private array $products;

    public function __construct(array $products = [], $filter_correct = null,$get_params = null, $data_filters = null)
    {

        $this->data_filters = !empty($data_filters) ? $data_filters : [
            ["text" => "Автозапуск", "name" => "autosetup", "count" => "1"],
            ["text" => "УПРАВЛЕНИЕ С ТЕЛЕФОНА", "name" => "control-phone", "count" => "1"],
            ["text" => "БЕСПЛАТНЫЙ МОНИТОРИНГ", "name" => "free-monitoring", "count" => "1"],
            ["text" => "УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART", "name" => "bluetooth-smart", "count" => "1"],
            ["text" => "БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN", "name" => "block-engine-can", "count" => "1"],
            ["text" => "УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ", "name" => "control-before-start", "count" => "1"],
            ["text" => "ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА", "name" => "data-level-bensin", "count" => "1"],
            ["text" => "УМНАЯ АВТОДИАГНОСТИКА", "name" => "smart-diagnostic", "count" => "1"],
        ];
        $this->get_params = !empty($get_params) ? $get_params : $_GET;
        $this->filter_correct = $filter_correct;

        $variables = new SetVariables();
        $variables->setVar();
        $this->path = $variables->getPathFileURL();
        $this->products = $products;
        $this->filters_products_count = [];


    
    }

    public function renderFiltersFunctions()
    {
        $count = [];

        
        if ($this->filter_correct !== null && $this->get_params !== null) {
            foreach ($this->products['category'] as $items) {
                foreach ($items as $product) {
                    $match = true;
                    $minCost = $this->get_params['min-value-cost'] ?? 0;
                    $maxCost = $this->get_params['max-value-cost'] ?? PHP_INT_MAX;

                    if ($product['price'] < $minCost || $product['price'] > $maxCost) {
                        $match = false;
                    }
                    
                    $active_params_arr = [];
                    foreach ($this->get_params as $key => $value) {
                        if ($value === 'on') {
                            $active_params_arr[] = $key;
                        }
                    }

                    if ($match && isset($product['options-filters']) && is_array($product['options-filters']) && in_array($this->filter_correct, $product['autosygnals']) && empty(array_diff($active_params_arr, $product['options-filters']))) {
                        foreach ($product['options-filters'] as $filter) {
                            $count[$filter] = ($count[$filter] ?? 0) + 1;
                        }
                    }
                    
                }
            }
        } elseif ($this->get_params !== null) {
            foreach ($this->products['category'] as $items) {
                foreach ($items as $product) {
                    $match = true;
                    $minCost = $this->get_params['min-value-cost'] ?? 0;
                    $maxCost = $this->get_params['max-value-cost'] ?? PHP_INT_MAX;

                    if ($product['price'] < $minCost || $product['price'] > $maxCost) {
                        $match = false;
                    }

                    $active_params_arr = [];
                    foreach ($this->get_params as $key => $value) {
                        if ($value === 'on') {
                            $active_params_arr[] = $key;
                        }
                    }

                    if ($match && isset($product['options-filters']) && is_array($product['options-filters']) && empty(array_diff($active_params_arr, $product['options-filters']))) {
                        foreach ($product['options-filters'] as $filter) {
                            $count[$filter] = ($count[$filter] ?? 0) + 1;
                        }
                    }
                }
            }
        }
    
        foreach ($this->data_filters as $index => $category) {
            $filterKey = $category['name'];
            $this->data_filters[$index]['count'] = $count[$filterKey] ?? 0;
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
    public function renderFiltersCost()
    {
        ob_start();
        ?>
            <div class="double-range-wrapper filter-cost">
                <div class="range-slider-container">
                    <div class="range-container">
                    <div class="range-scale">
                        <div class="range-progress"></div>
                    </div>
                    <div class="range-wrapper">
                        <input type="range" class="range-input range-min" min="100" max="300000" step="100"/>
                        <input type="range" class="range-input range-max" />
                    </div>
                    </div>
                    <div class="number-container">
                    <div class="numbers-wrapper">
                        <input type="number" class="number-input input-min" min="100" max="300000" step="100" name="min-value-cost"/>
                    </div>
                    <div class="numbers-wrapper">
                        <input type="number" class="number-input input-max" min="100" max="300000" step="100" name="max-value-cost"/>
                    </div>
                    </div>
                </div>
            </div>

        <?php
        
        return ob_get_clean();
    }
    public function renderFiltersBasic()
    {
        if (!empty($this->products)) {
            foreach ($this->products['category'] as $items) {
                foreach ($items as $product) {
                    if ($this->filter_correct !== null) {
                        $match = true;
                        $minCost = $this->get_params['min-value-cost'] ?? 0;
                        $maxCost = $this->get_params['max-value-cost'] ?? PHP_INT_MAX;
    
                        if ($product['price'] < $minCost || $product['price'] > $maxCost) {
                            $match = false;
                        }
                        
                        $active_params_arr = [];
                        foreach ($this->get_params as $key => $value) {
                            if ($value === 'on') {
                                $active_params_arr[] = $key;
                            }
                        }
                        if ($match && isset($product['options-filters']) && is_array($product['options-filters']) && in_array($this->filter_correct, $product['autosygnals']) && empty(array_diff($active_params_arr, $product['options-filters']))) {
                            foreach ($product['options-filters'] as $filter) {
                                if (isset($this->filters_products_count[$filter])) {
                                    $this->filters_products_count[$filter]++;
                                } else {
                                    $this->filters_products_count[$filter] = 0;
                                }
                            }
                        }
                    } else {
                        $match = true;
                        $minCost = $this->get_params['min-value-cost'] ?? 0;
                        $maxCost = $this->get_params['max-value-cost'] ?? PHP_INT_MAX;
    
                        if ($product['price'] < $minCost || $product['price'] > $maxCost) {
                            $match = false;
                        }
                        $active_params_arr = [];
                        foreach ($this->get_params as $key => $value) {
                            if ($value === 'on') {
                                $active_params_arr[] = $key;
                            }
                        }
                        if ($match && isset($product['options-filters']) && is_array($product['options-filters']) && empty(array_diff($active_params_arr, $product['options-filters']))) {
                            foreach ($product['options-filters'] as $filter) {
                                if (isset($this->filters_products_count[$filter])) {
                                    $this->filters_products_count[$filter]++;
                                } else {
                                    $this->filters_products_count[$filter] = 1;
                                }
                            }
                        }
                    }

                }
            }
        }
        ob_start();
        ?>
            <div class="filter-basic">
                <div class="filter-basic__item">
                    <label for="vnedorojnik" class="filter-basic__item-element">
                    <input type="checkbox" class="filter-basic__checkbox" id="vnedorojnik" name="vnedorojnik">
                    <span class="filter-basic__item-title">Для внедорожника</span>
                    </label>
                    <span class="filter-basic__count"><?= $this->filters_products_count['vnedorojnik'] ?? 0?></span>
                </div>
                <div class="filter-basic__item">
                    <label for="legkoe-avto" class="filter-basic__item-element">
                    <input type="checkbox" class="filter-basic__checkbox" id="legkoe-avto" name="legkoe-avto">
                    <span class="filter-basic__item-title">Для легкое авто</span>
                    </label>
                    <span class="filter-basic__count"><?= $this->filters_products_count['legkoe-avto'] ?? 0?></span>
                </div>
            </div>
        <?php

        return ob_get_clean();
    }
    public function renderFilters($filterBasic = true, $filterCost = true, $filterFunctions = true, $pathSend = '/files/php/pages/catalog/catalog.php')
    {
        ob_start();
        ?>
        <button class="filter-button" type="button" id="filter-btn"
            style="background-image: url(<?= htmlspecialchars($this->path . '/assets/images/vectors/filters.svg'); ?>);">Фильтр</button>
        <button class="filter-button-close" type="button" id="filter-btn-close">
            <span class="visually-hidden">скрыть фильтры</span>
        </button>
        <form class="filter-form open" id="filter-catalog" action="<?= $this->path . $pathSend; ?>" method="get">
            <?php
                if ($filterBasic === true) {
                    echo $this->renderFiltersBasic();
                }

                if ($filterCost === true) {
                    echo $this->renderFiltersCost();
                }

                if ($filterFunctions === true) {
                    echo $this->renderFiltersFunctions();
                }
            ?>
            <div class="filters__buttons">
                <button type="submit" class="button y-button-primary">Применить</button>
                <button type="reset" class="button y-button-secondary">Сбросить</button>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }
}
?>