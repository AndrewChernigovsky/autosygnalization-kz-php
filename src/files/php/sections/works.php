<?php
include_once __DIR__ . '/../../../files/php/helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$works = [
    [
        'image' => "$path/assets/images/works/setup.avif",
        'title' => 'УСТАНОВКА И РЕМОНТ АВТОСИГНАЛИЗАЦИЙ',
        'href' => '/files/php/pages/service/service.php?service=setup'
    ],
    [
        'image' => "$path/assets/images/works/setup-media.avif",
        'title' => 'УСТАНОВКА АВТОЗВУКА И МУЛЬТИМЕДИА',
        'href' => '/files/php/pages/service/service.php?service=setup-media'
    ],
    [
        'image' => "$path/assets/images/works/locks.avif",
        'title' => 'РЕМОНТ ЦЕНТРОЗАМКОВ',
        'href' => '/files/php/pages/service/service.php?service=locks'
    ],
    [
        'image' => "$path/assets/images/works/rus.avif",
        'title' => 'РУСИФИКАЦИЯ АВТО И ЧИПТЮНИНГ',
        'href' => '/files/php/pages/service/service.php?service=rus',
    ],
    [
        'image' => "$path/assets/images/works/system-parking.avif",
        'title' => 'УСТАНОВКА СИСТЕМ ПАРКИНГА',
        'href' => '/files/php/pages/service/service.php?service=setup-system-parking'
    ],
    [
        'image' => "$path/assets/images/works/autoelectric.avif",
        'title' => 'УСЛУГИ АВТОЭЛЕКТРИКА',
        'href' => '/files/php/pages/service/service.php?service=autoelectric'
    ],
    [
        'image' => "$path/assets/images/works/diagnostic.avif",
        'title' => 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА',
        'href' => '/files/php/pages/service/service.php?service=diagnostic'
    ],
    [
        'image' => "$path/assets/images/works/disabled-autosynal.avif",
        'title' => 'ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ',
        'href' => '/files/php/pages/service/service.php?service=disabled-autosynal'
    ],
    [
        'image' => "$path/assets/images/works/setup-videoregistration.avif",
        'title' => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ И АНТИРАДАРОВ',
        'href' => '/files/php/pages/service/service.php?service=setup-videoregistration'
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