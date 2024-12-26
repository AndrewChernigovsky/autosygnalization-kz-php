<?php
include __DIR__ . '/../../helpers/classes/setVariables.php';
$autoType = isset($_GET['service']) ? $_GET['service'] : null;

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . "/files/php/layout/head.php";
$sections_path = $docROOT . $path . '/files/php/helpers/include-sections.php';
include $head_path;
include_once $sections_path;
$base_path = $docROOT . $path . '/files/php/layout';
function getContent($base_path, $type)
{
  include $base_path . '/header.php';
  "<main class='main'>";
  "<div>";
  include "./$type.php";
  "</div>";
  "</main>";
  include $base_path . '/footer.php';
}
function getAutoContent($type, $base_path)
{
  switch ($type) {
    case 'setup':
      return getContent($base_path, 'setup');
    case 'locks':
      return getContent($base_path, 'locks');
    case 'setup-media':
      return getContent($base_path, 'setup-media');
    case 'setup-system-parking':
      return getContent($base_path, 'setup-system-parking');
    case 'autoelectric':
      return getContent($base_path, 'autoelectric');
    case 'rus':
      return getContent($base_path, 'rus');
    case 'diagnostic':
      return getContent($base_path, 'diagnostic');
    case 'disabled-autosynal':
      return getContent($base_path, 'disabled-autosynal');
    case 'setup-videoregistration':
      return getContent($base_path, 'setup-videoregistration');
    default:
      return getContent($base_path, 'default');
  }
}
$content = getAutoContent($autoType, $base_path);
$title = 'Установка и ремонт автосигнализаций | Auto Security';
$head = new Head($title, [], []);
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php echo htmlspecialchars($content); ?>
</body>

</html>