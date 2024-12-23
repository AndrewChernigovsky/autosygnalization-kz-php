<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';
include_once __DIR__ . '/../helpers/components/cart.php';
include_once __DIR__ . '/../data/products.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();
$docROOT = $variables->getDocRoot();

$cart = new Cart();
$quantity = 0;
?>

<?php
$filesToInclude = [
  'navigationLinks' => $docROOT . "$path/files/php/data/navigation-links.php",
  'phoneContacts' => $docROOT . "$path/files/php/data/contacts.php",
  'logo' => $docROOT . "$path/files/php/helpers/components/logo.php",
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
$phones = $contacts->getPhones();
$email = $contacts->getEmail();
$web_site = $contacts->getWebsite();
$address = $contacts->getAddress();
$logo = new Logo();
?>

<header class="header">
  <div class="container">
    <div class="header__head">
      <div class="header__menu">
        <?php echo $logo->getLogo() ?>
        <nav class="nav">
          <ul class="nav-list list-type-none">
            <?php
            foreach ($navigationLinks as $link) {
              echo '<li class="nav-item">
            <a class="link ' . isActive($link['path'], $currentPath) . '" href="' . htmlspecialchars($link['path']) . '">' . htmlspecialchars($link['name']) . '</a>
          </li>';
            }
            ?>
          </ul>
        </nav>
        <div class="header__contacts">
          <div class="header__geo geo">
            <a href="https://maps.app.goo.gl/72eQCZUbxVCKh43PA" class="header__geo-image link">
              <div class="header__image image">
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
              <a href="https://maps.app.goo.gl/72eQCZUbxVCKh43PA" class="link">
                <?php echo $address ?>
              </a>
            </address>
          </div>
          <?php echo $cart->initCart();
          ?>
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
        <a href="https://maps.app.goo.gl/72eQCZUbxVCKh43PA" class="link geo-address">
          <div class="header__image image">
            <svg width="50" height="50">
              <use href="<?php echo $path . '/assets/images/vectors/sprite.svg#geo' ?>"></use>
            </svg>
          </div>
          <?php echo $address ?>
        </a>
      </div>
    </div>
  </div>
</header>