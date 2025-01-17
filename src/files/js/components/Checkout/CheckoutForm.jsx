import { html, Component } from 'htm/preact';
import { CheckoutSelect } from './CheckoutSelect';
import { CheckoutCompany } from './CheckoutCompany';

export class CheckoutForm extends Component {
  constructor() {
    super();
    this.state = {
      isCompany: false, // Состояние для отслеживания типа клиента
      selectedClientType: '1', // Состояние для отслеживания выбранного типа клиента
    };
    this.PRODUCTION = window.location.href.includes('/dist/');
    this.path2Policy = `${
      this.PRODUCTION ? '/dist/' : '/'
    }files/docs/policy.txt`;
    this.path2Deal = `${this.PRODUCTION ? '/dist/' : '/'}files/docs/deal.txt`;
  }

  openWindow = (e, type) => {
    e.preventDefault();
    const path = type === 'policy' ? this.path2Policy : this.path2Deal;
    window.open(
      path,
      'window',
      'width=800,height=600,scrollbars=yes,status=no,toolbar=no,menubar=no,resizable=yes,location=no'
    );
  };

  changeFace = (e) => {
    const value = e.target.value;
    this.setState({
      selectedClientType: value,
      isCompany: value === '2', // Проверяем, является ли выбранный тип "Юридическое лицо"
    });
  }; 

  render() {
    const { isCompany, selectedClientType} =
      this.state; // Деструктурируем состояние для удобства

    return html`
      <p class="checkout-form__title">Оформление заказа</p>
      <fieldset>
        <legend>Вы оформляете заказ как:</legend>
        <label
          class="${selectedClientType === '1'
            ? 'checkout-form__label-radio selected'
            : 'checkout-form__label-radio'}"
        >
          <input
            type="radio"
            name="client_type"
            value="1"
            onChange=${this.changeFace}
            checked=${selectedClientType === '1'}
          />
          Физическое лицо
        </label>
        <label
          class="${selectedClientType === '2'
            ? 'checkout-form__label-radio selected'
            : 'checkout-form__label-radio'}"
        >
          <input
            type="radio"
            name="client_type"
            value="2"
            onChange=${this.changeFace}
            checked=${selectedClientType === '2'}
          />
          Юридическое лицо
        </label>
      </fieldset>
      <fieldset>
        <legend>Выберите город:</legend>
        <label>
          <p>Страна*</p>
          <${CheckoutSelect} />
        </label>
        <label>
          <p>Город*</p>
          <input type="text" required name="city" />
        </label>
      </fieldset>
      <fieldset>
        <legend>Адрес доставки:</legend>
        <label>
          <p>Улица*</p>
          <input type="text" required name="street" />
        </label>
        <label>
          <p>Индекс*</p>
          <input type="text" required name="index" />
        </label>
        <div>
          <label>
            <p>Дом*</p>
            <input type="text" required name="house" />
          </label>
          <label>
            <p>Корпус*</p>
            <input type="text" required name="corpus" />
          </label>
          <label>
            <p>Квартира*</p>
            <input type="text" required name="apartment" />
          </label>
        </div>
      </fieldset>
      <fieldset>
        <legend>Данные клиента:</legend>
        <label>
          <p>Ваше имя*</p>
          <input type="text" required name="user-name" />
        </label>
        <label>
          <p>Ваша фамилия*</p>
          <input type="text" required name="user-lastname" />
        </label>
        <label>
          <p>Телефон*</p>
          <input type="tel" required name="telephone" />
        </label>
        <label>
          <p>Электронный адрес*</p>
          <input type="email" required name="email" />
        </label>
      </fieldset>
      ${isCompany &&
      html`
        <fieldset>
          <${CheckoutCompany} />
        </fieldset>
      `}
      <fieldset class="checkout-form__fieldset">
        <legend>Способ доставки:</legend>
        <label class="checkout-form__label">
          <input
            type="radio"
            required
            value="quick-delivery"
            name="delivery-method"
            checked
          />
          <p class="checkout-form__subtitle">
            Быстрая доставка за 1-2 рабочих дня по г. Алматы - 200 тг.
          </p>
          <p class="checkout-form__description">
            Курьер доставит Ваш заказ за 1-2 рабочих дня с момента оформления
            заказа. Данный вид доставки возможен только в пределах
            административных границ г.Алматы, а если далее - то от 3000 тг и
            выше, что оговаривается отдельно.
          </p>
        </label>
        <label class="checkout-form__label">
          <input
            type="radio"
            required
            value="courier-delivery"
            name="delivery-method"
          />
          <p class="checkout-form__subtitle">
            Доставка курьером при сумме заказа от 40000 тг по г. Алматы -
            Бесплатно.
          </p>
          <p class="checkout-form__description">
            Срок доставки - от 2 до 7 рабочих дней с момента оформления заказа.
            Данный вид доставки действует в пределах административных границ г.
            Алматы..
          </p>
        </label>
        <label class="checkout-form__label">
          <input
            type="radio"
            required
            value="pickup-point"
            name="delivery-method"
          />
          <p class="checkout-form__subtitle">Пункт выдачи - Бесплатно</p>
          <p class="checkout-form__description">
            Самовывоз товара из магазина, расположенного по адресу: Казахстан,
            г. Алматы, ул. Щепеткова, 122. Срок хранения заказа - 3 дня.
          </p>
        </label>
        <label class="checkout-form__label">
          <input
            type="radio"
            required
            value="post-office-delivery"
            name="delivery-method"
          />
          <p class="checkout-form__subtitle">
            Доставка в отделение Почты г. Алматы - Бесплатно
          </p>
          <p class="checkout-form__description">
            Срок доставки — в среднем от 2 до 7 рабочих дней с момента
            оформления заказа. Срок хранения заказа в отделении Почты - 10
            рабочих дней.
          </p>
        </label>
      </fieldset>
      <fieldset class="checkout-form__fieldset">
        <legend>Способ оплаты:</legend>
        <label class="checkout-form__label">
          <p class="checkout-form__subtitle">Оплата с помощью Каспи</p>
          <p class="checkout-form__description">Доступная система: Каспи</p>
          <p class="checkout-form__description">Номер кошелька: +77077478212</p>
          <p class="checkout-form__description">
            При поступлении средств на кошелек Ваш заказ будет считаться
            оформленным.
          </p>
          <input
            type="radio"
            required
            name="payment-method"
            value="pay-caspy-bank"
            checked
          />
        </label>
        <label class="checkout-form__label">
          <p class="checkout-form__subtitle">Наличными при получении</p>
          <p class="checkout-form__description">
            Данный вид оплаты действует только для г.Алматы и его окрестностей
          </p>
          <input type="radio" required name="payment-method" value="cash" />
        </label>
      </fieldset>
      <fieldset class="checkout-form__comment">
        <legend>Комментарий к заказу:</legend>
        <label>
          <p>Ваш комментарий</p>
          <textarea name="comments" id="comments"></textarea>
        </label>
        <label>
          <input type="checkbox" name="call-me" id="call-me" />
          <p>Перезвоните мне</p>
        </label>
      </fieldset>
      <div class="checkout-form__wrapper">
        <input
          class="checkout-form__checkbox"
          type="checkbox"
          id="privacy"
          name="privacy"
          required
        />
        <label class="checkout-form__label-privacy" for="privacy">
          Оформляя заказ, подтверждаю, что я ознакомлен и согласен с условиями
          <a href="#" onClick=${(e) => this.openWindow(e, 'policy')}>
            политики конфиденциальности</a
          >
          и договором
          <a href="#" onClick=${(e) => this.openWindow(e, 'deal')}
            >купли-продажи</a
          >
        </label>
      </div>
    `;
  }
}
