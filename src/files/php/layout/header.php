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
$filesToInclude = [
  'navigationLinks' => $_SERVER['DOCUMENT_ROOT'] . "$pathFile_URL/files/php/data/navigation-links.php",
  'phoneContacts' => $_SERVER['DOCUMENT_ROOT'] . "$pathFile_URL/files/php/data/contacts.php",
];

foreach ($filesToInclude as $key => $filePath) {
  if (file_exists($filePath)) {
    include_once $filePath;
  } else {
    echo "Файл не найден: $filePath";
  }
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
            <a href="https://maps.app.goo.gl/72eQCZUbxVCKh43PA" class="geo-image link">
              <div class="image">
                <svg width="50" height="50">
                  <use href="<?php echo $pathFile_URL . '/assets/images/vectors/sprite.svg#geo' ?>"></use>
                </svg>
              </div>
            </a>
            <address>
              <?php
              if (!empty($contacts_phone)) {
                foreach ($contacts_phone as $phone) {
                  echo $phone;
                  $cleanedPhone = str_replace(' ', '', $phone['phone']);
                  echo '<a href="tel:' . htmlspecialchars($cleanedPhone) . '">' . htmlspecialchars($phone['phone']) . '</a>';
                }
              }
              ?>
              <span>
                Казахстан, г.Алматы, ул.Абая 145/г, бокс №15
              </span>
            </address>
          </div>
          <div class="cart">
            <a class="link" href="<?php echo $pathFile_URL . '/files/php/pages/cart/cart.php' ?>">
              <svg width="50" height="50">
                <use href="<?php echo $pathFile_URL . '/assets/images/vectors/sprite.svg#cart' ?>"></use>
              </svg>
              <div class="counter">1</div>
            </a>
          </div>
          <div class="menu-toggle">
            <button></button>
          </div>
        </div>
        <div class="menu-btns">
          <div class="search">
            <input type="search" placeholder="Поиск..." name="Поиск" />
          </div>
          <div class="phone">
            <svg width="50" height="50">
              <use href="<?php echo $pathFile_URL . '/assets/images/vectors/sprite.svg#phone' ?>"></use>
            </svg>
            <ul>
              <?php
              if (!empty($contacts_phone)) {
                foreach ($contacts_phone as $phone) {
                  $cleanedPhone = str_replace(' ', '', $phone['phone']);
                  echo '<li><a href="tel:' . htmlspecialchars($cleanedPhone) . '">' . htmlspecialchars($phone['phone']) . '</a></li>';
                }
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>