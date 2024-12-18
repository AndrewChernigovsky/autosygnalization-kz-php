<?php
class FilterFunctions
{
  public function renderFilters()
  {
    ob_start();
    ?>
    <div class="filter-functions">
      <p>Функции</p>
      <div class="filter-functions__item">
        <label for="autosetup">
          Автозапуск
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          УПРАВЛЕНИЕ С ТЕЛЕФОНА
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          БЕСПЛАТНЫЙ МОНИТОРИНГ
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          УМНАЯ АВТОДИАГНОСТИКА
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }
}
?>