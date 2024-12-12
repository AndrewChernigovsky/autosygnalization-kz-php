<?php
class CreateSVG
{
  public function insertIconText($icons)
  {
    $output = '';

    foreach ($icons as $icon) {
      if (isset($icon['name']) && isset($icon['href']) && isset($icon['image']) && isset($icon['width']) && isset($icon['height'])) {
        $output .= "
                    <a href='{$icon['href']}'>
                        <span class='visually-hidden'>{$icon['name']}</span>
                        <svg width='{$icon['width']}' height='{$icon['height']}'>      
                            <use href='{$icon['image']}'></use>
                        </svg>
                    </a>
                ";
      } else {
        $output .= "<!-- Icon data is missing -->";
      }
    }

    return $output;
  }

  public function insertSvg($icon)
  {
    return "<svg width='{$icon['width']}' height='{$icon['height']}'>      
                    <use href='{$icon['image']}'></use>
                </svg>";
  }
}
?>