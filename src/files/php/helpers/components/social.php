<?php
class InsertSVG
{
  public function render($icons)
  {
    foreach ($icons as $icon) {
      if (isset($icon['name']) && isset($icon['href']) && isset($icon['image']) && isset($icon['width']) && isset($icon['height'])) {
        echo "
                    <a href='{$icon['href']}'>
                    <span class='visually-hidden'>{$icon['name']}</span>
                        <svg width='{$icon['width']}' height='{$icon['height']}'>      
                            <use href='{$icon['image']}'></use>
                        </svg>
                    </a>
                ";
      } else {
        echo "<!-- Icon data is missing -->";
      }
    }
  }
}
?>