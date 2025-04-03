<?php
namespace COMPONENTS;
use HELPERS\SetVariables;

class Pagination
{
    private $items;
    private $variables;
    private $page;
    private $itemsPerPage;
    private $path;

    public function __construct($items, $itemsPerPage = 10)
    {
        $this->variables = new SetVariables();
        $this->variables->setVar();
        $this->items = $items;
        $this->page = $_GET['PAGE'] ?? 1;
        $this->itemsPerPage = $itemsPerPage;
        $this->path = str_replace('/dist', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }

    public function init() {
        $html = '<ol class="pagination">';
 
        $maxPaginationPageItems = 5;
        $queryParams = $_GET;
        unset($queryParams['PAGE']);

        $totalItems = count($this->items);

        if ($totalItems == 0) {
             return '';
        };

        $totalPages = ceil($totalItems / $this->itemsPerPage);
        $startPage = max(1, min($this->page - floor($maxPaginationPageItems / 2), $totalPages - $maxPaginationPageItems + 1));
        $endPage = min($startPage + $maxPaginationPageItems - 1, $totalPages);

        

        for ($i = $startPage; $i <= $endPage; $i++) {
            $queryParams['PAGE'] = $i;
            $href = $this->variables->getPathFileURL() . $this->path . '?' .  http_build_query($queryParams);
            $html .= '<li class="pagination__item"><a class="y-button-primary button link' . ($this->page == $i ? " active" : '') . '" href="' . $href . '">' . htmlspecialchars($i) . '</a></li>';
        }

        if ($totalPages > $endPage) {
            $queryParams['PAGE'] = $endPage + 1;
            $href = $this->variables->getPathFileURL() . $this->path . '?' . http_build_query($queryParams);
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

    public function getPaginatedItems()
    {
        $offset = ($this->page - 1) * $this->itemsPerPage;
        return array_slice($this->items, $offset, $this->itemsPerPage);
    }
}
