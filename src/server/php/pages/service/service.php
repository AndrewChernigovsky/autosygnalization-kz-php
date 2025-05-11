<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\NavigationLinks;
use DATA\ServicesData;
use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;
use COMPONENTS\ServiceCard;
use COMPONENTS\ModalForm;
use COMPONENTS\Share;
use HELPERS\Services;
use function FUNCTIONS\renderPhoneButton;
use function FUNCTIONS\getShop;

$header = new Header();
$footer = new Footer();

$services_data = (new ServicesData())->getData();
$service_data = new Services($services_data);
$services = array_values($service_data->getServices());
$navigationLinks = new NavigationLinks();
$card = new ServiceCard();

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

$title = $services_data[$type]['name'] . "| Auto Security";
$head = new Head($title, [], []);
$share = new Share();
?>

<!DOCTYPE html>
<html lang="ru">
<?= $head->setHead(); ?>

<body>

  <?= $header->getHeader(); ?>
  <main class="main"></main>
  <section class="service-setup" id="service-setup">
    <h2 class="service-setup__title"><?= htmlspecialchars($service['name']) ?></h2>
    <div class="service-setup__wrapper">
      <img class="service-setup__image" src="<?= htmlspecialchars($service['image']['src']) ?>"
        alt="<?= htmlspecialchars($service['image']['description']) ?>">

      <div class="service-setup__description"><?= $service['description'] ?></div>
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
    <p class="service-setup__price">цена: <span><?= $service['cost'] ?></span><span><?= $service['currency']; ?></span>
    </p>
    <button type="button" class="button y-button-primary" id="buy-btn">заказать</button>
  </section>
  <?= getShop('setup'); ?>
  </main>
  <?= $footer->getFooter(); ?>
  <?= (new ModalForm())->render(); ?>
  <?php echo renderPhoneButton();
  ?>
</body>

</html>