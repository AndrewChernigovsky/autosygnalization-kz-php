<?php

namespace SECTIONS;

use COMPONENTS\ServiceCard;
use DATA\ServicesData;
use HELPERS\Services;

function serviceSection(): string
{
    // Получение данных о услугах
    $servicesData = (new ServicesData())->getData();
    $data = new Services($servicesData);
    $services = array_values($data->getServices());
    $card = new ServiceCard();

    // Генерация HTML
    ob_start();
    ?>
    <section class="service" id="service">
        <div class="container">
            <h2 class="service__title secondary-title">наши услуги</h2>
            <div class="swiper swiper-service">
                <ul class="service__list service__list--component list-style-none swiper-wrapper">
                    <?php foreach ($services as $service): ?>
                        <li class="service__item swiper-slide">
                            <?= $card->initCard($service); ?>
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
    return ob_get_clean();
}
