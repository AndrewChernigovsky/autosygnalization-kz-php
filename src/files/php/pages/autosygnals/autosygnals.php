<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';

$autoType = isset($_GET['auto']) ? $_GET['auto'] : null;

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$sections_path = $docROOT . $path . '/files/php/helpers/include-sections.php';
include_once $head_path;
include_once $sections_path;
$base_path = $docROOT . $path . '/files/php/layout';
function getContent($base_path, $type)
{
  include $base_path . '/header.php';
  "<main class='main'>";
  "<div>";
  include_once "./$type.php";
  include_once './../../helpers/components/setup.php';
  "</div>";
  "</main>";
  include $base_path . '/footer.php';
}
function getAutoContent($type, $base_path)
{
  switch ($type) {
    case 'auto':
      return getContent($base_path, 'auto');
    case 'gsm':
      return getContent($base_path, 'gsm');
    case 'no-auto':
      return getContent($base_path, 'no-auto');
    case 'catalog':
      return getContent($base_path, 'catalog');
    case 'accessories':
      return getContent($base_path, 'acessories');
    case 'price':
      return getContent($base_path, 'gsm');
    default:
      return getContent($base_path, 'default');
  }
}

$content = getAutoContent($autoType, $base_path);
$title = 'Автосигнализации | Auto Security';
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