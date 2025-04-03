<?php

namespace COMPONENTS;

use DATA\ContactsData;

// use DATA\BrandsData;

class ModalForm
{
    private $phones;

    public function __construct()
    {
        // Инициализация данных о телефонах
        $this->phones = (new ContactsData())->getPhones();
    }

    public function render()
    {

        $phoneLinks = '';
        if (!empty($this->phones)) {
            foreach ($this->phones as $phoneData) {
                $cleanedPhone = str_replace(' ', '', $phoneData['phone']);
                $phoneLinks .= '<a href="tel:' . htmlspecialchars($cleanedPhone) . '">' .
                               'Позвонить ' .
                               '<span>' . htmlspecialchars($phoneData['phone']) . '</span>' .
                               '</a>';
            }
        }

        return <<<HTML
<div class="popup phone-popup">
  <div class="popup__phones">
  {$phoneLinks}
  </div>
  <div class="popup__icons">
    <a href="https://t.me/auto_security_almaty">
      <img src="/client/images/icons/telegram.avif'" alt="иконка телеграма" width="40" height="40">
    </a>
    <a href="https://clck.ru/3FQ7aJ">
      <img src="/client/images/icons/whatsapp.avif'" alt="иконка вотсапа" width="40" height="40">
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
HTML;
    }
}
