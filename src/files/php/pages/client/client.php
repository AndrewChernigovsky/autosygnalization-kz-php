<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
$autoType = isset($_GET['client']) ? $_GET['client'] : null;

function getAutoContent($type)
{
  switch ($type) {
    case 'special':
      return 'Контент для клиента с автозапуском.';
    case 'cart':
      return 'Контент для клиента с GSM.';
    case 'review':
      return 'Контент для клиента без автозапуска.';
    case 'gallery':
      return 'Контент для клиента каталога.';
    case 'map':
      return 'Контент для клиента аксессуары.';
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