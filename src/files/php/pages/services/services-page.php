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

$head_path = $_SERVER['DOCUMENT_ROOT'] . $pathFile_URL . '/files/php/layout/head.php';
$sections_path = $_SERVER['DOCUMENT_ROOT'] . $pathFile_URL . '/files/php/helpers/include-sections.php';
include_once $head_path;
include_once $sections_path;
$base_path = $_SERVER['DOCUMENT_ROOT'] . $pathFile_URL . '/files/php/layout';

$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова';
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
    <h2>Услуги</h2>
  </main>
  <?php include $base_path . '/footer.php'; ?>
</body>

</html>