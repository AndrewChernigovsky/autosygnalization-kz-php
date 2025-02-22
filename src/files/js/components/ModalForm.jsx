import { html, Component } from 'htm/preact';

export class ModalForm extends Component {
  constructor(props) {
    super(props);
    this.state = {
      name: '',
      phone: '',
    };
  }

  handleInputChange = (event) => {
    const { name, value } = event.target;
    this.setState({ [name]: value });
  };

  handleSubmit = (event) => {
    event.preventDefault();
    const { name, phone } = this.state;
    
    const formData = { name, phone };
    console.log('Отправляемые данные:', formData);

    const url = `${window.location.pathname.includes('/dist') ? '/dist' : ''}/files/php/data/form-quick-order.php`;
    
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData),
    })
      .then(response => {
        console.log('Статус ответа:', response.status);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        console.log('Ответ сервера:', data);
        if (data.success) {
          if (this.props.onClose) {
            this.props.onClose();
          }
          setTimeout(() => {
            alert('Заявка успешно отправлена!');
          }, 100);
          this.setState({ name: '', phone: '' });
        } else {
          const errorMessage = data.message || 'Неизвестная ошибка';
          alert(`Ошибка при отправке заказа: ${errorMessage}`);
          console.error('Детали ошибки:', data);
        }
      })
      .catch(error => {
        console.error('Ошибка:', error);
        if (!error.message.includes('JSON')) {
          alert('Произошла ошибка при отправке данных');
        }
      });
  };

  render() {
    return html`
      <section class="form" id="form-modal">
        <div class="form__wrapper">
          <h2 class="form__title">Оставьте заявку и мы вам перезвоним</h2>
          <form class="form__quick-order" action="../../php/data/form-quick-order.php" method="post" id="quick-order-form" onSubmit=${this.handleSubmit}>
            <ul class="form__list list-style-none">
              <li class="form__item">
                <label class="form__subtitle">Введите ФИО*:
                  <input class="form__input" type="text" name="name" id="name" placeholder="Ivanov Ivan Ivanovich"
                     required onInput=${this.handleInputChange} value=${this.state.name}>
                </label>
              </li>
              <li class="form__item">
                <label class="form__subtitle">Введите Телефон*:
                  <input class="form__input" type="tel" name="phone" id="phone" placeholder="+7 (777) 77 77 777"
                    required onInput=${this.handleInputChange} value=${this.state.phone}>
                </label>
              </li>
              // ${!this.props.fast && html`
              //   <li class="form__item form__item--textarea">
              //     <label class="form__subtitle">Оставьте Комментарий:
              //       <textarea class="form__input form__input--textarea" name="message" id="message"
              //         placeholder="Ваш комментарий" onInput=${this.handleInputChange} value=${this.state.message}></textarea>
              //     </label>
              //   </li>
              // `}
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
