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

    public function init($href_page)
    {
        $html = '<ol class="pagination">';

        if ($href_page === 'catalog') {
            $href_page = '/files/php/pages/catalog/catalog.php?';
        }
        if ($href_page === 'parking') {
            $href_page = '/files/php/pages/parking-systems/parking-systems.php?';
        }
        if ($href_page === 'autosygnals-auto') {
            $href_page = '/files/php/pages/autosygnals/autosygnals-auto.php?';
        }
        if ($href_page === 'autosygnals-gsm') {
            $href_page = '/files/php/pages/autosygnals/autosygnals-gsm.php?';
        }
        if ($href_page === 'autosygnals-without-auto') {
            $href_page = '/files/php/pages/autosygnals/autosygnals-without-auto.php?';
        }
        if ($href_page === 'autosygnals-starline') {
            $href_page = '/files/php/pages/autosygnals/autosygnals-starline.php?';
        }
        if ($href_page === 'autosygnals-acssesuars') {
            $href_page = '/files/php/pages/autosygnals/autosygnals-acssesuars.php?';
        }




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
            $href = $this->variables->getPathFileURL() . $href_page . http_build_query($queryParams);
            $html .= '<li class="pagination__item"><a class="y-button-primary button link' . ($this->page == $i ? " active" : '') . '" href="' . $href . '">' . htmlspecialchars($i) . '</a></li>';
        }

        // Кнопка "Показать еще" (если есть больше страниц)
        if ($totalPages > $endPage) {
            $queryParams['PAGE'] = $endPage + 1; // Устанавливаем следующий номер страницы
            $href = $this->variables->getPathFileURL() . $href_page . http_build_query($queryParams);
            $html .= '<li class="pagination__item"><a class="y-button-primary button link" href="' . $href . '">Показать еще</a></li>';
        }

        $html .= '</ol>';
        return $html;
    }

    public function render($href = 'catalog')
    {
        echo $this->init($href);
    }

    public function getHtml($href = 'catalog')
    {
        return $this->init($href);
    }

    // Метод для получения товаров на текущей странице
    public function getPaginatedItems()
    {
        $offset = ($this->page - 1) * $this->itemsPerPage;
        return array_slice($this->items, $offset, $this->itemsPerPage);
    }
}
