<?php

class Pagination
{
  private $items;

  public function __construct($items)
  {
    $this->items = $items;
  }

  public function init()
  {
    $html = '<ol class="pagination">';
    foreach ($this->items as $index => $item) {
      $href = isset($item['href']) ? $item['href'] : '#';
      $html .= '<li class="pagination__item"><a class="y-button-primary button link" href="' . $href . '">' . htmlspecialchars($index) . '</a></li>';
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
?>