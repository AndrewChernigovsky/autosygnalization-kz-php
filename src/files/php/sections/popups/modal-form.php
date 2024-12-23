<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/contacts.php';

$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();
?>

<div class="popup modal-form active">
  <button type="button" id="modal-form-close">
    <span class="visually-hidden">
      Закрыть форму
    </span>
  </button>
  <div class="container">
    <div class="popup__phones">
      <?php
      if (!empty($phones)) {
        foreach ($phones as $phone) {
          $cleanedPhone = str_replace(' ', '', $phone['phone']);
          echo '<a href="tel:' . htmlspecialchars($cleanedPhone) . '">' . 'Позвонить ' . '<span>' . htmlspecialchars($phone['phone']) . '</span>' . '</a>';
        }
      }
      ?>
    </div>
    <div class="popup__icons">
      <a href="">
        <img src="<?= $path . '/assets/images/icons/telegram.avif'; ?>" alt="">
      </a>
      <a href="https://clck.ru/3FQ7aJ">
        <img src="<?= $path . '/assets/images/icons/whatsapp.avif'; ?>" alt="">
      </a>
    </div>
  </div>
</div>