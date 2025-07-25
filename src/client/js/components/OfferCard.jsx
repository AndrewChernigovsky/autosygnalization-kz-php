import { html, Component } from 'htm/preact';

export class OfferCard extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: null,
    };
  }

  componentDidMount() {
    this.fetchOffersCards();
  }

  fetchOffersCards() {
    const url = 'server/php/api/offers.php';
    const options = {
      method: 'GET',
    };

    fetch(url, options)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => this.setState({ data }))
      .catch((error) => {
        console.error('Error:', error);
        this.setState({ errorMessage: 'Не удалось загрузить предложения' });
      });
  }

  render() {
    const { data } = this.state;

    if (!data) {
      return html`<div>Загрузка...</div>`;
    }

    return this.renderProducts(data);
  }

  renderProducts(data) {
    const components = data.map((item) => {
      return html`
        <article class="article">
          <a href="${item.href}">
            <h4 class="article__title">${item.title}</h4>
            <img
              class="article__image"
              src="${item.image}"
              alt="${item.alt}"
              width="130"
              height="130"
            />

            <div class="article__menu-btns">
              <p class="article__quantity y-button-secondary">
                <span>${item.quantity}</span> товаров
              </p>
              <p class="article__link y-button-primary link">в раздел</p>
            </div>
          </a>
        </article>
      `;
    });

    return html` ${components} `;
  }
}
