<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
$autoType = isset($_GET['service']) ? $_GET['service'] : null;

function getAutoContent($type)
{
  switch ($type) {
    case 'setup':
      return 'УСТАНОВКА И РЕМОНТ АВТОСИГНАЛИЗАЦИЙ.';
    case 'locks':
      return 'РЕМОНТ ЦЕНТРОЗАМКОВ';
    case 'setup-media':
      return 'УСТАНОВКА АВТОЗВУКА И МУЛЬТИМЕДИА.';
    case 'setup-system-parking':
      return 'УСТАНОВКА СИСТЕМ ПАРКИНГА';
    case 'autoelectric':
      return 'УСЛУГИ АВТОЭЛЕКТРИКА';
    case 'rus':
      return 'РУСИФИКАЦИЯ АВТО И ЧИПТЮНИНГ.';
    case 'diagnostic':
      return 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА';
    case 'disabled-autosynal':
      return 'ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ.';
    case 'setup-videoregistration':
      return 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ И АНТИРАДАРОВ.';
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