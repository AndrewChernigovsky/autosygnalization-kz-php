<?php
class FilterFunctions
{
  private $data_filters;
  public function __construct($data_filters)
  {
    $this->data_filters = $data_filters;
  }
  public function renderFilters()
  {
    ob_start();
    ?>
    <div class="filter-functions">
      <p class="filter-functions__title">Функции</p>
      <?php foreach ($this->data_filters as $category): ?>
        <div class="filter-functions__item">
          <label class="filter-functions__item-element">
            <input type="checkbox" class="filter-functions__checkbox" id="<?php echo $category['name'] ?>"
              name="<?php echo $category['name'] ?>">
              <span class="filter-functions__item-title"> <?php echo $category['text'] ?></span>
          </label>
          <span class="filter-functions__count"><?php echo $category['count'] ?></span>
        </div>
      <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
  }
}
?>