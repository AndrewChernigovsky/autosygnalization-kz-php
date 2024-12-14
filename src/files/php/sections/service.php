<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';
include_once __DIR__ . '/../helpers/components/service.php';
include_once __DIR__ . '/../data/services.php';
include_once __DIR__ . '/../helpers/classes/services.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();
$service_data = new Services($services_data);
$services = $service_data->getServices();
?>
<section class="service" id="service">
  <div class="container">
    <h2 class="service__title secondary-title">наши услуги</h2>
    <div class="swiper swiper-service">
      <ul class="service__list list-style-none swiper__wrapper">
        <?php foreach ($services as $index => $service): ?>
          <li class="service__item swiper-slide">
            <?php
            $card = new ServiceCard();
            echo $card->initCard($service, $index);
            ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>