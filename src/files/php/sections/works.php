<?php
include_once __DIR__ . '/../../../files/php/helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$works = [
    [
        'image' => "$path/assets/images/services/service-1.png",
        'title' => 'Русификация'
    ],
    [
        'image' => "$path/assets/images/services/service-3.png",
        'title' => 'Установка сигнализации'
    ],
    [
        'image' => "$path/assets/images/services/service-4.png",
        'title' => 'Установка паркинга'
    ],
    [
        'image' => "$path/assets/images/services/service-5.png",
        'title' => 'бла бла'
    ],
    [
        'image' => "$path/assets/images/services/service-2.png",
        'title' => 'Установка чего-то там'
    ],
];
?>
<section class="works">
    <div class="container">
        <h2 class="works__title secondary-title">Наши работы</h2>
        <div class="works__swiper swiper swiper-works">
            <div class="works__swiper-wrapper swiper-wrapper">
                <?php foreach ($works as $work): ?>
                    <div class="swiper-slide works__slide">
                        <h3 class="works__slide-title"><?php echo $work['title']; ?></h3>
                        <div class="works__slide-image swiper-zoom-container">
                            <img src="<?php echo $work['image']; ?>" alt="Фото работы нашего сервиса" width="600" height="300" />
                        </div>
                        <a href="#" class="works__slide-button y-button-primary button">Подробнее</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="works__swiper-pagination swiper-pagination"></div>
        </div>
    </div>
</section>