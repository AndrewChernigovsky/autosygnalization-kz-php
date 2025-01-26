<?php
include_once __DIR__ . '/../../../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $docROOT . $variables->getPathFileURL();

include_once $path . '/files/php/data/filters.php';
include_once $path . '/files/php/helpers/components/filters/filter-cost.php';
include_once $path . '/files/php/helpers/components/filters/filter-functions.php';
include_once $path . '/files/php/helpers/components/filters/filter-basic.php';
class Filters
{
    private $filterCost;
    private $filterFunctions;

    private $filterBasic;

    private $path;

    public function __construct($data_filters)
    {
        $this->filterCost = new FilterCost();
        $this->filterFunctions = new FilterFunctions($data_filters);
        $this->filterBasic = new filterBasic();
        $variables = new SetVariables();
        $variables->setVar();
        $path = $variables->getPathFileURL();
        $this->path = $path;
    }
    public function renderFilters()
    {
        ob_start();
        ?>
    <button class="filter-button" type="button" id="filter-btn"
      style="background-image: url(<?= htmlspecialchars($this->path . '/assets/images/vectors/filters.svg'); ?>);">Фильтр</button>
    <button class="filter-button-close" type="button" id="filter-btn-close"><span class="visually-hidden">скрыть
        фильтры</span></button>
    <form class="filter-form open" id="filter-catalog" action="<?= $this->path;?>/files/php/pages/catalog/catalog.php" method="get">
      <?php echo $this->filterBasic->renderFilters(); ?>
      <?php echo $this->filterCost->renderFilters(); ?>
      <?php echo $this->filterFunctions->renderFilters(); ?>
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