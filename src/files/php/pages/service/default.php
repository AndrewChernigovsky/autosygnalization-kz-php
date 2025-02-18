<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/navigation-links.php';
include_once __DIR__ . '/../../helpers/components/service.php';
include_once __DIR__ . '/../../data/services.php';
include_once __DIR__ . '/../../helpers/classes/services.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();
$service_data = new Services($services_data);
$services = array_values($service_data->getServices());
$navigationLinks = new NavigationLinks();
$card = new ServiceCard();
?>

<section class="service" id="service">
  <div class="container">
    <h2 class="service__title">наши услуги</h2>
    <div class="swiper swiper-service">
      <ul class="service__list service__list--component list-style-none swiper-wrapper component">
        <?php foreach ($services as $index => $service): ?>
          <li class="service__item swiper-slide">
            <?php
            error_log(print_r($service, true) . ' 222');
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
<?php
include_once __DIR__ . '/../../helpers/components/setup.php';
echo getShop('setup');
?>