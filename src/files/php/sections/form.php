<section class="form" id="form">
  <div class="form__wrapper">
    <h2 class="form__title">Форма обратной связи</h2>
    <form class="form__main-form" action="#" method="post" id="feedback-form">
      <fieldset class="form__fieldset">
        <legent class="form__subtitle">Марка:</legent>
        <div class="form__group-radio">
          <input class="form__radio form__radio--genesis" type="radio" name="mark" id="genesis" checked>
          <label class="visually-hidden" for="genesis">Genesis</label>
          <input class="form__radio form__radio--kia" type="radio" name="mark" id="kia">
          <label class="visually-hidden" for="kia">Kia</label>
          <input class="form__radio form__radio--lexus" type="radio" name="mark" id="lexus">
          <label class="visually-hidden" for="lexus">Lexus</label>
          <input class="form__radio form__radio--toyota" type="radio" name="mark" id="toyota">
          <label class="visually-hidden" for="toyota">Toyota</label>
          <input class="form__radio form__radio--vw" type="radio" name="mark" id="vw">
          <label class="visually-hidden" for="vw">Volkswagen</label>
          <input class="form__radio form__radio--audi" type="radio" name="mark" id="audi">
          <label class="visually-hidden" for="audi">Audi</label>
        </div>
      </fieldset>
      <ul class="form__list list-style-none">
        <li class="form__item">
          <label class="form__subtitle">Модель автомобиля:
            <input class="form__input" type="text" name="model" id="model" placeholder="Vesta" required>
          </label>
        </li>
        <li class="form__item">
          <label class="form__subtitle">Год выпуска:
            <input class="form__input" type="number" name="release-year" id="release-year" placeholder="2024"
              pattern="\d{4}" required>
          </label>
        </li>
        <li class="form__item">
          <label class="form__subtitle">Имя:
            <input class="form__input" type="text" name="name" id="name" placeholder="Ivanov Ivan Ivanovich"
              pattern="[A-Za-z\s]+" required>
          </label>
        </li>
        <li class="form__item">
          <label class="form__subtitle">Телефон:
            <input class="form__input" type="tel" name="phone" id="phone" placeholder="+7 (777) 77 77 777"
              pattern="[0-9]+" required>
          </label>
        </li>
        <li class="form__item form__item--textarea">
          <label class="form__subtitle">Ваше сообщение:
            <textarea class="form__input form__input--textarea" name="message" id="message"
              placeholder="Вы купили автомобиль и желаете защитить его, установив сигнализацию?  Вы любите комфорт и хотите установить автозапуск на Ваше авто?  Вам необходимо отслеживать Ваш транспорт по GPS?  Обращайтесь к нам, и мы поможем Вам решить эти задачи!"
              required></textarea>
          </label>
        </li>
      </ul>
      <div class="g-recaptcha" data-callback="onReCaptchaSuccess" id="recaptcha-field1"></div>
      <button type="button" id="captcha-render">Капча!</button>
      <button class="form__button y-button-primary" type="submit">Отправить заявку</button>
    </form>
  </div>
</section>