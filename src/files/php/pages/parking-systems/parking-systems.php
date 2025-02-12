<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../data/article.php';
include_once __DIR__ . '/../../data/select.php';
include_once __DIR__ . '/../../data/products.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../helpers/components/filters/filters.php';
include_once __DIR__ . '/../../helpers/components/setup.php';
include_once __DIR__ . '/../../helpers/components/product.php';
include_once __DIR__ . '/../../helpers/components/article.php';
include_once __DIR__ . '/../../helpers/components/select.php';
include_once __DIR__ . '/../../helpers/components/pagination.php';

$PAGE = $_GET['PAGE'] ?? 1;
$OPTIONS = $_GET ?? [];
$minCost = $_GET['min-value-cost'] ?? 0;
$maxCost = $_GET['max-value-cost'] ?? PHP_FLOAT_MAX;
$SELECT = $_GET['SELECT'] ?? '';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$title = 'Парковочные системы | Auto Security';

include_once $head_path;
include_once $docROOT . $path . '/files/php/data/products.php';
include_once $docROOT . $path . '/files/php/pages/special-products.php';

$head = new Head($title, [], []);
$filters_render = new FiltersRender($products, "auto");
$article = new Article();
$articleData = new ArticleData();
$select = new Select();
$selectData = new SelectData();

$allProducts = $products;

// Фильтрация по OPTIONS
if (empty($OPTIONS)) {
    $filteredProducts = $allProducts;
} else {
    $filteredProducts = [];
    foreach ($allProducts['category'] as $category => $items) {
        foreach ($items as $product) {
            $isMatch = true;
            $productCost = $product['price'] ?? 0;


            if ($productCost < $minCost || $productCost > $maxCost) {
                $isMatch = false;
                continue;
            }

            foreach ($OPTIONS as $option => $value) {
                $productFilters = $product['options-filters'] ?? [];
                if ($value === 'on') {
                    if (!is_array($productFilters) || !in_array($option, $productFilters)) {
                        $isMatch = false;
                        break;
                    }
                }
            }

            if ($isMatch) {
                $filteredProducts[] = $product;
            }
        }
    }
}
error_log(print_r($OPTIONS, true) . ' : OPTIONS');

if (!empty($SELECT)) {
    if($SELECT === 'name') {
        error_log(print_r($filteredProducts, true) . ' : FILTERS');
        usort($filteredProducts, function ($a, $b) {
            $nameA = $a['title'] ?? '';
            $nameB = $b['title'] ?? '';
            return strcmp(mb_strtolower($nameA), mb_strtolower($nameB));
        });
    }

    if($SELECT === 'price') {
        if ($SELECT === 'price') {
            usort($filteredProducts, function ($a, $b) {
                $priceA = $a['price'] ?? 0;
                $priceB = $b['price'] ?? 0;
                return $priceB <=> $priceA; // Сортировка по убыванию
            });

            // Логирование для отладки
            error_log("Sorted by Price (Descending): " . print_r($filteredProducts, true));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
    <?php include_once $docROOT . $path . '/files/php/layout/header.php'; ?>
    <main class="main">
        <h2 class="title__h2">Парковочные системы</h2>
        <div class="catalog">
            <div class="catalog__wrapper parking-system">
                <aside class="aside">
                    <p>Стоимость</p>
                    <?= $filters_render->renderFilters(false, true, false, '/files/php/pages/parking-systems/parking-systems.php'); ?>
                </aside>
                <div class="catalog__products">
                    <?php
                    $count = 0;
                    foreach ($filteredProducts as $product) {
                        echo getProductCardWModel([$product], false, $PAGE); // Вывод товара
                        $count++;
                        if ($count === 6 || $count === count($filteredProducts)): // Вставляем "Специальное предложение" после второго товара
                            echo getSpecialOffersSection(); // Вставляем слайдер
                        endif;
                    }
                    ?>
                </div>
            </div>
            <?php if ($filteredProducts): ?>
            <?php
                error_log(print_r($filteredProducts, true) . ' :FILTERS ');
                $pagination = new Pagination($filteredProducts);
            ?>
            <?= $pagination->render(); ?>
            <?php endif; ?>
        </div>
        <?= getShop('setup'); ?>
    </main>
    <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
    <?php include_once $docROOT . $path . '/files/php/sections/popups/modal-cart.php'; ?>
</body>
</html>