<?php
include_once __DIR__ . '/../../../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $docROOT . $variables->getPathFileURL();

include_once $path . '/files/php/data/filters.php';
include_once $path . '/files/php/data/products.php';
include_once $path . '/files/php/helpers/components/filters/filter-cost.php';
include_once $path . '/files/php/helpers/components/filters/filter-functions.php';
include_once $path . '/files/php/helpers/components/filters/filter-basic.php';


class Filters
{
    private $filterCost;
    private $filterFunctions;
    private $filterBasic;
    private $filter_correct;
    

    private $path;
    private $filters_products_count;

    public function __construct($products, $filter_correct = null)
    {
        $this->filter_correct =  $filter_correct;
        $this->filterCost = new FilterCost();
        $this->filterFunctions = new FilterFunctions($products, $filter_correct);
        $this->filterBasic = new FilterBasic();

        $variables = new SetVariables();
        $variables->setVar();
        $this->path = $variables->getPathFileURL();
        // Инициализация массива для подсчета товаров
        $this->filters_products_count = [];
        if (!empty($products)) {
            // Проходим по всем категориям и товарам
            foreach ($products['category'] as $items) {
                foreach ($items as $product) {
                    // Проверяем, есть ли у товара поле options-filters
                    if ($this->filter_correct !== null) {
                        if (isset($product['options-filters']) && is_array($product['options-filters']) && in_array($this->filter_correct, $product['autosygnals'])) {
                            // Проходим по каждому значению options-filters
                            foreach ($product['options-filters'] as $filter) {
                                // Увеличиваем счетчик для текущего фильтра
                                if (isset($this->filters_products_count[$filter])) {
                                    $this->filters_products_count[$filter]++;
                                } else {
                                    $this->filters_products_count[$filter] = 0;
                                }
                            }
                        }
                    } else {
                        if (isset($product['options-filters']) && is_array($product['options-filters'])) {
                            // Проходим по каждому значению options-filters
                            foreach ($product['options-filters'] as $filter) {
                                // Увеличиваем счетчик для текущего фильтра
                                if (isset($this->filters_products_count[$filter])) {
                                    $this->filters_products_count[$filter]++;
                                } else {
                                    $this->filters_products_count[$filter] = 0;
                                }
                            }
                        }
                    }

                }
            }
        }
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
            // Рендерим базовые фильтры
            if ($filterBasic === true) {
                echo $this->filterBasic->renderFilters($this->filters_products_count);
            }

        // Рендерим фильтры по цене
        if ($filterCost === true) {
            echo $this->filterCost->renderFilters();
        }

        // Рендерим фильтры по функциям
        if ($filterFunctions === true) {
            echo $this->filterFunctions->renderFilters();
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