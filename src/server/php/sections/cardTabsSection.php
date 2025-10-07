<?php

namespace SECTIONS;

use DATA\TabsAdditionalData;

function cardTabsSection($id)
{
  $tabs_from_db = (new TabsAdditionalData())->getTabsByProductId($id);

  // if (empty($tabs_from_db)) {
  //   // Если табов нет - показываем только диагностику
  //   $debug_html = '<div style="background: #1a1a1a; padding: 15px; margin: 15px 0; border: 2px solid #ff6b6b; border-radius: 8px;">';
  //   $debug_html .= '<h3 style="color: #ffffff; margin: 0 0 15px 0; font-size: 18px;">🔍 ДИАГНОСТИКА ТАБОВ</h3>';
  //   $debug_html .= '<p style="color: #ffffff; margin: 5px 0;"><strong>ID товара:</strong> ' . htmlspecialchars($id) . '</p>';
  //   $debug_html .= '<p style="color: #ffffff; margin: 5px 0;"><strong>Количество табов:</strong> ' . count($tabs_from_db) . '</p>';
  //   $debug_html .= '<p style="color: #ff6b6b; margin: 10px 0;"><strong>❌ ТАБЫ НЕ НАЙДЕНЫ!</strong></p>';
  //   $debug_html .= '<p style="color: #ffffff; margin: 10px 0;">Возможные причины:</p>';
  //   $debug_html .= '<ul style="color: #ffffff; margin: 10px 0; padding-left: 20px;">';
  //   $debug_html .= '<li>Нет данных в таблице TabsAdditionalProductsData</li>';
  //   $debug_html .= '<li>Ошибка в классе TabsAdditionalData</li>';
  //   $debug_html .= '<li>Неправильный ID товара</li>';
  //   $debug_html .= '</ul></div>';
  //   return $debug_html;
  // }

  ob_start();
  ?>
  <section class="tab">
    <div class="tab__wrapper">
      <!-- Блок с кнопками вкладок -->
      <div class="tab__buttons">
        <?php $tabs_filtered = []; $firstActiveIndex = null; ?>
        <?php foreach ($tabs_from_db as $index => $tab_row): ?>
          <?php
          if (empty($tab_row['content']))
            continue;
          $tab_data = json_decode($tab_row['content'], true);
          if (!isset($tab_data['title']) || !isset($tab_data['content']) || !is_array($tab_data['content']))
          continue;

        // Фильтруем элементы: только те, у которых title не пустой
        $filtered_content = array_filter($tab_data['content'], function($item) {
              return !empty($item['title']);  // Проверяем только title, path-icon необязательна
            });
          // Сохраняем отфильтрованные элементы по индексу вкладки
          $tabs_filtered[$index] = array_values($filtered_content);
          error_log('cardTabsSection: tab '.$index.' filtered_count=' . count($tabs_filtered[$index]));
          if (empty($tabs_filtered[$index])) continue;  // Пропускаем вкладку, если нет элементов с title
          if ($firstActiveIndex === null) {
            $firstActiveIndex = $index;
          }
          ?>
          <button type="button" class="tab__button <?= $index === $firstActiveIndex ? 'tab__button--active' : '' ?> y-button-secondary"
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
          // Используем ранее сохраненный отфильтрованный контент для этой вкладки
          if (!isset($tabs_filtered[$index]) || empty($tabs_filtered[$index])) continue;
          ?>
          <ul class="tab__list <?= $index === $firstActiveIndex ? 'tab__list--show' : '' ?> list-style-none"
            data-content="<?= htmlspecialchars($tab_data['title']) ?>">
            <?php foreach ($tabs_filtered[$index] as $item): ?>
              <?php if (!empty($item['title'])): ?>
                <li class="tab__item" style="background-image: url(<?= htmlspecialchars($item['path-icon'] ?? '') ?>);">
                  <h3 class="tab__title"><?= $item['title'] ?? '' ?></h3>
                  <div class="tab__description-wrapper">
                    <p class="tab__description"><?= $item['description'] ?? 'Нет описания' ?></p>  <!-- Добавляем запасной текст, если описание пустое -->
                  </div>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  
  <!-- ДИАГНОСТИКА ПОСЛЕ ОТРИСОВКИ ТАБОВ -->
  <!-- <div style="background: #1a1a1a; padding: 15px; margin: 15px 0; border: 2px solid #4CAF50; border-radius: 8px;">
    <h3 style="color: #ffffff; margin: 0 0 15px 0; font-size: 18px;">🔍 ДИАГНОСТИКА ТАБОВ</h3>
    <p style="color: #ffffff; margin: 5px 0;"><strong>ID товара:</strong> <?= htmlspecialchars($id) ?></p>
    <p style="color: #ffffff; margin: 5px 0;"><strong>Количество табов:</strong> <?= count($tabs_from_db) ?></p>
    <p style="color: #4CAF50; margin: 10px 0;"><strong>✅ ТАБЫ НАЙДЕНЫ И ОТРИСОВАНЫ!</strong></p>
    
    <details style="margin: 10px 0;">
      <summary style="color: #ffffff; cursor: pointer; font-weight: bold;">📊 Показать детальную информацию</summary>
      <div style="margin-top: 10px;">
        <p style="color: #ffffff; margin: 5px 0;"><strong>Сырые данные из БД:</strong></p>
        <pre style="background: #2a2a2a; color: #ffffff; padding: 10px; border-radius: 4px; overflow: auto; font-size: 12px;"><?= htmlspecialchars(print_r($tabs_from_db, true)) ?></pre>
        
        <p style="color: #ffffff; margin: 10px 0 5px 0;"><strong>Обработка каждого таба:</strong></p>
        <?php foreach ($tabs_from_db as $index => $tab_row): ?>
          <div style="background: #2a2a2a; padding: 8px; margin: 5px 0; border-left: 3px solid #2196F3; border-radius: 4px;">
            <p style="color: #ffffff; margin: 2px 0;"><strong>📋 Таб #<?= $index ?>:</strong></p>
            <?php if (empty($tab_row['content'])): ?>
              <p style="color: #ff6b6b; margin: 2px 0;">❌ Пустой контент</p>
            <?php else: ?>
              <?php $tab_data = json_decode($tab_row['content'], true); ?>
              <?php if (!isset($tab_data['title'])): ?>
                <p style="color: #ff6b6b; margin: 2px 0;">❌ Нет заголовка</p>
              <?php else: ?>
                <p style="color: #4CAF50; margin: 2px 0;">✅ Заголовок: "<?= htmlspecialchars($tab_data['title']) ?>"</p>
                <?php if (isset($tab_data['content']) && is_array($tab_data['content'])): ?>
                  <p style="color: #2196F3; margin: 2px 0;">📄 Элементов контента: <?= count($tab_data['content']) ?></p>
                <?php else: ?>
                  <p style="color: #ff6b6b; margin: 2px 0;">❌ Нет контента или не массив</p>
                <?php endif; ?>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </details>
  </div> -->
  
  <?php
  return ob_get_clean();
}
