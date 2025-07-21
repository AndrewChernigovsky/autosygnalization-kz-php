<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use LAYOUT\Header;
use LAYOUT\Footer;
use LAYOUT\Head;
use HELPERS\Products AS ProductsHelper;
use Components\CurrencyRuble;
use SECTIONS\MainBanner;
use SECTIONS\ProductCards;
use SECTIONS\Contacts;
use SECTIONS\Feedback;
use SECTIONS\Services;
use SECTIONS\Partners;
use SECTIONS\About;
use SECTIONS\Map;
use DATA\Products;
use function FUNCTIONS\main;
use function FUNCTIONS\get_header_and_styles;
use function FUNCTIONS\get_footer;
use function HELPERS\get_products_by_category;
use function HELPERS\get_product_by_id;
use function SECTIONS\render_breadcrumbs;
use function HELPERS\getAutoContent;
use function HELPERS\getProductCardDescription;
use function SECTIONS\cardTabsSection;
use function FUNCTIONS\renderPhoneButton;
use DATA\TabsAdditionalData;

use function AUTH\SESSIONS\initSession;

initSession();
$products = (new Products())->getData();
$helper = new ProductsHelper($products);
list($category, $id) = $helper->extractCategoryAndId();

function formatPriceWithSpaces($price)
{
    return number_format($price, 0, '', ' ');
}

$tabsData = new TabsAdditionalData();
$tabs = $tabsData->getTabsByProductId($id);

$title = "$id | Auto Security";
$head = new Head($title, [], []);
$header = new Header();
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?= $header->getHeader(); ?>
  <main class="main">
    <section class="card-more">
      <?= getAutoContent($products, $category, $id); ?>
      <?= getProductCardDescription($products, $id); ?>
      
      <?php if (!empty($tabs)): ?>
        <div class="tabs-container">
            <div class="tabs-buttons">
                <?php foreach ($tabs as $index => $tab): ?>
                    <button class="tab-button <?= $index === 0 ? 'active' : '' ?>" data-tab-target="#tab-<?= $index ?>">
                        <?= htmlspecialchars($tab['title']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="tabs-content">
                <?php foreach ($tabs as $index => $tab): ?>
                    <div id="tab-<?= $index ?>" class="tab-pane <?= $index === 0 ? 'active' : '' ?>">
                        <?php 
                            $contentData = json_decode($tab['content'], true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($contentData)) {
                                echo '<ul>';
                                foreach($contentData['items'] ?? [] as $item) {
                                    echo '<li><strong>' . htmlspecialchars($item['title']) . ':</strong> ' . htmlspecialchars($item['description']) . '</li>';
                                }
                                echo '</ul>';
                            } else {
                                echo $tab['content'];
                            }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
      <?php endif; ?>

    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabsButtons = document.querySelectorAll('.tab-button');
        const tabsPanes = document.querySelectorAll('.tab-pane');

        tabsButtons.forEach(button => {
            button.addEventListener('click', () => {
                const target = document.querySelector(button.dataset.tabTarget);

                tabsButtons.forEach(btn => btn.classList.remove('active'));
                tabsPanes.forEach(pane => pane.classList.remove('active'));

                button.classList.add('active');
                if(target) target.classList.add('active');
            });
        });
    });
  </script>

  <?= (new Footer())->getFooter(); ?>
</body>

</html>