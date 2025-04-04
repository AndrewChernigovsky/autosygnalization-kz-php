<?php
use COMPONENTS\Share;
use DATA\Services;

use function FUNCTIONS\getShop;

$type = isset($_GET['service']) ? $_GET['service'] : null;

if ($type && isset($services_data[$type])) {
    $service = $services_data[$type];
} else {
    $service = [
      'name' => 'Услуга не найдена',
      'src' => '',
      'description' => 'Описание услуги не доступно.'
    ];
}

$share = new Share();
?>

<section class="service-setup" id="service-setup">
  <h2 class="service-setup__title"><?= htmlspecialchars($service['name']) ?></h2>
  <div class="service-setup__wrapper">
    <img class="service-setup__image" src="<?= htmlspecialchars($service['image']['src']) ?>"
      alt="<?= htmlspecialchars($service['image']['description']) ?>">
    <p class="service-setup__description"><?= htmlspecialchars($service['description']) ?></p>
  </div>
  <h3 class="service-setup__subtitle">Мы предлагаем:</h3>
  <ul class="service-setup__list list-style-none">
    <?php foreach ($service['services'] as $item): ?>
      <li class="service-setup__item" style="background-image: url(<?= '/client/vectors/checkbox-mark-icon.svg'; ?>);">
        <?= htmlspecialchars($item); ?>
      </li>
    <?php endforeach; ?>
  </ul>
  <p class="service-setup__text">Стоимость услуг необходимо уточнять у мастера.</p>
  <p class="service-setup__text">Насладитесь комфортом с прекрасно установленным и настроенным нами оборудованием!</p>
  <?= $share->getShare(); ?>
  <p class="service-setup__price">цена: <span><?= $service['cost'] ?></span><span><?= $service['currency']; ?></span></p>
  <button type="button" class="button y-button-primary" id="buy-btn">заказать</button>
</section>

<?php
echo getShop('shop');
?>