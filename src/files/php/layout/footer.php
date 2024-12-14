<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();
$docROOT = $variables->getDocRoot();
$basePath = $variables->getBasePath();

$contacts_data = $basePath . '/files/php/data/contacts.php';
$social = $basePath . '/files/php/helpers/components/social.php';
$phone = $basePath . '/files/php/helpers/phone.php';
$classes = $basePath . '/files/php/helpers/classes/';
$geo = $basePath . '/files/php/helpers/components/geo.php';
$logo = $basePath . '/files/php/helpers/components/logo.php';

include_once $contacts_data;
include_once $social;
include_once $phone;
include_once $geo;
include_once $logo;

spl_autoload_register(function ($class_name) use ($classes) {
  include $classes . $class_name . '.php';
});

$contacts = new Contacts();
$phones = $contacts->getPhones();
$email = $contacts->getEmail(true);
$web_site = $contacts->getWebsite(true);
$geos = new Geo();
$logo = new Logo();
$footerMenu = new GenerateFooterLinks($navigationFooterLinks);

$socialIcons = $contacts->getSocialIcons();
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
  <div class="footer__wrapper">
    <div class="container">
      <div class="footer__inner">
        <div class="footer__contacts">
          <?php echo $logo->getLogo(); ?>
          <div class="social">
            <p>Социальные сети</p>
            <ul class="social__icons list-style-none">
              <li>
                <?php
                foreach ($socialIcons as $social) {
                  echo $contacts->setSocial($social);
                }
                ?>
              </li>
            </ul>
          </div>
          <div class="phones">
            <?php $insertPHONE->displayPhones($phones, $icon_phone) ?>
          </div>
          <div class="email-site">
            <div class="email" style>
              <?php echo $email ?>
            </div>
            <div class="site">
              <?php echo $web_site ?>
            </div>
          </div>
          <?php echo $geos->getGeo() ?>
        </div>
        <div class="footer__menu">
          <?php
          $footerMenu->generateFooter();
          ?>
        </div>
      </div>
    </div>
    <p class="footer__copy">© 2024 Auto Security. Все права защищены</p>
  </div>
</footer>