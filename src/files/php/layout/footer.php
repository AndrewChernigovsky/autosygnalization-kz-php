<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$requestUri = $_SERVER['REQUEST_URI'];
$currentUrl = $protocol . $host . $requestUri;

$distPath = $_SERVER['DOCUMENT_ROOT'] . '/dist';

// Проверяем, существует ли папка dist
if (is_dir($distPath)) {
  $currentUrl = "http://localhost:3000/dist/index.php";
  $pathFile = "http://localhost:3000/dist";
  $pathFile_URL = '/dist';
} else {
  $currentUrl = "/index.php";
  $pathFile = "";
  $pathFile_URL = '';
}


$distPath = $_SERVER['DOCUMENT_ROOT'] . '/dist';

// Проверяем, существует ли папка dist
if (is_dir($distPath)) {
  $currentUrl = $_SERVER['DOCUMENT_ROOT'] . '/dist' . '/files/php/data/contacts.php';
  $svgInclude = $_SERVER['DOCUMENT_ROOT'] . '/dist' . '/files/php/helpers/svg.php';
  $phone = $_SERVER['DOCUMENT_ROOT'] . '/dist' . '/files/php/helpers/phone.php';
} else {
  $currentUrl = $_SERVER['DOCUMENT_ROOT'] . '/files/php/data/contacts.php';
  $svgInclude = $_SERVER['DOCUMENT_ROOT'] . '/files/php/helpers/svg.php';
  $phone = $_SERVER['DOCUMENT_ROOT'] . '/files/php/helpers/phone.php';
}

include_once $currentUrl;
include_once $svgInclude;
include_once $phone;

$insertSVG = new InsertSVG();
$insertPHONE = new InsertPhone();
?>

<footer class="footer">
  <div class="container">
    <div class="footer__wrapper">
      <div class="logo">
        <a href="/" class="logo">
          <img src="<?php echo htmlspecialchars($pathFile_URL . '/assets/images/logo.avif'); ?>"
            alt="Логотип компании Auto Security." width="122" height="84" />
          <div class="text">
            <p class="text-main">Auto</p>
            <p class="text-secondary">Security</p>
          </div>
        </a>
        <div class="social">
          <p>Социальные сети</p>
          <?php $insertSVG->render($social) ?>
          <div class="phones">
            <?php $insertPHONE->displayPhones($contacts_phone) ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>