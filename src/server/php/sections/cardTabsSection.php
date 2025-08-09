<?php

namespace SECTIONS;

use DATA\TabsAdditionalData;

function cardTabsSection($id)
{
  $tabs_from_db = (new TabsAdditionalData())->getTabsByProductId($id);

  if (empty($tabs_from_db)) {
    return '';
  }

  ob_start();
  ?>
  <section class="tab">
    <div class="tab__wrapper">
      <!-- Блок с кнопками вкладок -->
      <div class="tab__buttons">
        <?php foreach ($tabs_from_db as $index => $tab_row): ?>
          <?php
          if (empty($tab_row['content']))
            continue;
          $tab_data = json_decode($tab_row['content'], true);
          if (!isset($tab_data['title']))
            continue;
          ?>
          <button type="button" class="tab__button <?= $index === 0 ? 'tab__button--active' : '' ?> y-button-secondary"
            data-tab="<?= htmlspecialchars($tab_data['title']) ?>">
            <?= htmlspecialchars($tab_data['title']) ?>
          </button>
        <?php endforeach; ?>
      </div>

      <!-- Блок с контентом вкладок -->
      <div class="tab__content">
        <?php foreach ($tabs_from_db as $index => $tab_row): ?>
          <?php
          if (empty($tab_row['content']))
            continue;
          $tab_data = json_decode($tab_row['content'], true);
          if (!isset($tab_data['title']) || !isset($tab_data['content']) || !is_array($tab_data['content']))
            continue;
          ?>
          <ul class="tab__list <?= $index === 0 ? 'tab__list--show' : '' ?> list-style-none"
            data-content="<?= htmlspecialchars($tab_data['title']) ?>">
            <?php foreach ($tab_data['content'] as $item): ?>
              <li class="tab__item" style="background-image: url(<?= htmlspecialchars($item['icon'] ?? '') ?>);">
                <h3 class="tab__title"><?= htmlspecialchars($item['title'] ?? '') ?></h3>
                <p class="tab__description"><?= htmlspecialchars($item['description'] ?? '') ?></p>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean();
}
