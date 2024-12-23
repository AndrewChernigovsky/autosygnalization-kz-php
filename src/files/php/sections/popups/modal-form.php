<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/contacts.php';
include __DIR__ . '/../../data/brands.php';


$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();
?>

<div class="popup">
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

<div class="popup modal-form">
  <button type="button" id="modal-form-close" class="popup__button--close">
    <span class="visually-hidden">
      Закрыть форму
    </span>
  </button>
  <section class="form" id="form-modal">
    <div class="form__wrapper">
      <h2 class="form__title">Оставьте заявку
        и мы вам перезвоним</h2>
      <form class="form__main-form" action="#" method="post" id="feedback-form">
        <ul class="form__list list-style-none">
          <li class="form__item">
            <label class="form__subtitle">Введите ФИО*:
              <input class="form__input" type="text" name="name" id="name" placeholder="Ivanov Ivan Ivanovich"
                pattern="[A-Za-z\s]+" required>
            </label>
          </li>
          <li class="form__item">
            <label class="form__subtitle">Введите Телефон*:
              <input class="form__input" type="tel" name="phone" id="phone" placeholder="+7 (777) 77 77 777"
                pattern="[0-9]+" required>
            </label>
          </li>
          <li class="form__item form__item--textarea">
            <label class="form__subtitle">Оставьте Комментарий:
              <textarea class="form__input form__input--textarea" name="message" id="message"
                placeholder="Вы купили автомобиль и желаете защитить его, установив сигнализацию?  Вы любите комфорт и хотите установить автозапуск на Ваше авто?  Вам необходимо отслеживать Ваш транспорт по GPS?  Обращайтесь к нам, и мы поможем Вам решить эти задачи!"></textarea>
            </label>
          </li>
        </ul>
        <button class="form__button y-button-primary" type="submit">Отправить заявку</button>
      </form>
    </div>
  </section>
</div>