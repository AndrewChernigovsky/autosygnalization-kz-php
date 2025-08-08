<?php

namespace SECTIONS;

use DATA\WorksData;

function worksSection(): string
{
    // Массив с данными о работах
    $works = (new WorksData())->getAllWorks();

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
                                <img src="<?= htmlspecialchars($work['image_path']); ?>" alt="Фото работы нашего сервиса" width="600" height="300" />
                            </div>
                            <a href="<?= htmlspecialchars($work['link']); ?>" class="works__slide-button y-button-primary button">Подробнее</a>
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
