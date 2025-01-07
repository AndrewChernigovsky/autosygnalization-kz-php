import { html, Component } from 'htm/preact';

export class CheckoutCompany extends Component {
  render() {
    return html`
      <legend>Данные компании</legend>
      <label>
        <p>Название компании</p>
        <input type="text" name="company-name" />
      </label>
      <label>
        <p>Юридический адрес</p>
        <input type="text" name="company-adress" />
      </label>
      <label>
        <p>Индекс</p>
        <input type="text" name="index" />
      </label>
      <label>
        <p>ИНН*</p>
        <input type="text" required name="INN" />
      </label>
      <label>
        <p>КПП*</p>
        <input type="text" required name="KPP" />
      </label>
      <label>
        <p>ОГРН*</p>
        <input type="text" required name="OGRN" />
      </label>
      <label>
        <p>БИК*</p>
        <input type="text" required name="BIK" />
      </label>
      <label>
        <p>Расчетный счет*</p>
        <input type="text" required name="cash-number" />
      </label>
      <label>
        <p>Телефон*</p>
        <input type="tel" required name="telephone" />
      </label>
    `;
  }
}
