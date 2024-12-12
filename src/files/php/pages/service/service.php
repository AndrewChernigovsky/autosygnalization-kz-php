<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
$autoType = isset($_GET['service']) ? $_GET['service'] : null;

function getAutoContent($type)
{
  switch ($type) {
    case 'setup':
      return 'Контент для установочного центра с автозапуском.';
    case 'locks':
      return 'Контент для установочного центра с GSM.';
    case 'setup-media':
      return 'Контент для установочного центра без автозапуска.';
    case 'setup-system-parking':
      return 'Контент для установочного центра каталога.';
    case 'autoelectric':
      return 'Контент для установочного центра аксессуары.';
    case 'rus':
      return 'Контент для установочного центра парковочные системы.';
    case 'diagnostic':
      return 'Контент для установочного центра цена.';
    case 'disabled-autosynal':
      return 'Контент для установочного центра цена.';
    case 'setup-videoregistration':
      return 'Контент для установочного центра цена.';
    case 'price':
      return 'Контент для установочного центра цена.';
    default:
      return 'Контент не найден.';
  }
}
$content = getAutoContent($autoType);
$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$sections_path = $docROOT . $path . '/files/php/helpers/include-sections.php';
include_once $head_path;
include_once $sections_path;
$base_path = $docROOT . $path . '/files/php/layout';

$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова';
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
    <h1>Автосигнализации</h1>
    <div>
      <?php echo htmlspecialchars($content); ?>
    </div>
  </main>
  <?php include $base_path . '/footer.php'; ?>
</body>

</html>