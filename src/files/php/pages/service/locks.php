<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../helpers/components/share.php';
include_once __DIR__ . '/../../data/services.php';

$type = isset($_GET['service']) ? $_GET['service'] : null;

$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();

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
  <div class="container">
    <h2 class="service-setup__title"><?= htmlspecialchars($service['name']) ?></h2>
    <div class="service-setup service-setup--image">
      <img src="<?= htmlspecialchars($path . $service['image']['src']) ?>"
        alt="<?= htmlspecialchars($service['image']['description']) ?>">
      <p class="service-setup__description"><?= htmlspecialchars($service['description']) ?></p>
    </div>
    <ul>
      <?php foreach ($service['services'] as $item): ?>
        <li style="background-image: url(<?= $path . 'assets/images/vectors/checkbox.svg'; ?>);">
          <?= htmlspecialchars($item); ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <p>Стоимость услуг необходимо уточнять у мастера.</p>
    <p>Насладитесь комфортом с прекрасно установленным и настроенным нами оборудованием!</p>
    <?= $share->getShare(); ?>
    <p>цена: <span><?= $service['cost'] ?></span><span><?= $service['currency']; ?></span></p>
    <button type="button" class="button y-button-primary" id="buy-btn">заказать</button>
  </div>
</section>

<?php
include_once __DIR__ . '/../../helpers/components/setup.php';
echo getShop('shop');
?>