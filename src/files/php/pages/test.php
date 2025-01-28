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
$path_href = $path . '/files/php/pages/catalog/catalog.php?special=special';

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
    <section class="offers">
      <div class="swiper swiper-offers">
        <h3 class="offers__heading">Специальное предложение</h3>
        <ul class="offers__list list-style-none swiper-wrapper">
          <?php if (!empty($products['category'])): ?>  
            <?php foreach ($products['category']['keychain'] as $product): ?>
              <?php if ($product['popular']): ?>
                <li class="offers__item swiper-slide">
                  <img class="offers__image" src="<?php echo htmlspecialchars($product['gallery'][0]); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" height="300" width="200">
                  <h4 class="offers__title"><?= htmlspecialchars($product['title']); ?></h4>
                  <p class="offers__wrapper">
                    <span class="offers__price"><?= htmlspecialchars($product['price']); ?></span>
                    <span class="offers__currency"><?= htmlspecialchars($product['currency']); ?></span>
                  </p>
                  <a class="y-button-secondary button offers__link-more" href="<?= htmlspecialchars($product['link']); ?>">Подробнее</a>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
        <div class="offers__container">
          <a class="offers__link-all" href="<?= htmlspecialchars($path_href); ?>">Все предложения</a>
          <div class="offers__buttons">
            <button class="offers__button offers__button--prev swiper-button-prev-offers" type="button"></button>
            <button class="offers__button offers__button--next swiper-button-next-offers" type="button"></button>
          </div>
        </div>
      </div>
    </section>
  </main>
  <?php include $base_path . '/footer.php'; ?>  
</body>
</html>
