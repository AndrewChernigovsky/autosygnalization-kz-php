<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();
?>

<section class="autosygnals">
  <div class="container">
    <h3 class="autosygnals__title">Автосигнализации</h3>
    <div class="autosygnals__wrapper swiper">
      <ul class="autosygnals__list list-style-none swiper-wrapper">
        <li class="autosygnals__item swiper-slide">
          <h3 class="autosygnals__item-title">Автосигнализации с автозапуском</h3>
          <div class="autosygnals__item-block"
            style="background-image: url('/dist/assets/images/services/service-1.png')">
            <p class="autosygnals__item-count y-button-secondary">
              <span class="autosygnals__item-counter">11</span> товаров
            </p>
            <a class="autosygnals__item-link link y-button-primary" href="#">ПЕРЕЙТИ В РАЗДЕЛ</a>
          </div>
        </li>
        <li class="autosygnals__item swiper-slide">
          <h3 class="autosygnals__item-title">Автосигнализации с GSM</h3>
          <div class="autosygnals__item-block"
            style="background-image: url('/dist/assets/images/services/service-2.png')">
            <p class="autosygnals__item-count link y-button-secondary" href="#">
              <span class="autosygnals__item-counter">8</span> товаров
            </p>
            <a class="autosygnals__item-link link y-button-primary " href="#">ПЕРЕЙТИ В РАЗДЕЛ</a>
          </div>
        </li>
        <li class="autosygnals__item swiper-slide">
          <h3 class="autosygnals__item-title">Автосигнализации без автозапуска</h3>
          <div class="autosygnals__item-block"
            style="background-image: url('/dist/assets/images/services/service-3.png')">
            <p class="autosygnals__item-count link y-button-secondary" href="#">
              <span class="autosygnals__item-counter">3</span> товаров
            </p>
            <a class="autosygnals__item-link link y-button-primary" href="#">ПЕРЕЙТИ В РАЗДЕЛ</a>
          </div>
        </li>
        <li class="autosygnals__item swiper-slide">
          <h3 class="autosygnals__item-title">Каталог автосигнализаций Starline</h3>
          <div class="autosygnals__item-block"
            style="background-image: url('/dist/assets/images/services/service-4.png')">
            <p class="autosygnals__item-count link y-button-secondary" href="#">
              <span class="autosygnals__item-counter">8</span> товаров
            </p>
            <a class="autosygnals__item-link link y-button-primary" href="#">ПЕРЕЙТИ В РАЗДЕЛ</a>
          </div>
        </li>
        <li class="autosygnals__item swiper-slide">
          <h3 class="autosygnals__item-title">Пульты и аксессуары</h3>
          <div class="autosygnals__item-block"
            style="background-image: url('/dist/assets/images/services/service-5.png')">
            <p class="autosygnals__item-count link y-button-secondary" href="#">
              <span class="autosygnals__item-counter">15</span> товаров
            </p>
            <a class="autosygnals__item-link link y-button-primary" href="#">ПЕРЕЙТИ В РАЗДЕЛ</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</section>
<?php
include_once __DIR__ . '/../../helpers/components/setup.php';
?>