<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();
$docROOT = $variables->getDocRoot();
?>

<?php
$filesToInclude = [
  'navigationLinks' => $docROOT . "$path/files/php/data/navigation-links.php",
  'phoneContacts' => $docROOT . "$path/files/php/data/contacts.php",
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

$contacts = new Contacts();
$social = $contacts->getSocial();
$phones = $contacts->getPhones();
$email = $contacts->getEmail();
$web_site = $contacts->getWebsite();
$address = $contacts->getAddress();
?>

<header class="header">
  <div class="container">
    <div class="header-head">
      <div class="menu">
        <div class="logo">
          <a href="/" class="logo">
            <img src="<?php echo htmlspecialchars($path . '/assets/images/logo.avif'); ?>"
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
                  <use href="<?php echo $path . '/assets/images/vectors/sprite.svg#geo' ?>"></use>
                </svg>
              </div>
            </a>
            <address>
              <?php
              if (!empty($phones)) {
                foreach ($phones as $phone) {
                  $cleanedPhone = str_replace(' ', '', $phone['phone']);
                  echo '<a href="tel:' . htmlspecialchars($cleanedPhone) . '">' . htmlspecialchars($phone['phone']) . '</a>';
                }
              }
              ?>
              <span>
                <?php echo $address ?>
              </span>
            </address>
          </div>
          <div class="cart">
            <a class="link" href="<?php echo $path . '/files/php/pages/cart/cart.php' ?>">
              <svg width="50" height="50">
                <use href="<?php echo $path . '/assets/images/vectors/sprite.svg#cart' ?>"></use>
              </svg>
              <div class="counter">1</div>
            </a>
          </div>
          <div class="menu-toggle">
            <button type="button" id="btn-open-menu" class="button"><span class="visually-hidden">Открыть
                меню</span></button>
          </div>
        </div>
        <div class="menu-btns">
          <div class="search">
            <input type="search" placeholder="Поиск..." name="Поиск" />
          </div>
          <div class="phone">
            <svg width="50" height="50">
              <use href="<?php echo $path . '/assets/images/vectors/sprite.svg#phone' ?>"></use>
            </svg>
            <ul>
              <?php
              if (!empty($phones)) {
                foreach ($phones as $phone) {
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