<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';
include_once __DIR__ . '/../data/tabs.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

// ID текущего товара
$current_product_id = 'product_keychain_a93-eco';

// Найти вкладки для текущего товара
$product_tabs = [];
foreach ($tabs as $product) {
    if ($product['id'] === $current_product_id && isset($product['tabs'])) {
        $product_tabs = $product['tabs'];
        break;
    }
}

function isActiveClassTab($index)
{
    return $index === 0 ? 'tab__button--active' : '';
}

function isActiveClassTabContent($index)
{
    return $index === 0 ? 'tab__list--show' : '';
}
?>

<?php if (!empty($product_tabs)): ?>
<section class="tab">
    <div class="tab__wrapper">
        <!-- Кнопки вкладок -->
        <div class="tab__buttons">
            <?php $index = 0; ?>
            <?php foreach ($product_tabs as $tab_title => $tab_content): ?>
                <button type="button" class="tab__button <?= isActiveClassTab($index) ?> y-button-secondary"
                        data-tab="<?= $tab_title; ?>"><?= $tab_title; ?></button>
                <?php $index++; ?>
            <?php endforeach; ?>
        </div>

        <!-- Контент вкладок -->
        <div class="tab__content">
            <?php $index = 0; ?>
            <?php foreach ($product_tabs as $tab_title => $tab_content): ?>
                <?php if ($tab_title === 'ОПИСАНИЕ'): ?>
                    <!-- Список items -->
                    <?php if (isset($tab_content['items'])): ?>
                        <ul class="tab__list <?= isActiveClassTabContent($index) ?> list-style-none" data-content="<?= $tab_title; ?>">
                            <?php foreach ($tab_content['items'] as $item): ?>
                                <li class="tab__item"
                                    style="background-image: url('<?= isset($item['path-icon']) ? htmlspecialchars($item['path-icon']) : '' ?>')">
                                    <?php if (!empty($item['title'])): ?>
                                        <h3 class="tab__title"><?= htmlspecialchars($item['title']); ?></h3>
                                    <?php endif; ?>
                                    <p class="tab__description"><?= htmlspecialchars($item['description']); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- Список items-service -->
                    <?php if (isset($tab_content['items-service'])): ?>
                        <p>Удобный сервис</p>
                        <ul class="tab__list tab__list--service <?= isActiveClassTabContent($index) ?> list-style-none" data-content="<?= $tab_title; ?>">
                            <?php foreach ($tab_content['items-service'] as $item): ?>
                                <li class="tab__item"
                                    style="background-image: url('<?= isset($item['path-icon']) ? ($item['path-icon']) : '' ?>')">
                                    <?php if (!empty($item['title'])): ?>
                                        <h3 class="tab__title"><?=($item['title']); ?></h3>
                                    <?php endif; ?>
                                    <p class="tab__description"><?=($item['description']); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Для других вкладок -->
                    <ul class="tab__list <?= isActiveClassTabContent($index) ?> list-style-none" data-content="<?= $tab_title; ?>">
                        <?php foreach ($tab_content as $item): ?>
                            <li class="tab__item">
                                <?php if (!empty($item['title'])): ?>
                                    <h3 class="tab__title"><?= htmlspecialchars($item['title']); ?></h3>
                                <?php endif; ?>
                                <p class="tab__description"><?= htmlspecialchars($item['description']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php $index++; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php else: ?>
    <p>Информация о товаре недоступна.</p>
<?php endif; ?>
