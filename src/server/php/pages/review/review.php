<?php
include_once __DIR__ . '/../../api/sessions/session.php';
use HELPERS\SetVariables;
include_once __DIR__ . '/../../data/aboutus.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/server/php/layout/head.php';
$title = 'О нас | Auto Security';

include_once $head_path;

$head = new Head($title, [], []);

$aboutUs = new AboutusData();
?>


<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include_once $docROOT . $path . '/server/php/layout/header.php'; ?>
  <main class="main">
    <div class="container">
      <h2>Оставить отзыв о работе с нами</h2>
    </div>
  </main>
  <?php include_once $docROOT . $path . '/server/php/layout/footer.php'; ?>
</body>

</html>