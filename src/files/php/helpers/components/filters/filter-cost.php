<?php
class FilterCost
{
    public function renderFilters()
    {
        ob_start();
        ?>
    <div class="double-range-wrapper filter-cost">
      <div class="range-slider-container">
        <div class="range-container">
          <div class="range-scale">
            <div class="range-progress"></div>
          </div>
          <div class="range-wrapper">
            <input type="range" class="range-input range-min" min="100" max="300000" step="100"/>
            <input type="range" class="range-input range-max" />
          </div>
        </div>
        <div class="number-container">
          <div class="numbers-wrapper">
            <input type="number" class="number-input input-min" min="100" max="300000" step="100" name="min-value-cost"/>
          </div>
          <div class="numbers-wrapper">
            <input type="number" class="number-input input-max" min="100" max="300000" step="100" name="max-value-cost"/>
          </div>
        </div>
      </div>
    </div>

    <?php
        return ob_get_clean();
    }
}
?>