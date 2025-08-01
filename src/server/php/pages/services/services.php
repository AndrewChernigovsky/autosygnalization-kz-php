<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\NavigationLinks;
use DATA\ServicesData;
use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;
use COMPONENTS\ServiceCard;
use COMPONENTS\ModalForm;
use HELPERS\Services;
use function FUNCTIONS\renderPhoneButton;
use function FUNCTIONS\getShop;

$services_data = (new ServicesData())->getData();

$service_data = new Services($services_data);
$services = array_values($service_data->getServices());
$navigationLinks = new NavigationLinks();
$card = new ServiceCard();

$title = 'Автосигнализации';
$header = new Header();
$footer = new Footer();
$head = new Head($title, [], []);
error_log(print_r($services, true) . ' SSS');
?>

<!DOCTYPE html>
<html lang="ru">
<?= $head->setHead(); ?>

<body>

  <?= $header->getHeader(); ?>
  <main class="main">
    <section class="service" id="service">
      <div class="container">
        <h2 class="service__title1">наши услуги</h2>
        <div class="swiper swiper-service">
          <ul class="service__list service__list--component list-style-none swiper-wrapper component">
            <?php foreach ($services[0] as $index => $service): ?>
              <li class="service__item swiper-slide">
                <?php
                echo $card->initCard($service);
                ?>
              </li>
            <?php endforeach; ?>
            <li>
              <ul class="swiper-pagination swiper-service__pagination"></ul>
            </li>
          </ul>
        </div>
      </div>
    </section>
    <?= getShop('shop'); ?>
  </main>
  <?= $footer->getFooter(); ?>
  <?= (new ModalForm())->render(); ?>
  <?php echo renderPhoneButton();
  ?>
</body>

</html>