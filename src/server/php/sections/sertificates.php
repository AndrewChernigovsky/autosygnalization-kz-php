<?php
include_once __DIR__ . '/../../../server/php/helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$sertificates = [
  "$path/client/images/sertificates/sertificate-1.avif",
  "$path/client/images/sertificates/sertificate-2.avif",
  "$path/client/images/sertificates/sertificate-3.avif",
  "$path/client/images/sertificates/sertificate-4.avif",
  "$path/client/images/sertificates/sertificate-5.avif",
];
?>
<section class="sertificates">
  <div class="container">
    <h2 class="sertificates__title secondary-title">Сертификаты</h2>
    <div class="swiper swiper-sertificates">
      <ul class="swiper-wrapper sertificates__list list-style-none">
        <?php foreach ($sertificates as $index => $sertificate): ?>
          <li class="swiper-slide">
            <a href="<?php echo $sertificate; ?>" class="fancybox quality-slider__image swiper-zoom-container"
              data-fancybox="gallerySertificates" data-fancybox-index="0">
              <img src="<?php echo $sertificate; ?>" alt="Сертификат компании Auto Security." width="300" height="400" />
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
      <ul class="swiper-pagination swiper-sertificates__pagination list-style-none"></ul>
    </div>
  </div>
</section>