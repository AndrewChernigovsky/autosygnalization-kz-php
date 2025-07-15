<?php

namespace SECTIONS;

require_once __DIR__ . '/../../../server/vendor/autoload.php';

use DATA\SertificatesData;

function sertificatesSection(): string
{
  $certificates = (new SertificatesData())->getData();
  // Массив с ссылками на сертификаты

  // Генерация HTML
  ob_start();
  ?>
  <section class="sertificates">
    <div class="container">
      <h2 class="sertificates__title secondary-title">Сертификаты</h2>
      <div class="swiper swiper-sertificates">
        <ul class="swiper-wrapper sertificates__list list-style-none">
          <?php foreach ($certificates as $index => $certificate): ?>
            <li class="sertificates__slide swiper-slide">
              <a href="<?= htmlspecialchars($certificate); ?>" class="fancybox quality-slider__image swiper-zoom-container"
                data-fancybox="gallerySertificates" data-fancybox-index="<?= $index; ?>">
                <img src="<?= htmlspecialchars($certificate); ?>" alt="Сертификат компании Auto Security." width="300"
                  height="400" />
              </a>
              <a class="sertificates__download y-button-primary button" download
                href="/client/docs/sertificates/sertificate-<?= $index + 1 ?>.pdf">Скачать</a>
            </li>
          <?php endforeach; ?>
        </ul>
        <ul class="swiper-pagination swiper-sertificates__pagination list-style-none"></ul>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean();
}
