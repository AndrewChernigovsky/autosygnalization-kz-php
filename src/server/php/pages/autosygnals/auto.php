<?php
use HELPERS\SetVariables;
include_once __DIR__ . '/../../data/navigation-links.php';

$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();
$navigationLinks = new NavigationLinks();
$autosygnals = $navigationLinks->getCategoriesAutoSygnals();
?>

<section class="autosygnals" id="autosygnals">
  <div class="container">
    <h2 class="autosygnals__title">Автосигнализации с автозапуском</h2>
  </div>
</section>
<?php
include_once __DIR__ . '/../../helpers/components/setup.php';
echo getShop('setup');
?>