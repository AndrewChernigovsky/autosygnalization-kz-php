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

  public function initCard($card)
  {
    $path = $this->variables->getPathFileURL();
    error_log(print_r($card, true) . ' 111');
    ob_start();
    ?>
    <div class="service-card">
      <h3 class="service-card__title third-title"><?php echo htmlspecialchars($card['name']); ?></h3>
      <img src="<?php echo htmlspecialchars($path . htmlspecialchars($card['image']['src'])); ?>"
        alt="<?php echo htmlspecialchars($card['image']['description']) . ' ' . htmlspecialchars($card['name']); ?>"
        width="300" height="400">
      <div class="service-card__buttons">
        <a class="y-button-secondary button animated-button"
          href="<?php echo htmlspecialchars($path . $card['href']); ?>">Подробнее</a>
        <button type="button" class="y-button-primary button">Заказать</button>
      </div>
      </в>
      <?php
      return ob_get_clean();
  }

}

?>