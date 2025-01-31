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
          <h2 class="price__subtitle">Прайс по оборудованию Starline и цены на установку:*</h2>
          <ul class="price__list list-style-none">
            <?php
            foreach ($prices as $price): ?>
              <li class="price__item">
                    <details class="price__details product-cart">
                      <summary class="price__summary">
                        <p class="price__item-title" role="term" aria-details="faq-1">
                          <?php echo htmlspecialchars($price['title']); ?>
                        </p>
                        <div class="price__item-box">
                          <span class="price__item-product"><?php echo htmlspecialchars($price['productPrice']); ?></span>
                          <span class="price__item-currency"><?php echo htmlspecialchars($price['currency']); ?></span>
                        </div>
                        <p class="price__item-price">установка от <?php echo htmlspecialchars($price['installationPrice']) . ' ' . htmlspecialchars($price['currency']) . '*'; ?></p>
                      </summary>
                    </details>
                    <div class="price__content" id="faq-1" role="definition">
                      <div class="price__content-body">
                        <ul class="price__item-description">
                          <?php foreach ($price['description'] as $descItem): ?>
                            <li class="price__item-text"><?php echo htmlspecialchars($descItem); ?></li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    </div>
              </li>
            <?php endforeach; ?>
          </ul>
          <h3>Прайс на дополнительные услуги: *</h3>
          <ul>
            <?php 
              foreach ($pricesServices as $service): ?>
                <li>
                  <а><?php echo htmlspecialchars($service['title']); ?></а>
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