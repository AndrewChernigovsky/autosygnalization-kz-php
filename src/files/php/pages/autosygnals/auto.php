<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/navigation-links.php';

$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();
$autosygnals = $categories_autosygnals;
?>

<section class="autosygnals" id="autosygnals">
  <div class="container">
    <h2 class="autosygnals__title">Автосигнализации</h2>
    <div class="autosygnals__wrapper swiper swiper-autosygnals">
      <ul class="autosygnals__list list-style-none swiper-wrapper">
        <?php foreach ($autosygnals as $slide): ?>
          <li class="autosygnals__item swiper-slide">
            <div class="autosygnals__item-card">
              <h3 class="autosygnals__item-title"><?php echo $slide['name'] ?></h3>
              <img class="autosygnals__item-image" src="<?php echo $slide['src'] ?>"
                alt="Картинка на которой изображена услуга Auto Security Автосигнализации с автозапуском" width="640"
                height="554">
              <div class="autosygnals__item-block">
                <p class="autosygnals__item-count">
                  <span class="autosygnals__item-counter"><?php echo $slide['count'] ?></span> товаров
                </p>
                <a class="autosygnals__item-link link y-button-primary" href="<?php echo $slide['link'] ?>">В РАЗДЕЛ</a>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  </div>
</section>
<?php
include_once __DIR__ . '/../../helpers/components/setup.php';
echo getShop('setup');
?>