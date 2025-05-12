<?php

namespace COMPONENTS;

use DATA\ContactsData;

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
          'Звонок на ' .
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
     <span>Написать в </span><svg  alt="иконка телеграма" width="40" height="40"><use href="/client/vectors/sprite.svg#telegram"></use></svg>
    </a>
    <a href="https://clck.ru/3FQ7aJ">
     <span>Написать в </span><svg  alt="иконка вотсапа" width="40" height="40"><use href="/client/vectors/sprite.svg#whatsapp"></use></svg>
    </a>
  </div>
</div>

<div class="popup modal-form">
  <button type="button" id="modal-form-close" class="popup__button--close">
    <span class="visually-hidden">
      Закрыть форму
    </span>
  </button>
  <div class="popup-body"><section class="form" id="form-modal"><div class="form__wrapper"><h2 class="form__title">Оставьте заявку и мы вам перезвоним</h2><form class="form__main-form" action="#" method="post" id="feedback-form"><ul class="form__list list-style-none"><li class="form__item"><label class="form__subtitle">Имя*:<input class="form__input" type="text" name="name" id="name" placeholder="Алексей" required=""></label><li class="form__item"><label class="form__subtitle">Телефон*:<input class="form__input" type="tel" name="phone" id="phone" placeholder="+7 (777) 77 77 777" required=""></label><li class="form__item form__item--textarea"><label class="form__subtitle">Оставьте Комментарий:<textarea class="form__input form__input--textarea" name="message" id="message" placeholder="Ваш комментарий"></textarea></label></li></li><button class="form__button y-button-primary" type="submit">Отправить заявку</button></li></ul></form></div></section>
  </div>
</div>
HTML;
  }
}
