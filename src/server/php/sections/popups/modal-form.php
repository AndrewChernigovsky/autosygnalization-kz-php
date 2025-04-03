<?php
use HELPERS\SetVariables;
include_once __DIR__ . '/../../data/contacts.php';
include __DIR__ . '/../../data/brands.php';


$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();
?>

<div class="popup phone-popup">
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
    <a href="https://t.me/auto_security_almaty">
      <img src="<?= $path . '/client/images/icons/telegram.avif'; ?>" alt="иконка телеграма" width="40" height="40">
    </a>
    <a href="https://clck.ru/3FQ7aJ">
      <img src="<?= $path . '/client/images/icons/whatsapp.avif'; ?>" alt="иконка вотсапа" width="40" height="40">
    </a>
  </div>
</div>

<div class="popup modal-form">
  <button type="button" id="modal-form-close" class="popup__button--close">
    <span class="visually-hidden">
      Закрыть форму
    </span>
  </button>
  <div class="popup-body">
  </div>
</div>