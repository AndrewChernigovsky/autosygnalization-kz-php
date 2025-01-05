<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';
include_once __DIR__ . '/../data/tabs.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

error_log(print_r($tabs, true) . ": TABS");

function isActiveClassTab($index)
{
  if($index === 0) {
    return 'tab__button--active';
  }
}
?>

<section class="tab">
  <div class="tab__wrapper">
    <div class="tab__buttons">
      <?php foreach ($tabs as $index => $tab): ?>
        <button type="button" class="tab__button  <?= isActiveClassTab($index) ?>  y-button-secondary"
          data-tab="<?php echo $tab['id']; ?>"><?= $tab['title'] ?></button>
      <?php endforeach; ?>
    </div>
    <div class="tab__content">
      <?php foreach ($tabs as $index => $tab): ?>
        <ul class="tab__list <?= isActiveClassTabContent($index)?> list-style-none" data-content="<?= $tab['id']; ?>">
          <?php foreach ($tab['items'] as $item): ?>
            <li class="tab__item"
              style="background-image: url('<?php echo isset($item['path-icon']) ? htmlspecialchars($item['path-icon']) : '' ?>')">
              <h3 class="tab__title"><?= $item['title']; ?></h3>
              <p class="tab__description"><?= $item['description'] ?></p>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endforeach; ?>
    </div>
  </div>
</section>