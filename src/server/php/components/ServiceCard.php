<?php

namespace COMPONENTS;

class ServiceCard
{
    public function initCard($card)
    {
        ob_start();
        ?>
<div class="service-card" id="service-card">
    <h3 class="service-card__title third-title"><?php echo htmlspecialchars($card['name'] ?? ''); ?></h3>
    <img src="<?php echo htmlspecialchars($card['image']['src'] ?? ''); ?>"
        alt="<?php echo htmlspecialchars($card['image']['description'] ?? '') . ' ' . htmlspecialchars($card['name'] ?? ''); ?>"
        width="300" height="400">
    <div class="service-card__buttons">
        <a class="y-button-secondary button animated-button"
            href="<?php echo htmlspecialchars($card['href'] ?? '#'); ?>">Подробнее</a>
        <button type="button" class="y-button-primary button buy-btn">Заказать</button>
    </div>
</div>
    <?php
          return ob_get_clean();
    }

}

?>
