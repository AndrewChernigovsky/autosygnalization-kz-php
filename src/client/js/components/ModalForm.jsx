import { html, Component } from 'htm/preact';

export class ModalForm extends Component {
  constructor(props) {
    super(props);
    this.state = {
      name: '',
      phone: '',
      message: '',
    };
  }

  handleInputChange = (event) => {
    const { name, value } = event.target;
    this.setState({ [name]: value });
  };

  handleSubmit = (event) => {
    event.preventDefault();
    const { name, phone, message } = this.state;

    console.log('Отправка данных:', { name, phone, message });

    this.setState({ name: '', phone: '', message: '' });

    if (this.props.onClose) {
      this.props.onClose();
    }
  };

  render() {
    return html`
      <section class="form" id="form-modal">
        <div class="form__wrapper">
          <h2 class="form__title">Оставьте заявку и мы вам перезвоним</h2>
          <form class="form__main-form" action="#" method="post" id="feedback-form">
            <ul class="form__list list-style-none">
              <li class="form__item">
                <label class="form__subtitle">Имя*:
                  <input class="form__input" type="text" name="name" id="name" placeholder="Алексей"
                   required onInput=${this.handleInputChange} value=${
      this.state.name
    }>
                </label>
              </li>
              <li class="form__item">
                <label class="form__subtitle">Телефон*:
                  <input class="form__input" type="tel" name="phone" id="phone" placeholder="+7 (777) 77 77 777"
                  required onInput=${this.handleInputChange} value=${
      this.state.phone
    }>
                </label>
              </li>
              ${
                !this.props.fast &&
                html`
                  <li class="form__item form__item--textarea">
                    <label class="form__subtitle"
                      >Оставьте Комментарий:
                      <textarea
                        class="form__input form__input--textarea"
                        name="message"
                        id="message"
                        placeholder="Какой товар или вид услуги Вас заинтересовал?"
                        onInput=${this.handleInputChange}
                        value=${this.state.message}
                      ></textarea>
                    </label>
                  </li>
                `
              }
            </ul>
            <button class="form__button y-button-primary" type="submit">Отправить заявку</button>
          </form>
        </div>
  </div>
  </div>
      </section>
    `;
  }
}
