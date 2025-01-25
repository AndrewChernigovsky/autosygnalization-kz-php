<?php

include_once __DIR__ . '/../../helpers/classes/setVariables.php';
class Pagination
{
    private $items;
    private $variables;
    private $page;
    public function __construct($items)
    {
        $this->variables = new SetVariables();
        $this->variables->setVar();
        $this->items = $items;
        $this->page = $_GET['PAGE'] ?? 1;
    }

    public function init()
    {
        $paginationIndex = 1;
        $html = '<ol class="pagination">';
        $maxPaginationItems = 5;

        $totalPaginationItems = 0;
        foreach ($this->items as $item) {
            foreach ($item as $item1) {
                $totalPaginationItems += count($item1);
                $maxPaginationItems = round($totalPaginationItems * 0.1);
            }
        }

        foreach ($this->items as $item) {
            foreach ($item as $item1) {
                foreach ($item1 as $item) {
                    if ($paginationIndex <= $maxPaginationItems) {
                        $href = $this->variables->getPathFileURL() . '/files/php/pages/catalog/catalog.php?PAGE=' . $paginationIndex;

                        error_log(message: $this->page . ' ' . $paginationIndex);
                        $html .= '<li class="pagination__item"><a class="y-button-primary button link' . ($this->page == $paginationIndex ? " active" : '') . '" href="' . $href . '">' . htmlspecialchars($paginationIndex) . '</a></li>';
                    }
                    $paginationIndex++;
                }
            }
        }

        if ($totalPaginationItems > 49) {
            $html .= '<li class="pagination__item"><a class="y-button-primary button link" href="#">Показать еще</a></li>';
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
}
