<?php

namespace SECTIONS;

use DATA\BrandsData;

function formSection()
{
  // Fetch brands data
  $brandsData = new BrandsData();
  $brands = $brandsData->getData();
  $year_holder = date('Y');

  // Validate brands data
  if (empty($brands) || !is_array($brands)) {
    return '<p>Информация о марках недоступна.</p>';
  }

  ob_start(); // Start output buffering
  ?>
  <section class="form" id="form">
    <div class="form__wrapper">
      <h2 class="form__title secondary-title">Форма обратной связи</h2>
      <form class="form__main-form main-form" action="/server/php/process_form.php" method="post" id="feedback-form">
        <!-- <fieldset class="form__fieldset">
          <legend class="form__subtitle">Марка:</legend>
          <div class="form__group-radio">
            <?php foreach ($brands as $brand): ?>
              <div class="form__radio-wrapper">
                <input class="form__radio" type="radio" name="mark" id="<?= htmlspecialchars($brand['name']) ?>"
                  style="background-image: url('<?= htmlspecialchars($brand['path']) ?>');">
                <label class="form__radio-label" for="<?= htmlspecialchars($brand['name']) ?>">
                  <?= htmlspecialchars($brand['name']) ?>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </fieldset> -->
        <ul class="form__list list-style-none">
          <li class="form__item">
            <label class="form__subtitle">Марка/Модель:
              <input class="form__input" type="text" name="model" id="model" placeholder="Toyota Land Cruiser 300">
            </label>
          </li>
          <li class="form__item">
            <label class="form__subtitle">Год выпуска:
              <input class="form__input" type="number" name="release-year" id="release-year"
                placeholder="<?= htmlspecialchars($year_holder) ?>" pattern="\d{4}">
            </label>
          </li>
          <li class="form__item">
            <label class="form__subtitle">Имя*:
              <input class="form__input" type="text" name="name" id="name" placeholder="Алексей"
                pattern="[A-Za-zА-Яа-яЁё\s]+" required>
            </label>
          </li>
          <li class="form__item">
            <label class="form__subtitle">Телефон*:
              <input class="form__input" type="tel" name="phone" id="phone" placeholder="(777) 77 77 777"
                pattern="^\+?[0-9\s\(\)]{5,20}$" required>
            </label>
          </li>
          <li class="form__item form__item--textarea">
            <label class="form__subtitle">Ваше сообщение:
              <textarea class="form__input form__input--textarea" name="message" id="message" placeholder="Какой товар или вид услуги Вас заинтересовал?
Укажите комплектацию Вашего автомобиля: коробку передач, способ запуска, вид топлива"></textarea>
            </label>
          </li>
        </ul>
        <div class="g-recaptcha" data-callback="onReCaptchaSuccess" id="recaptcha-field1"></div>
        <!-- <button type="button" id="captcha-render">Капча!</button> -->
        <button class="form__button y-button-primary" type="submit" disabled>Отправить заявку</button>
      </form>
    </div>
  </section>
  <?php
  return ob_get_clean(); // Return the buffered content
}
