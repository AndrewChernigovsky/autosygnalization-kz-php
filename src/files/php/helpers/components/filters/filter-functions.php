<?php
class FilterFunctions
{
    private $data_filters;
    private $products;

    public function __construct($data_filters, $products)
    {
        $this->data_filters = $data_filters;
        $this->products = $products;
    }

    /**
     * Подсчитывает количество товаров для каждого значения options-filters.
     *
     * @return array
     */
    public function countOptionsFilters()
    {
        $count = [];

        // Проходим по всем категориям и товарам
        foreach ($this->products['category'] as $items) {
            foreach ($items as $product) {
                // Проверяем, есть ли у товара поле options-filters
                if (isset($product['options-filters']) && is_array($product['options-filters'])) {
                    // Проходим по каждому значению options-filters
                    foreach ($product['options-filters'] as $filter) {
                        // Увеличиваем счетчик для текущего фильтра
                        if (isset($count[$filter])) {
                            $count[$filter]++;
                        } else {
                            $count[$filter] = 1;
                        }
                    }
                }
            }
        }
        error_log(print_r($count, true));
        return $count;
    }

    /**
     * Рендерит блок фильтров.
     *
     * @return string
     */
    public function renderFilters()
    {
        // Подсчитываем количество товаров для каждого фильтра
        $count = $this->countOptionsFilters();

        ob_start();
        ?>
        <div class="filter-functions">
            <p class="filter-functions__title">Функции</p>
            <?php foreach ($this->data_filters as $category): ?>
                <?php
                // Получаем значение 'options-filters' из $category
                $filterKey = $category['options-filters'] ?? null;

                // Если ключ существует в массиве $count, используем его значение, иначе 0
                $filterCount = 0;
                if (is_array($filterKey)) {
                    foreach ($filterKey as $key) {
                        $filterCount += $count[$key] ?? 0;
                        error_log(print_r($filterCount, true) . ' : filterCount');
                    }
                } else {
                    $filterCount = $count[$filterKey] ?? 0;
                }
                ?>
                <div class="filter-functions__item">
                    <label class="filter-functions__item-element">
                        <input type="checkbox" class="filter-functions__checkbox" id="<?= htmlspecialchars($category['name']) ?>"
                               name="<?= htmlspecialchars($category['name']) ?>">
                        <span class="filter-functions__item-title"><?= htmlspecialchars($category['text']) ?></span>
                    </label>
                    <span class="filter-functions__count"><?= $filterCount ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
?>