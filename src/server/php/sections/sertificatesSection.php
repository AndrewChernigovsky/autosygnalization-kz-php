<?php

namespace SECTIONS;

require_once __DIR__ . '/../../../server/vendor/autoload.php';

use DATA\SertificatesData;

function sertificatesSection(): string
{
  $certificates = (new SertificatesData())->getAllSertificates();
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
              <?php 
              $fileExtension = strtolower(pathinfo($certificate['image_path'], PATHINFO_EXTENSION));
              if ($fileExtension === 'pdf'): 
              ?>
                <a href="<?= htmlspecialchars($certificate['image_path']); ?>" 
                   class="fancybox quality-slider__image swiper-zoom-container pdf-preview-link"
                   data-fancybox="gallerySertificates" 
                   data-fancybox-index="<?= $index; ?>">
                  <img 
                    src="<?= htmlspecialchars($certificate['image_path']); ?>#page=1&zoom=page-fit&toolbar=0&navpanes=0&scrollbar=0" 
                    class="pdf-preview-iframe"
                    frameborder="0"
                    width="300"
                    height="400"
                    title="Сертификат компании Auto Security"
                  ></img>
                  <div class="pdf-overlay">
                    <span class="pdf-icon">📄</span>
                    <span class="pdf-text">Просмотр PDF</span>
                  </div>
                </a>
              <?php else: ?>
                <a href="<?= htmlspecialchars($certificate['image_path']); ?>" class="fancybox quality-slider__image swiper-zoom-container"
                  data-fancybox="gallerySertificates" data-fancybox-index="<?= $index; ?>">
                  <img src="<?= htmlspecialchars($certificate['image_path']); ?>" alt="Сертификат компании Auto Security." width="300"
                    height="400" />
                </a>
              <?php endif; ?>
              <a class="sertificates__download y-button-primary button" download
                href="<?= htmlspecialchars($certificate['pdf_path']); ?>">Скачать</a>
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
