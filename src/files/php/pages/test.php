<?php
$path_to_include = __DIR__ . '/../helpers/classes/setVariables.php';
if (!file_exists($path_to_include)) {
  die("Файл не найден: $path_to_include");
}
include $path_to_include;

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

    <p>
      js/modules/swiper.js <br>
      js/modules/swipers/swiper-offers.js
    </p>

    <article class="swiper swiper-offers">
      <ul class="swiper-wrapper">
        <li class="swiper-slide">1</li>
        <li class="swiper-slide">2</li>
        <li class="swiper-slide">3</li>
      </ul>
    </article>

  </main>

  <?php include $base_path . '/footer.php'; ?>
</body>

</html>