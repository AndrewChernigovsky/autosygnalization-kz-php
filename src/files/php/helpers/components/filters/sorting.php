<?php
class Sorting
{
  public function renderFilters()
  {
    ob_start();
    ?>
    <div class="sorting">
      <p>Сортировка: </p>
      <select name="soring" id="sort">
        <option value="ready_setup" selected>Предустановленная</option>
        <option value="cost">Цена</option>
        <option value="name">Название</option>
        <option value="date">Дата</option>
        <option value="popularity">Популярность</option>
      </select>
    </div>
    <?php
    return ob_get_clean();
  }
}
?>