<?php
class FilterBasic
{
  public function renderFilters()
  {
    ob_start();
    ?>
    <div class="filter-basic">
      <div class="filter-basic__item">
        <label for="vnedorojnik" class="filter-basic__item-element">
          <input type="checkbox" class="filter-basic__checkbox" id="vnedorojnik" name="vnedorojnik">
          <span class="filter-basic__item-title"></span>
        </label>
        <span class="filter-basic__count">111</span>
      </div>
      <div class="filter-basic__item">
        <label for="legkoe-avto" class="filter-basic__item-element">
          <input type="checkbox" class="filter-basic__checkbox" id="legkoe-avto" name="legkoe-avto">
          <span class="filter-basic__item-title">Для легкое авто</span>
        </label>
        <span class="filter-basic__count">111</span>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }
}
?>