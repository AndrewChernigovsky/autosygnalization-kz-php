<?php
include_once __DIR__ . '/files/php/api/sessions/session.php';
include __DIR__ . '/files/php/helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$sections_path = $docROOT . $path . '/files/php/helpers/include-sections.php';
include_once $head_path;
include_once $sections_path;
$base_path = $docROOT . $path . '/files/php/layout';
$path_components = $docROOT . $path . '/files/php/helpers/components';

$title = 'Главная | Auto Security';
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
    <?php
    $file_2_section = './files/php/sections/';
    $files_to_include = [
      'intro.php',
      'marks.php',
      'popular.php',
      'bank.php',
      'service.php',
      'quality.php',
      'sertificates.php',
      'form.php',
    ];

    $sectionLoader = new IncludeSections($file_2_section, $files_to_include);
    $sectionLoader->includeFiles();
    ?>
  </main>

  <?php include $base_path . '/footer.php'; ?>
  <?php include_once $path_components . '/phone-button.php'; ?>
  <?php include_once $file_2_section . '/popups/modal-form.php'; ?>
</body>

</html>