<?php

namespace SECTIONS;

function worksSection(): string
{
    // Массив с данными о работах
    $works = [
        [
            'image' => "/client/images/works/setup.avif",
            'title' => 'УСТАНОВКА И РЕМОНТ АВТОСИГНАЛИЗАЦИЙ',
            'href' => '/service?service=setup'
        ],
        [
            'image' => "/client/images/works/setup-media.avif",
            'title' => 'УСТАНОВКА АВТОЗВУКА И МУЛЬТИМЕДИА',
            'href' => '/service?service=setup-media'
        ],
        [
            'image' => "/client/images/works/locks.avif",
            'title' => 'РЕМОНТ ЦЕНТРОЗАМКОВ',
            'href' => '/service?service=locks'
        ],
        [
            'image' => "/client/images/works/rus.avif",
            'title' => 'РУСИФИКАЦИЯ АВТО И ЧИПТЮНИНГ',
            'href' => '/service?service=rus',
        ],
        [
            'image' => "/client/images/works/system-parking.avif",
            'title' => 'УСТАНОВКА СИСТЕМ ПАРКИНГА',
            'href' => '/service?service=setup-system-parking'
        ],
        [
            'image' => "/client/images/works/autoelectric.avif",
            'title' => 'УСЛУГИ АВТОЭЛЕКТРИКА',
            'href' => '/service?service=autoelectric'
        ],
        [
            'image' => "/client/images/works/diagnostic.avif",
            'title' => 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА',
            'href' => '/service?service=diagnostic'
        ],
        [
            'image' => "/client/images/works/disabled-autosynal.avif",
            'title' => 'ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ',
            'href' => '/service?service=disabled-autosynal'
        ],
        [
            'image' => "/client/images/works/setup-videoregistration.avif",
            'title' => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ И АНТИРАДАРОВ',
            'href' => '/service?service=setup-videoregistration'
        ],
    ];

    // Генерация HTML
    ob_start();
    ?>
    <section class="works">
        <div class="container">
            <h2 class="works__title secondary-title">Наши работы</h2>
            <div class="works__swiper swiper swiper-works">
                <div class="works__swiper-wrapper swiper-wrapper">
                    <?php foreach ($works as $work): ?>
                        <div class="swiper-slide works__slide">
                            <h3 class="works__slide-title"><?= htmlspecialchars($work['title']); ?></h3>
                            <div class="works__slide-image swiper-zoom-container">
                                <img src="<?= htmlspecialchars($work['image']); ?>" alt="Фото работы нашего сервиса" width="600" height="300" />
                            </div>
                            <a href="<?= htmlspecialchars($work['href']); ?>" class="works__slide-button y-button-primary button">Подробнее</a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="works__swiper-pagination swiper-pagination"></div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
