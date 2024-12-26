<?php
class FilterCost
{
  public function renderFilters()
  {
    ob_start();
    ?>
      <div class="filter-cost">
        <p class="filter-cost__title">Стоимость</p>
        <div class="filter-cost__range-block">
          <input type="range" class="filter-cost__range filter-cost__range--min" min="0" max="1000000" value="20000">
          <input type="range" class="filter-cost__range filter-cost__range--max" min="0" max="1000000" value="800000">
        </div>
        <div class="filter-cost__range-values">
          <span class="filter-cost__range-span" id="minValue">19999</span><span class="filter-cost__range-span" id="maxValue">129999</span>
        </div>
      </div>
    <?php
    return ob_get_clean();
  }
}
?>