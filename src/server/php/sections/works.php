<?php
include_once __DIR__ . '/../../../server/php/helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$works = [
    [
        'image' => "$path/client/images/works/setup.avif",
        'title' => 'УСТАНОВКА И РЕМОНТ АВТОСИГНАЛИЗАЦИЙ',
        'href' => '/server/php/pages/service/service.php?service=setup'
    ],
    [
        'image' => "$path/client/images/works/setup-media.avif",
        'title' => 'УСТАНОВКА АВТОЗВУКА И МУЛЬТИМЕДИА',
        'href' => '/server/php/pages/service/service.php?service=setup-media'
    ],
    [
        'image' => "$path/client/images/works/locks.avif",
        'title' => 'РЕМОНТ ЦЕНТРОЗАМКОВ',
        'href' => '/server/php/pages/service/service.php?service=locks'
    ],
    [
        'image' => "$path/client/images/works/rus.avif",
        'title' => 'РУСИФИКАЦИЯ АВТО И ЧИПТЮНИНГ',
        'href' => '/server/php/pages/service/service.php?service=rus',
    ],
    [
        'image' => "$path/client/images/works/system-parking.avif",
        'title' => 'УСТАНОВКА СИСТЕМ ПАРКИНГА',
        'href' => '/server/php/pages/service/service.php?service=setup-system-parking'
    ],
    [
        'image' => "$path/client/images/works/autoelectric.avif",
        'title' => 'УСЛУГИ АВТОЭЛЕКТРИКА',
        'href' => '/server/php/pages/service/service.php?service=autoelectric'
    ],
    [
        'image' => "$path/client/images/works/diagnostic.avif",
        'title' => 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА',
        'href' => '/server/php/pages/service/service.php?service=diagnostic'
    ],
    [
        'image' => "$path/client/images/works/disabled-autosynal.avif",
        'title' => 'ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ',
        'href' => '/server/php/pages/service/service.php?service=disabled-autosynal'
    ],
    [
        'image' => "$path/client/images/works/setup-videoregistration.avif",
        'title' => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ И АНТИРАДАРОВ',
        'href' => '/server/php/pages/service/service.php?service=setup-videoregistration'
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
                        <a href="<?php echo htmlspecialchars($path . $work['href']); ?>" class="works__slide-button y-button-primary button">Подробнее</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="works__swiper-pagination swiper-pagination"></div>
        </div>
    </div>
</section>