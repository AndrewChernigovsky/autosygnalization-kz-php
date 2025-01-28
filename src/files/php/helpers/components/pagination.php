<?php

include_once __DIR__ . '/../../helpers/classes/setVariables.php';

class Pagination
{
    private $items;
    private $variables;
    private $page;
    private $itemsPerPage; // Количество товаров на странице

    public function __construct($items, $itemsPerPage = 10)
    {
        $this->variables = new SetVariables();
        $this->variables->setVar();
        $this->items = $items;
        $this->page = $_GET['PAGE'] ?? 1;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function init()
    {
        $html = '<ol class="pagination">';

        // Общее количество товаров
        $totalItems = count($this->items);

        // Общее количество страниц
        $totalPages = ceil($totalItems / $this->itemsPerPage);

        // Ограничиваем количество отображаемых страниц (например, 5)
        $maxPaginationItems = 5;
        $startPage = max(1, min($this->page - floor($maxPaginationItems / 2), $totalPages - $maxPaginationItems + 1));
        $endPage = min($startPage + $maxPaginationItems - 1, $totalPages);

        // Получаем текущие GET-параметры, исключая параметр PAGE
        $queryParams = $_GET;
        unset($queryParams['PAGE']); // Удаляем PAGE, так как он будет изменяться

        // Генерация ссылок на страницы
        for ($i = $startPage; $i <= $endPage; $i++) {
            // Добавляем текущие GET-параметры к ссылке
            $queryParams['PAGE'] = $i; // Устанавливаем новый номер страницы
            $href = $this->variables->getPathFileURL() . '/files/php/pages/catalog/catalog.php?' . http_build_query($queryParams);
            $html .= '<li class="pagination__item"><a class="y-button-primary button link' . ($this->page == $i ? " active" : '') . '" href="' . $href . '">' . htmlspecialchars($i) . '</a></li>';
        }

        // Кнопка "Показать еще" (если есть больше страниц)
        if ($totalPages > $endPage) {
            $queryParams['PAGE'] = $endPage + 1; // Устанавливаем следующий номер страницы
            $href = $this->variables->getPathFileURL() . '/files/php/pages/catalog/catalog.php?' . http_build_query($queryParams);
            $html .= '<li class="pagination__item"><a class="y-button-primary button link" href="' . $href . '">Показать еще</a></li>';
        }

        $html .= '</ol>';
        return $html;
    }

    public function render()
    {
        echo $this->init();
    }

    public function getHtml()
    {
        return $this->init();
    }

    // Метод для получения товаров на текущей странице
    public function getPaginatedItems()
    {
        $offset = ($this->page - 1) * $this->itemsPerPage;
        return array_slice($this->items, $offset, $this->itemsPerPage);
    }
}
