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
          Автозапуск
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          Автозапуск
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          Автозапуск
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          Автозапуск
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          Автозапуск
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