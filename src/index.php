<?php
require_once __DIR__ . '/server/vendor/autoload.php';
use LAYOUT\Head;
use LAYOUT\Header;
use LAYOUT\Footer;
use COMPONENTS\ModalForm;
use DATABASE\InitDataBase;

use function SECTIONS\bankSection;
use function SECTIONS\introSection;
use function SECTIONS\marksSection;
use function SECTIONS\popularSection;
use function SECTIONS\qualitySection;
use function SECTIONS\sertificatesSection;
use function SECTIONS\serviceSection;
use function SECTIONS\worksSection;
use function SECTIONS\formSection;
use function FUNCTIONS\renderPhoneButton;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
// header('Content-Type: application/json');

$title = 'Главная | Auto Security';
$head = new Head($title, [], []);

?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <?= $head->setHead(); ?>
  <?php
  $initDataBase = new InitDataBase();
  // $initDataBase->init();
  ?>
</head>

<body>
  <?= (new Header())->getHeader() ?>
  <main class="main">
    <?php
    echo introSection();
    echo marksSection();
    echo popularSection();
    echo serviceSection();
    echo qualitySection();
    echo bankSection();
    echo sertificatesSection();
    echo worksSection();
    echo formSection();
    ?>
  </main>

  <?php
  echo (new Footer())->getFooter();
  echo (new ModalForm())->render();
  echo renderPhoneButton();
  ?>
</body>

</html>