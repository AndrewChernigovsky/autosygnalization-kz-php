<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/prices.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$title = 'Прайс-лист | Auto Security';

include_once $head_path;
$head = new Head($title, [], []);
?>


<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include_once $docROOT . $path . '/files/php/layout/header.php'; ?>
  <main class="main">
    <section class="price">
      <div class="container">
        <div class="price__wrapper">
          <h1 class="price__title">Прайс</h1>
          <h2 class="price__subtitle">Прайс по оборудованию Starline и цены на установку: <span>*</span></h2>
          <ul class="price__list list-style-none">
            <?php
            foreach ($prices as $price): ?>
              <li class="price__item">
                  <h3 class="price__item-title"><?php echo htmlspecialchars($price['title']) . ' - ' . htmlspecialchars($price['productPrice']) . ' ' . htmlspecialchars($price['currency']);?></h3>
                  <p class="price__item-description"><?php echo htmlspecialchars($price['description']); ?></p>
                  <p class="price__item-price">Установка от <?php echo htmlspecialchars($price['installationPrice']) . ' ' . htmlspecialchars($price['currency']) . ' *' ?></p>
              </li>
          <?php endforeach; ?>
          </ul>
          <div class="price__button">
            <a class="button y-button-primary" href="<?php echo $path . '/files/docs/Auto-Security-price-2025.pdf' ?>" download="Auto-Security-price-2025.pdf">Скачать прайс-лист</a>
          </div>
        </div>
      </div>
    </section>
  </main>
  <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
</body>

</html>