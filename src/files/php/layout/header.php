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

?>

<?php
$navLinks = $_SERVER['DOCUMENT_ROOT'] . "$pathFile_URL/files/php/data/navigation-links.php";

if (file_exists($navLinks)) {
  include_once $navLinks;
} else {
  echo "Файл не найден: $navLinks";
}

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function isActive($linkPath, $currentPath)
{
  return $linkPath === $currentPath ? 'active' : '';
}
?>

<header class="header">
  <div class="container">
    <div class="header-head">
      <div class="menu">
        <div class="menu-desktop">
          <div class="logo">
            <a href="/" class="logo">
              <img src="<?php echo htmlspecialchars($pathFile_URL . '/assets/images/logo.avif'); ?>"
                alt="Логотип компании Auto Security." width="122" height="84" />
              <div class="text">
                <p class="text-main">Auto</p>
                <p class="text-secondary">Security</p>
              </div>
            </a>
          </div>
          <nav class="nav">
            <ul class="nav-list list-type-none">
              <?php
              foreach ($navigationLinks as $link) {
                echo '<li class="nav-item">
            <a class="nav-link link ' . isActive($link['path'], $currentPath) . '" href="' . htmlspecialchars($link['path']) . '">' . htmlspecialchars($link['name']) . '</a>
          </li>';
              }
              ?>
            </ul>
          </nav>
          <div class="contacts">
            <div class="geo">
              <a href="https://maps.app.goo.gl/72eQCZUbxVCKh43PA" class="geo-image">
                <div class="image">
                  <svg width="25" height="25">
                    <use href="<?php echo $pathFile_URL . '/assets/images/vectors/sprite.svg#geo' ?>"></use>
                  </svg>
                </div>
              </a>
              <address>
                <a href="tel:+77077478212">+7 707 747 8212'</a>
                <a href="tel:77017478212">+7 701 747 8212</a>
                <span>
                  Казахстан, г.Алматы, ул.Абая 145/г, бокс №15
                </span>
              </address>
            </div>
            <div class="cart">
              <a class="link" href="/cart">
                <svg width="25" height="25">
                  <use href="<?php echo $pathFile_URL . '/assets/images/vectors/sprite.svg#cart' ?>"></use>
                </svg>
                <div class="counter">1</div>
              </a>
            </div>
          </div>
          <div class="menu-btns">
            <div class="search">
              <input type="search" placeholder="Поиск..." name="Поиск" />
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</header>