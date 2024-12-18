<?php
class FilterCost
{
  public function renderFilters()
  {
    ob_start();
    ?>
    <div class="filter-cost">
      <p>Стоимость</p>
      <input type="range" class="filter-cost__range filter-cost__range--min">
      <input type="range" class="filter-cost__range filter-cost__range--max">
    </div>
    <?php
    return ob_get_clean();
  }
}
?>