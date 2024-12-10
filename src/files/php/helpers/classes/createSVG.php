<?php
class createSVG
{
  // Метод для вставки иконок с текстом
  public function insertIconText($icons)
  {
    $output = ''; // Инициализируем переменную для хранения HTML-кода

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

    return $output; // Возвращаем собранный HTML-код
  }

  // Метод для вставки SVG
  public function insertSvg($icon)
  {
    return "<svg width='{$icon['width']}' height='{$icon['height']}'>      
                    <use href='{$icon['image']}'></use>
                </svg>"; // Возвращаем строку с SVG-кодом
  }
}
?>