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
$services_data_manager = new ServicesData();
$navigationLinks = new NavigationLinks();
$share = new Share();

$type = isset($_GET['service']) ? $_GET['service'] : null;

// Инициализируем массив service с базовыми значениями
$service = null;
if ($type) {
  $service = $services_data_manager->getServiceByType($type);
}

if (!$service) {
  $service = [
    'name' => 'Услуга не найдена',
    'image' => ['src' => '', 'description' => ''],
    'description' => 'Описание услуги не доступно.',
    'services' => '',
    'cost' => '',
    'currency' => ''
  ];
}

$title = isset($service['name']) && $service['name'] !== 'Услуга не найдена' ? $service['name'] . " | Auto Security" : "Услуга | Auto Security";
$head = new Head($title, [], []);

?>

<!DOCTYPE html>
<html lang="ru">
<?= $head->setHead(); ?>

<body>

  <?= $header->getHeader(); ?>
  <main class="main">
    <section class="service-setup" id="service-setup">
      <h2 class="service-setup__title"><?= htmlspecialchars($service['name']) ?></h2>
      <div class="service-setup__wrapper">
        <img class="service-setup__image" src="<?= htmlspecialchars($service['image']['src']) ?>"
          alt="<?= htmlspecialchars($service['image']['description']) ?>">

        <div class="service-setup__description"><?= $service['description'] ?></div>
      </div>
      <?php if (!empty($service['services'])): ?>
        <h3 class="service-setup__subtitle">Мы предлагаем:</h3>
        <div class="service-setup__list">
          <?= $service['services'] ?>
        </div>
      <?php endif; ?>
      <p class="service-setup__text">Стоимость услуг необходимо уточнять у мастера.</p>
      <p class="service-setup__text">Насладитесь комфортом с прекрасно установленным и настроенным нами оборудованием!
      </p>
      <?= $share->getShare(); ?>
      <?php if (!empty($service['cost'])): ?>
        <p class="service-setup__price">цена:
          <span><?= $service['cost'] ?></span><span><?= $service['currency']; ?></span>
        </p>
      <?php endif; ?>
      <button type="button" class="button y-button-primary" id="buy-btn">заказать</button>
    </section>
    <?= getShop('shop'); ?>
  </main>
  <?= $footer->getFooter(); ?>
  <?= (new ModalForm())->render(); ?>
  <?php echo renderPhoneButton();
  ?>
</body>

</html>