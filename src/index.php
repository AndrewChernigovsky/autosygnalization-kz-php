<?php

$path_to_include = __DIR__ . '/files/php/helpers/classes/setVariables.php';
error_log($path_to_include . ' путь к setVar');
if (!file_exists($path_to_include)) {
  die("Файл не найден: $path_to_include");
} else {
  include $path_to_include;
}


$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$sections_path = $docROOT . $path . '/files/php/helpers/include-sections.php';
include $head_path;
include $sections_path;
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
      'works.php',
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