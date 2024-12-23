<?php
class FilterBasic
{
  public function renderFilters()
  {
    ob_start();
    ?>
    <div class="filter-functions">
      <div class="filter-functions__item">
        <label for="vnedorojnik">
          Для внедорожника
          <input type="checkbox" class="filter-cost__checkbox" id="vnedorojnik" name="vnedorojnik">
        </label>
        <span class="count">111</span>
      </div>
      <div class="filter-functions__item">
        <label for="legkoe-avto">
          Для легкое авто
          <input type="checkbox" class="filter-cost__checkbox" id="legkoe-avto" name="legkoe-avto">
        </label>
        <span class="count">111</span>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }
}
?>