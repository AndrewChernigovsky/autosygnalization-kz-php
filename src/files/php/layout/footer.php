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

spl_autoload_register(function ($class_name) use ($classes) {
  include $classes . $class_name . '.php';
});

$contacts = new Contacts();
$social = $contacts->getSocial();
$phones = $contacts->getPhones();
$email = $contacts->getEmail();
$web_site = $contacts->getWebsite();
$geos = new Geo();
$logos = new Logo();
$footerMenu = new GenerateFooterLinks($navigationFooterLinks);


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
        <?php
        $footerMenu->generateFooter();
        ?>
      </div>
      <p class="footer__copy">© 2024 Auto Security. Все права защищены</p>
    </div>
  </div>
  </div>
</footer>