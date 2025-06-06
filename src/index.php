<?php
require_once __DIR__ . '/server/vendor/autoload.php';

use LAYOUT\Head;
use LAYOUT\Header;
use LAYOUT\Footer;
use COMPONENTS\ModalForm;

use function SECTIONS\bankSection;
// use function SECTIONS\faqSection;
use function SECTIONS\introSection;
use function SECTIONS\marksSection;
use function SECTIONS\popularSection;
use function SECTIONS\qualitySection;
// use function SECTIONS\reasonsSection;
use function SECTIONS\sertificatesSection;
use function SECTIONS\serviceSection;
use function SECTIONS\worksSection;
use function SECTIONS\formSection;
use function FUNCTIONS\renderPhoneButton;

$title = 'Главная | Auto Security';
$head = new Head($title, [], []);

error_log(123123 . ' : ');

?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <?= $head->setHead(); ?>
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