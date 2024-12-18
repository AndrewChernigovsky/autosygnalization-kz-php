<?php
include_once __DIR__ . '/../../../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $docROOT . $variables->getPathFileURL();

include_once $path . '/files/php/helpers/components/filters/filter-cost.php';
include_once $path . '/files/php/helpers/components/filters/filter-functions.php';
include_once $path . '/files/php/helpers/components/filters/filter-basic.php';
class Filters
{
  private $filterCost;
  private $filterFunctions;

  private $filterBasic;

  public function __construct()
  {
    $this->filterCost = new FilterCost();
    $this->filterFunctions = new FilterFunctions();
    $this->filterBasic = new filterBasic();
  }
  public function renderFilters()
  {
    ob_start();
    ?>
    <form id="filter-catalog" action="" method="get">
      <?php echo $this->filterBasic->renderFilters() ?>
      <?php echo $this->filterCost->renderFilters() ?>
      <?php echo $this->filterFunctions->renderFilters() ?>
      <div class="filters__buttons">
        <button type="submit" class="button y-button-primary">Применить</button>
        <button type="reset" class="button y-button-secondary">Сбросить</button>
      </div>
    </form>
    <?php
    return ob_get_clean();
  }
}
?>