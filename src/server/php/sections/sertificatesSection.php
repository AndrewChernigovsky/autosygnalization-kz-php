<?php

namespace SECTIONS;

function sertificatesSection(): string
{
    // Массив с ссылками на сертификаты
    $certificates = [
        "/client/images/sertificates/sertificate-1.avif",
        "/client/images/sertificates/sertificate-2.avif",
        "/client/images/sertificates/sertificate-3.avif",
        "/client/images/sertificates/sertificate-4.avif",
        "/client/images/sertificates/sertificate-5.avif",
    ];

    // Генерация HTML
    ob_start();
    ?>
    <section class="sertificates">
        <div class="container">
            <h2 class="sertificates__title secondary-title">Сертификаты</h2>
            <div class="swiper swiper-sertificates">
                <ul class="swiper-wrapper sertificates__list list-style-none">
                    <?php foreach ($certificates as $index => $certificate): ?>
                        <li class="swiper-slide">
                            <a href="<?= htmlspecialchars($certificate); ?>" class="fancybox quality-slider__image swiper-zoom-container"
                               data-fancybox="gallerySertificates" data-fancybox-index="<?= $index; ?>">
                                <img src="<?= htmlspecialchars($certificate); ?>" alt="Сертификат компании Auto Security."
                                     width="300" height="400" />
                            </a>
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
