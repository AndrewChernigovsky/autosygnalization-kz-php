<?php
include_once __DIR__ . '/../../api/sessions/session.php';
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
$path_2 = $docROOT . $path;
function getContent($base_path, $path_2, $type)
{
  include $base_path . '/header.php';
  "<main class='main'>";
  "<div>";
  include "./$type.php";
  "</div>";
  "</main>";
  include $base_path . '/footer.php';
  include $path_2 . '/files/php/helpers/components/phone-button.php';
  include $path_2 . '/files/php/sections/popups/modal-form.php';
}
function getAutoContent($type, $base_path, $path_2)
{
  switch ($type) {
    case 'setup':
      return getContent($base_path, $path_2, 'setup');
    case 'locks':
      return getContent($base_path, $path_2, 'locks');
    case 'setup-media':
      return getContent($base_path, $path_2, 'setup-media');
    case 'setup-system-parking':
      return getContent($base_path, $path_2, 'setup-system-parking');
    case 'autoelectric':
      return getContent($base_path, $path_2, 'autoelectric');
    case 'rus':
      return getContent($base_path, $path_2, 'rus');
    case 'diagnostic':
      return getContent($base_path, $path_2, 'diagnostic');
    case 'disabled-autosynal':
      return getContent($base_path, $path_2, 'disabled-autosynal');
    case 'setup-videoregistration':
      return getContent($base_path, $path_2, 'setup-videoregistration');
    default:
      return getContent($base_path, $path_2, 'default');
  }
}
$content = getAutoContent($autoType, $base_path, $path_2);
$title = 'Установка и ремонт автосигнализаций | Auto Security';
$head = new Head($title, [], []);
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php echo htmlspecialchars($content ?? 'Услуги пока не определены'); ?>
</body>

</html>