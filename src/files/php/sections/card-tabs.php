<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';
include_once __DIR__ . '/../data/tabs.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

error_log(print_r($tabs, true) . ": TABS");

?>

<section class="tab">
  <div class="tab__wrapper">
    <div class="tab__buttons">
      <?php foreach ($tabs as $tab): ?>
        <button type="button" class="tab__button y-button-secondary"
          data-tab="<?php echo $tab['id']; ?>"><?= $tab['title'] ?></button>
      <?php endforeach; ?>
    </div>
    <div class="tab__content">
      <?php foreach ($tabs as $tab): ?>
        <ul class="tab__list tab__list--show list-style-none" data-content="<?= $tab['id']; ?>">
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