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
      <p>Функции</p>
      <?php foreach ($this->data_filters as $category): ?>
        <div class="filter-functions__item">
          <label>
            <?php echo $category['text'] ?>
            <input type="checkbox" class="filter-cost__checkbox" id="<?php echo $category['name'] ?>"
              name="<?php echo $category['name'] ?>">
          </label>
          <span class="count"><?php echo $category['count'] ?></span>
        </div>
      <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
  }
}
?>