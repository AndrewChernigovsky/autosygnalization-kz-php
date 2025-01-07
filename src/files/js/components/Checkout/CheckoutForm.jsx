import { html, Component } from 'htm/preact';
import { CheckoutSelect } from './CheckoutSelect';
import { CheckoutCompany } from './CheckoutCompany';
export class CheckoutForm extends Component {
  constructor() {
    super();
    this.isCompany = false;
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
    console.log(e.target.value);
    const value = parseInt(e.target.value, 10); // Преобразуем строку в число
    this.setState({ isCompany: value === 2 }); // Устанавливаем состояние на основе числового значения
  };

  render() {
    return html`
      <p>Оформление заказа</p>
      <fieldset>
        <legend>Вы оформляете заказ как</legend>
        <label>
          <input
            type="radio"
            name="client_type"
            value="1"
            onChange=${this.changeFace}
            checked=${!this.state.isCompany}
          />
          Физическое лицо
        </label>
        <label>
          <input
            type="radio"
            name="client_type"
            value="2"
            onChange=${this.changeFace}
          />
          Юридическое лицо
        </label>
      </fieldset>
      <fieldset>
        <legend>Выберите город</legend>
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
        <legend>Адрес доставки</legend>
        <label>
          <p>Улица*</p>
          <input type="text" required name="street" />
        </label>
        <label>
          <p>Индекс*</p>
          <input type="text" required name="index" />
        </label>
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
      </fieldset>
      <fieldset>
        <legend>Данные клиента</legend>
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
      ${this.state.isCompany &&
      html`
        <fieldset>
          <${CheckoutCompany} />
        </fieldset>
      `}
      <fieldset>
        <legend>Способ доставки</legend>
        <label>
          <p>Быстрая доставка за 1-2 рабочих дня по г. Алматы - 200 тг.</p>
          <p>
            Курьер доставит Ваш заказ за 1-2 рабочих дня с момента оформления
            заказа. Данный вид доставки возможен только в пределах
            административных границ г.Алматы, а если далее - то от 3000 тг и
            выше, что оговаривается отдельно.
          </p>
          <input type="radio" required name="quick-delivery" checked />
        </label>
        <label>
          <p>
            Доставка курьером при сумме заказа от 40000 тг по г. Алматы -
            Бесплатно.
          </p>
          <p>
            Срок доставки - от 2 до 7 рабочих дней с момента оформления заказа.
            Данный вид доставки действует в пределах административных границ г.
            Алматы..
          </p>
          <input type="radio" required name="delivery-courer" />
        </label>
        <label>
          <p>Пункт выдачи - Бесплатно</p>
          <p>
            Самовывоз товара из магазина, расположенного по адресу: Казахстан,
            г. Алматы, ул. Щепеткова, 122. Срок хранения заказа - 3 дня.
          </p>
          <input type="radio" required name="place-end" />
        </label>
        <label>
          <p>Доставка в отделение Почты г. Алматы - Бесплатно</p>
          <p>
            Срок доставки — в среднем от 2 до 7 рабочих дней с момента
            оформления заказа. Срок хранения заказа в отделении Почты - 10
            рабочих дней.
          </p>
          <input type="radio" required name="delivery-in-part-post-almaty" />
        </label>
      </fieldset>
      <fieldset>
        <legend>Способ оплаты</legend>
        <label>
          <p>Оплата с помощью Каспи</p>
          <p>Доступная система: Каспи</p>
          <p>Номер кошелька: +77077478212</p>
          <p>
            При поступлении средств на кошелек Ваш заказ будет считаться
            оформленным.
          </p>
          <input type="radio" required name="pay-caspy-bank" checked />
        </label>
        <label>
          <p>Наличными при получении</p>
          <p>
            Данный вид оплаты действует только для г.Алматы и его окрестностей
          </p>
          <input type="radio" required name="cash" />
        </label>
      </fieldset>
      <fieldset>
        <legend>Комментарий к заказу</legend>
        <label>
          <p>Ваш комментарий</p>
          <textarea name="comments" id="comments"></textarea>
        </label>
        <label>
          <p>Перезвоните мне</p>
          <input name="call-me" id="call-me" />
        </label>
      </fieldset>
      <input type="checkbox" id="privacy" name="privacy" required />
      <label for="privacy">
        Оформляя заказ, подтверждаю, что я ознакомлен и согласен с условиями
        <a href="#" onClick=${(e) => this.openWindow(e, 'policy')}>
          политики конфиденциальности</a
        >
        и договором
        <a href="#" onClick=${(e) => this.openWindow(e, 'deal')}
          >купли-продажи</a
        >
      </label>
    `;
  }
}
