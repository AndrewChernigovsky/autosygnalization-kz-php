<?php
$path_to_include = __DIR__ . '/../helpers/classes/setVariables.php';
if (!file_exists($path_to_include)) {
  die("Файл не найден: $path_to_include");
}
include $path_to_include;
include __DIR__ . '/../data/products.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
include $head_path;

$base_path = $docROOT . $path . '/files/php/layout';

$title = 'Для Денчика | Auto Security';
$head = new Head($title, [], []);
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include $base_path . '/header.php'; ?>

  <main class="main">
    
    <article class="swiper swiper-offers offers">
      <ul class="offers--list swiper-wrapper">
        <li class="offers--item swiper-slide">
          <h4 class="offers--item__heading">Специальные предложения</h4>
          <p class="offers--item__name">StarLine S96 V2 BT 2CAN2LIN GSM</p>
          <p class="offers--item__price">113600</p>
          <img class="offers--item__image" src="../../../assets/images/products/starline_s96/StarLine-S96-V2-BT-2CAN-2LIN-GSM.avif" alt="starline S-96" height="300" width="200">
          <a class="y-button-secondary button offers--item__more">Подробнее</a>
          <a class="offers--item__all" href="#">Все предложения</a>
        </li> 
        <li class="offers--item swiper-slide">
          <h4 class="offers--item__heading">Специальные предложения</h4>
          <p class="offers--item__name">Starline E96 V2 BT GSM</p>
          <p class="offers--item__price">147100</p>
          <img class="offers--item__image" src="../../../assets/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN.avif" alt="starline S-96" height="200" width="300">
          <a class="y-button-secondary button offers--item__more">Подробнее</a>
          <a class="offers--item__all" href="#">Все предложения</a>
        </li>
        <li class="offers--item swiper-slide">
          <h4 class="offers--item__heading">Специальные предложения</h4>
          <p class="offers--item__name">Starline A93 ECO V2</p>
          <p class="offers--item__price">64900</p>
          <img class="offers--item__image" src="../../../assets/images/products/starline_a93/keychain-Starline-A93-ECO-V2.avif" alt="starline S-96" height="200" width="300">
          <a class="y-button-secondary button offers--item__more">Подробнее</a>
          <a class="offers--item__all" href="#">Все предложения</a>
        </li>
        <li class="offers--item swiper-slide">
          <h4 class="offers--item__heading">Специальные предложения</h4>
          <p class="offers--item__name">StarLine E96 V2 BT ECO 2CAN+4LIN</p>
          <p class="offers--item__price">99800</p>
          <img class="offers--item__image" src="../../../assets/images/products/starline_e96/starline-E96-V2-BT-ECO-2CAN-4LIN.avif" alt="starline S-96" height="200" width="300">
          <a class="y-button-secondary button offers--item__more">Подробнее</a>
          <a class="offers--item__all" href="#">Все предложения</a>
        </li>
      </ul>

      <div class="swiper-button-prev swiper-button-prev-offers"></div>
      <div class="swiper-button-next swiper-button-next-offers"></div>
    </article>

  </main>

  <?php include $base_path . '/footer.php'; ?>
  
</body>

</html>