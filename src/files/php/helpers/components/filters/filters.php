<?php
include_once __DIR__ . '/../../../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $docROOT . $variables->getPathFileURL();
error_log(print_r($_SERVER['SCRIPT_NAME'],true) . "Это path");

include_once $path . '/files/php/data/products.php';


class FiltersRender
{
    
    private $path;
    private $get_params;
    private $filters_correct;
    private $filters_correct_arr;
    private $filters_products_count;
    private $filters_basic_arr;
    private $filters_function_arr;
    private $filters_min_value;
    private $filters_max_value;
    private $SELECT;
    private $path_send;

    private array $products;

    public function __construct(array $products = [], $filters_correct = null, $filters_function_arr = null, $filters_basic_arr = null, $get_params = null)
    {

        $this->filters_function_arr = !empty($filters_function_arr) ? $filters_function_arr : [
            ["text" => "Автозапуск", "name" => "autosetup", "count" => "1"],
            ["text" => "УПРАВЛЕНИЕ С ТЕЛЕФОНА", "name" => "control-phone", "count" => "1"],
            ["text" => "БЕСПЛАТНЫЙ МОНИТОРИНГ", "name" => "free-monitoring", "count" => "1"],
            ["text" => "УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART", "name" => "bluetooth-smart", "count" => "1"],
            ["text" => "БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN", "name" => "block-engine-can", "count" => "1"],
            ["text" => "УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ", "name" => "control-before-start", "count" => "1"],
            ["text" => "ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА", "name" => "data-level-bensin", "count" => "1"],
            ["text" => "УМНАЯ АВТОДИАГНОСТИКА", "name" => "smart-diagnostic", "count" => "1"],
        ];

        $this->filters_basic_arr = !empty($filters_basic_arr) ? $filters_basic_arr : [
            ["text" => "ДЛЯ ВНЕДОРОЖНИКА", "name" => "vnedorojnik", "count" => "1"],
            ["text" => "ДЛЯ ЛЕГКОВОГО АВТО", "name" => "legkoe-avto", "count" => "1"],
        ];

        $this->get_params = !empty($get_params) ? $get_params : $_GET;
        $this->filter_correct = $filters_correct;

        $variables = new SetVariables();
        $variables->setVar();
        $this->path = $variables->getPathFileURL();
        $this->products = $products;
        $this->filters_products_count = [];
        $this->filters_correct_arr = [];
        $this->filters_min_value = $this->get_params['min-value-cost'] ?? 100;
        $this->filters_max_value = $this->get_params['max-value-cost'] ?? 300000;
        $this->SELECT = $this->get_params['SELECT'] ?? '';
        $this->path_send = str_replace('/dist', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }

    public function returnCorrectedArr() {

        $count = [];
        if (!$this->get_params && $this->filter_correct === null) {
            foreach ($this->products as $categoryType) {
                if (!empty($categoryType)) {
                    foreach ($categoryType as $productType) {
                        if (!empty($productType)) {
                            foreach ($productType as $productTypeItem) {
                                $this->filters_correct_arr[] = $productTypeItem;
                                if (!empty($productTypeItem['options-filters'])) {
                                    foreach ($productTypeItem['options-filters'] as $counterName) {
                                        $count[$counterName] = ($count[$counterName] ?? 0) + 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } elseif ($this->get_params && $this->filter_correct !== null) {
            $this->filters_correct_arr = [];
            foreach ($this->products as $categoryType) {
                if (!empty($categoryType)) {
                    foreach ($categoryType as $productType) {
                        if (!empty($productType)) {
                            foreach ($productType as $productTypeItem) {
                                if (!empty($productTypeItem)) {
                                    $isMatch = true;
                                    $active_params_arr = [];
                                    if (isset($productTypeItem['price']) && ($productTypeItem['price'] < $this->filters_min_value || $productTypeItem['price'] > $this->filters_max_value)) {
                                        $isMatch = false;
                                        continue;
                                    }
                                    foreach ($this->get_params as $key => $value) {
                                        if ($value === 'on') {
                                            $active_params_arr[] = $key;
                                        }
                                    }

                                    if(!isset($productTypeItem['options-filters']) || !empty(array_diff($active_params_arr, $productTypeItem['options-filters']))) {
                                        $isMatch = false;
                                    }
                                    
                                    if (!isset($productTypeItem['autosygnals']) || (!is_array($productTypeItem['autosygnals']) || !in_array($this->filter_correct,$productTypeItem['autosygnals']))) {
                                        $isMatch = false;
                                    }

                                    if ($isMatch) {
                                        $this->filters_correct_arr[] = $productTypeItem;
                                        foreach ($productTypeItem['options-filters'] as $counterName) {
                                            $count[$counterName] = ($count[$counterName] ?? 0) + 1;
                                        }
                                    }

                                }
                            }
                        }
                    }
                }
            }
        } elseif ($this->get_params && $this->filter_correct === null) {
            $this->filters_correct_arr = [];
            foreach ($this->products as $categoryType) {
                if (!empty($categoryType)) {
                    foreach ($categoryType as $productType) {
                        if (!empty($productType)) {
                            foreach ($productType as $productTypeItem) {
                                if (!empty($productTypeItem)) {
                                    $isMatch = true;
                                    $active_params_arr = [];
                                    if (isset($productTypeItem['price']) && ($productTypeItem['price'] < $this->filters_min_value || $productTypeItem['price'] > $this->filters_max_value)) {
                                        $isMatch = false;
                                        continue;
                                    }
                                    foreach ($this->get_params as $key => $value) {
                                        if ($value === 'on') {
                                            $active_params_arr[] = $key;
                                        }
                                    }
                          
                                    if(!isset($productTypeItem['options-filters']) || !empty(array_diff($active_params_arr, $productTypeItem['options-filters']))) {
                                        $isMatch = false;
                                    }
                                    
                                    if ($isMatch) {
                                        $this->filters_correct_arr[] = $productTypeItem;
                                        foreach ($productTypeItem['options-filters'] as $counterName) {
                                            $count[$counterName] = ($count[$counterName] ?? 0) + 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } elseif (!$this->get_params && $this->filter_correct !== null) {
            $this->filters_correct_arr = [];
            foreach ($this->products as $categoryType) {
                if (!empty($categoryType)) {
                    foreach ($categoryType as $productType) {
                        if (!empty($productType)) {
                            foreach ($productType as $productTypeItem) {
                                if (!empty($productTypeItem)) {
                                    $isMatch = true;
                                    if (!isset($productTypeItem['autosygnals']) || (!is_array($productTypeItem['autosygnals']) || !in_array($this->filter_correct,$productTypeItem['autosygnals']))) {
                                        $isMatch = false;
                                    }
                                    if ($isMatch) {
                                        $this->filters_correct_arr[] = $productTypeItem;
                                        foreach ($productTypeItem['options-filters'] as $counterName) {
                                            $count[$counterName] = ($count[$counterName] ?? 0) + 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        foreach ($this->filters_function_arr as $index => $category) {
            $filterKey = $category['name'];
            $this->filters_function_arr[$index]['count'] = $count[$filterKey] ?? 0;
        }
        foreach ($this->filters_basic_arr as $index => $category) {
            $filterKey = $category['name'];
            $this->filters_basic_arr[$index]['count'] = $count[$filterKey] ?? 0;
        }
        if (!empty($this->SELECT)) {
            if($this->SELECT === 'name') {
                usort($this->filters_correct_arr, function ($a, $b) {
                    $nameA = $a['title'] ?? '';
                    $nameB = $b['title'] ?? '';
                    return strcmp(mb_strtolower($nameA), mb_strtolower($nameB));
                });
            }
            if($this->SELECT === 'price') {
                usort($this->filters_correct_arr, function ($a, $b) {
                    $priceA = $a['price'] ?? 0;
                    $priceB = $b['price'] ?? 0;
                    return $priceB <=> $priceA;
                });
            }
        }
        return $this->filters_correct_arr;
    }

    public function renderFiltersFunctions() {
    
        ob_start();
        ?>
        <div class="filter-functions">
            <p class="filter-functions__title">Функции</p>
            <?php foreach ($this->filters_function_arr as $category): ?>
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

    public function renderFiltersCost() {
        ob_start();
        ?>
            <div class="double-range-wrapper filter-cost">
                <div class="range-slider-container">
                    <div class="range-container">
                        <div class="range-scale">
                            <div class="range-progress"></div>
                        </div>
                        <div class="range-wrapper">
                            <input type="range" class="range-input range-min" min="100" max="300000" step="100" name="min-range-cost"/>
                            <input type="range" class="range-input range-max" min="100" max="300000" step="100" name="max-range-cost"/>
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

    public function renderFiltersBasic() {
       
        ob_start();
        ?>
        <div class="filter-basic">
            <?php foreach ($this->filters_basic_arr as $category): ?>
                <div class="filter-basic__item">
                    <label class="filter-basic__item-element">
                        <input type="checkbox" class="filter-basic__checkbox"  id="<?= htmlspecialchars($category['name']) ?>"
                        name="<?= htmlspecialchars($category['name']) ?>">
                        <span class="filter-basic__item-title"><?= htmlspecialchars($category['text']) ?></span>
                    </label>
                    <span class="filter-basic__count"><?= $category['count'] ?></span>
                </div>
                <?php endforeach; ?>
        </div>
           
        <?php

        return ob_get_clean();
    }

    public function renderFilters($filterBasic = true, $filterCost = true, $filterFunctions = true) {
        ob_start();
        ?>
        <button class="filter-button" type="button" id="filter-btn"
            style="background-image: url(<?= htmlspecialchars($this->path . '/assets/images/vectors/filters.svg'); ?>);">Фильтр</button>
        <button class="filter-button-close" type="button" id="filter-btn-close">
            <span class="visually-hidden">скрыть фильтры</span>
        </button>
        <form class="filter-form open" id="filter-catalog" action="<?= $this->path . $this->path_send; ?>" method="get">
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