<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();
$docROOT = $variables->getDocRoot();
$basePath = $variables->getBasePath();

$contacts_data = $basePath . '/files/php/data/contacts.php';
$svgInclude = $basePath . '/files/php/helpers/svg.php';
$phone = $basePath . '/files/php/helpers/phone.php';
$classes = $basePath . '/files/php/helpers/classes/';
$geo = $basePath . '/files/php/helpers/components/geo.php';
$logo = $basePath . '/files/php/helpers/components/logo.php';

include_once $contacts_data;
include_once $svgInclude;
include_once $phone;
include_once $geo;
include_once $logo;

$contacts = new Contacts();
$social = $contacts->getSocial();
$phones = $contacts->getPhones();
$email = $contacts->getEmail();
$web_site = $contacts->getWebsite();
$geos = new Geo();
$logos = new Logo();

spl_autoload_register(function ($class_name) use ($classes) {
  include $classes . $class_name . '.php';
});

$insertSVG = new CreateSVG();
$insertPHONE = new InsertPhone();
$icon_phone = ['name' => 'icon_phone', 'width' => '50', 'height' => '50', "image" => $path . '/assets/images/vectors/sprite.svg#phone-no-border', 'href' => '#'];

$filesToInclude = [
  'navigationLinks' => $docROOT . "$path/files/php/data/navigation-links.php",
];

foreach ($filesToInclude as $key => $filePath) {
  if (file_exists($filePath)) {
    include_once $filePath;
  } else {
    echo "Файл не найден: $filePath";
  }
}
?>

<footer class="footer">
  <div class="container">
    <div class="footer__wrapper">
      <?php echo $logos->getLogo(); ?>
      <div class="footer__contacts">
        <div class="social">
          <p>Социальные сети</p>
          <?php
          if (isset($social['instagramm'])) {
            echo $insertSVG->insertSvg($social['instagramm']);
          } else {
            echo "<!-- Instagram icon data is missing -->";
          }
          ?>

        </div>
        <div class="phones">
          <?php $insertPHONE->displayPhones($phones, $icon_phone) ?>
        </div>
        <div class="contacts__text">
          <p><?php echo $email ?></p>
        </div>
        <div class="site">
          <p><?php echo $web_site ?></p>
        </div>
        <?php echo $geos->getGeo() ?>
      </div>
      <div class="footer__menu">
        <div class="footer__menu-title">
          <?php foreach ($navigationFooterLinks as $navItem): ?>
            <div class="footer__menu-title">
              <h3>
                <a href="<?php echo htmlspecialchars($navItem['link']); ?>">
                  <?php echo htmlspecialchars($navItem['title']); ?>
                </a>
              </h3>

              <?php if (isset($navItem['sub-title'])): ?>
                <h4><?php echo htmlspecialchars($navItem['sub-title']['name']); ?></h4>
                <ul class="footer__menu-list">
                  <?php foreach ($navItem['sub-title']['list'] as $subItem): ?>
                    <li>
                      <a href="<?php echo htmlspecialchars($subItem['link']); ?>">
                        <?php echo htmlspecialchars($subItem['name']); ?>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php elseif (isset($navItem['list'])): ?>
                <ul class="footer__menu-list">
                  <?php foreach ($navItem['list'] as $clientItem): ?>
                    <li>
                      <a href="<?php echo htmlspecialchars($clientItem['link']); ?>">
                        <?php echo htmlspecialchars($clientItem['name']); ?>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  </div>
</footer>