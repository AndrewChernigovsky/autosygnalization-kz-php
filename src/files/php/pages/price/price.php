<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';

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
    <div class="container">
      <h2>Наш прайс-лист</h2>
      <ul>
        <li>
          Услуга 1 <span>15000Тг.</span>
        </li>
        <li>
          Услуга 2 <span>45000Тг.</span>
        </li>
        <li>
          Услуга 3 <span>500Тг.</span>
        </li>
        <li>
          Услуга 4 <span>5500Тг.</span>
        </li>
      </ul>
      <div class="price__button">
        <a class="button y-button-primary" href="<?php echo $path . '/assets/pdf/Auto-Security-price-2025.pdf' ?>" download="Auto-Security-price-2025.pdf">Скачать прайс-лист</a>
      </div>
    </div>
  </main>
  <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
</body>

</html>