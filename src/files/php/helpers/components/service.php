<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
class ServiceCard
{
  private $variables;

  public function __construct()
  {
    $this->variables = new SetVariables();
    $this->variables->setVar();
  }

  public function initCard($card, $index)
  {
    $path = $this->variables->getPathFileURL();
    $output = '';
    $output .= '<a class="service-card" href="' . htmlspecialchars($path . $card['href']) . '">';
    $output .= '<h3 class="service-card__title third-title">' . htmlspecialchars($card['name']) . '</h3>';

    // Исправление формулы для индекса и формирование пути к изображению
    $output .= '<img src="' . htmlspecialchars($path . htmlspecialchars($card['src']) . '-' . ($index + 1) . '.avif') . '" alt="' . htmlspecialchars($card['description']) . ' ' . htmlspecialchars($card['name']) . '" width="300" height="400">';

    // Исправление кавычек в ссылке
    $output .= '<div class="service-card__buttons">';
    $output .= '<a class="y-button-secondary button animated-button" href="' . htmlspecialchars($path . $card['href']) . '">Подробнее</a>';

    // Исправление кавычек в кнопке
    $output .= '<button type="button" class="y-button-primary button">Заказать</button>';

    $output .= '</div>';
    $output .= '</a>';
    return $output;
  }
}

?>