<?php
include_once __DIR__ . '/../../api/sessions/session.php';
use HELPERS\SetVariables;

$autoType = isset($_GET['client']) ? $_GET['client'] : null;

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/server/php/layout/head.php';
$sections_path = $docROOT . $path . '/server/php/helpers/include-sections.php';
include_once $head_path;
include_once $sections_path;
$base_path = $docROOT . $path . '/server/php/layout';
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
    case 'special':
      return getContent($base_path, 'special');
    case 'review':
      return getContent($base_path, 'review');
    default:
      return getContent($base_path, 'default');
  }
}

$content = getAutoContent($autoType, $base_path);
$title = 'Клиенту | Auto Security';
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