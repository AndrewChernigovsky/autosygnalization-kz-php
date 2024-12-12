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

include_once $contacts_data;
include_once $svgInclude;
include_once $phone;
include_once $geo;

$contacts = new Contacts();
$social = $contacts->getSocial();
$phones = $contacts->getPhones();
$email = $contacts->getEmail();
$web_site = $contacts->getWebsite();
$geos = new Geo();

spl_autoload_register(function ($class_name) use ($classes) {
  include $classes . $class_name . '.php';
});

$insertSVG = new CreateSVG();
$insertPHONE = new InsertPhone();
?>

<footer class="footer">
    <div class="container">
        <div class="footer__wrapper">
            <a href="/" class="logo">
                <img src="<?php echo htmlspecialchars($path . '/assets/images/logo.avif'); ?>"
                    alt="Логотип компании Auto Security." width="122" height="84" />
                <div class="text">
                    <p class="text-main">Auto</p>
                    <p class="text-secondary">Security</p>
                </div>
            </a>
            <div class="contacts">
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
                    <?php $insertPHONE->displayPhones($phones) ?>
                </div>
                <div class="contacts__text">
                    <p><?php echo $email ?></p>
                </div>
                <div class="site">
                    <p><?php echo $web_site ?></p>
                </div>
                <?php echo $geos->getGeo() ?>
            </div>
        </div>
    </div>
    </div>
</footer>