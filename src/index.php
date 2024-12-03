<?php


$head_path = './files/php/layout/head.php';
$sections_path = './files/php/helpers/includeSections.php';
include_once $head_path;
include_once $sections_path;

$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова';
$canonical = "<link rel='canonical' href='https://xn----7sbbihceda5ae9bf1bg0j.xn--p1ai/'";
$head = new Head($title, [], [$canonical]);
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php
  $base_path = __DIR__ . '/files/php/layout';

  include $base_path . '/header.php';
  ?>
  <main class="main">
    <?php
    $file_2_section = './files/php/sections/';
    $files_to_include = [
      $file_2_section . 'intro.php',
      $file_2_section . 'advantages.php',
      $file_2_section . 'prices.php',
      $file_2_section . 'tarifs.php',
      $file_2_section . 'about.php',
      $file_2_section . 'reasons.php',
      $file_2_section . 'quality.php',
      $file_2_section . 'cost/cost.php',
      $file_2_section . 'examples/examples.php',
      $file_2_section . 'form.php',
      $file_2_section . 'reviews/reviews.php',
      $file_2_section . 'faq.php',
    ];

    $sectionLoader = new IncludeSections(__DIR__, $files_to_include);
    $sectionLoader->includeFiles();
    ?>

  </main>
  <?php
  $base_path = __DIR__ . '/files/php/layout';

  include $base_path . '/footer.php';

  $filesModal_to_include = [
    'files/php/sections/popups/modal-form.php',
  ];

  $sectionLoader = new IncludeSections(__DIR__, $filesModal_to_include);
  $sectionLoader->includeFiles();
  ?>
</body>

</html>