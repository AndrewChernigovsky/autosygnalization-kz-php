<?php

namespace SECTIONS;

use DATA\TabsAdditionalData;

// $current_product_id = isset($_GET['id']) && preg_match('/^[a-zA-Z0-9_-]+$/', $_GET['id']) ? $_GET['id'] : '';

// error_log(print_r($current_product_id, true) . " Это id продукта");

// $tabs = [];


function isActiveClassTab($index)
{
  return $index === 0 ? 'tab__button--active' : '';
}

function isActiveClassTabContent($index)
{
  return $index === 0 ? 'tab__list--show' : '';
}

function DescriptionTabIsEmpty($tabs, $title)
{
  if (isset($tabs['ОПИСАНИЕ'])) {
    $descriptionArray = $tabs['ОПИСАНИЕ'];

    if (is_array($descriptionArray) && empty($descriptionArray)) {
      return $title !== 'ОПИСАНИЕ' ? 'tab__list--show ' : '';
    }
  }
  return '';
}

function isTextTab($title)
{
  return $title === 'ГАРАНТИЯ' ? 'tab__list--no-column ' : '';
}


function cardTabsSection($id)
{

  $tabs = (new TabsAdditionalData())->getTabsByProductId($id);
  // if (!empty($tabs)) {
  //   foreach ($tabs as $product) {
  //     if ($product['id'] === $id && isset($product['tabs'])) {
  //       $tabs = $product['tabs'];
  //       break;
  //     }
  //   }
  // }
  if (empty($tabs)) {
    return '<p>Информация о товаре недоступна.</p>';
  }

  ob_start(); // Начинаем буферизацию вывода
  ?>
  <section class="tab">
    <div class="tab__wrapper">
      <!-- Кнопки вкладок -->
      <div class="tab__buttons">
        <?php $index = 0; ?>
        <?php foreach ($tabs as $tab_title => $tab_content): ?>
          <?php if (empty($tab_content)) {
            continue;
          } ?>
          <button type="button" class="tab__button <?= isActiveClassTab($index) ?> y-button-secondary"
            data-tab="<?= htmlspecialchars($tab_title) ?>">
            <?= htmlspecialchars($tab_title) ?>
          </button>
          <?php $index++; ?>
        <?php endforeach; ?>
      </div>

      <!-- Контент вкладок -->
      <div class="tab__content">
        <?php $index = 0; ?>
        <?php foreach ($tabs as $tab_title => $tab_content): ?>
          <?php if (empty($tab_content)) {
            continue;
          } ?>

          <?php
          $tab_classes = [
            isActiveClassTabContent($index),
            isTextTab($tab_title),
            DescriptionTabIsEmpty($tabs, $tab_title),
            (count($tab_content) === 1 ? 'tab__list--no-column' : '')
          ];
          $tab_classes = implode(' ', array_filter($tab_classes));
          ?>

          <?php if ($tab_title === 'ОПИСАНИЕ'): ?>
            <!-- Список items -->
            <?php if (isset($tab_content['description'])): ?>
              <ul class="tab__list <?= $tab_classes ?> list-style-none" data-content="<?= htmlspecialchars($tab_title) ?>">
                <?php foreach ($tab_content['items'] as $item): ?>
                  <li class="tab__item" style="background-image: url('<?= htmlspecialchars($item['path-icon'] ?? '') ?>')">
                    <?php if (!empty($item['title'])): ?>
                      <h3 class="tab__title"><?= htmlspecialchars($item['title']) ?></h3>
                    <?php endif; ?>
                    <p class="tab__description"><?= $item['description'] ?></p>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <!-- Список items-service -->
            <?php if (isset($tab_content['items-service'])): ?>
              <p class="tab__text">Удобный сервис</p>
              <ul class="tab__list tab__list--service <?= $tab_classes ?> list-style-none"
                data-content="<?= htmlspecialchars($tab_title) ?>">
                <?php foreach ($tab_content['items-service'] as $item): ?>
                  <li class="tab__item" style="background-image: url('<?= htmlspecialchars($item['path-icon'] ?? '') ?>')">
                    <?php if (!empty($item['title'])): ?>
                      <h3 class="tab__title"><?= htmlspecialchars($item['title']) ?></h3>
                    <?php endif; ?>
                    <p class="tab__description"><?= $item['description'] ?></p>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <!-- Description -->
            <?php if (isset($tab_content['description'])): ?>
              <ul class="tab__list tab__list--no-column <?= $tab_classes ?> list-style-none"
                data-content="<?= htmlspecialchars($tab_title) ?>">
                <li class="tab__item tab__item--text">
                  <p class="tab__description"><?= $tab_content['description'] ?></p>
                </li>
              </ul>
            <?php endif; ?>

          <?php else: ?>
            <!-- Для других вкладок -->
            <ul class="tab__list <?= $tab_classes ?> list-style-none" data-content="<?= htmlspecialchars($tab_title) ?>">
              <?php foreach ($tab_content as $item): ?>

                <li class="tab__item tab__item--text">
                  <?php if (!empty($item['title'])): ?>
                    <h3 class="tab__title"><?= htmlspecialchars($item['title']) ?></h3>
                  <?php endif; ?>
                  <p class="tab__description"><?= $item['description'] ?></p>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php $index++; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean(); // Возвращаем содержимое буфера
}
