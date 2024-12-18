<?php
class FilterBasic
{
  public function renderFilters()
  {
    ob_start();
    ?>
    <div class="filter-functions">
      <div class="filter-functions__item">
        <label for="autosetup">
          Для внедорожника
          <input type="checkbox" class="filter-cost__checkbox" id="autosetup" name="autosetup">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="autosetup">
          ДЛЯ ЛЕГКОВОГО АВТО
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