import Swal from 'sweetalert2';

export default class DownloadPrices {
  constructor(element) {
    this.element = element;
    this.container = document.createElement('div'); // Контейнер для печати
    // Удаляем кастомный лоадер
    this.init();
  }

  init() {
    this.element.addEventListener('click', async () => {
      if (Swal) {
        Swal.fire({
          title: 'Пожалуйста, подождите...',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
      }
      const data = await this.getData();
      if (Swal) {
        Swal.close();
      }
      if (!data) {
        alert('Ошибка загрузки данных!');
        return;
      }
      this.populateContainer(data.contacts, data.services, data.products); // Заполняем контейнер данными
      this.printIframe(); // Печатаем содержимое через временный iframe
    });
  }

  async getData() {
    try {
      const [contactsRes, servicesRes, productsRes] = await Promise.all([
        fetch('/server/php/admin/api/contacts/contact.php', {
          method: 'GET',
          headers: { 'Content-type': 'application/json' },
        }),
        fetch('/server/php/api/services/get_all_services.php', {
          method: 'GET',
          headers: { 'Content-type': 'application/json' },
        }),
        fetch('/server/php/api/products/get_all_products.php?services=1', {
          method: 'GET',
          headers: { 'Content-type': 'application/json' },
        }),
      ]);

      const contactsText = await contactsRes.text();
      const servicesText = await servicesRes.text();
      const productsText = await productsRes.text();

      // console.log('Ответ сервера (контакты):', contactsText);
      // console.log('Ответ сервера (услуги):', servicesText);
      // console.log('Ответ сервера (продукты):', productsText);

      if (!contactsRes.ok) throw new Error(`Ошибка сервера (контакты): ${contactsRes.status}`);
      if (!servicesRes.ok) throw new Error(`Ошибка сервера (услуги): ${servicesRes.status}`);
      if (!productsRes.ok) throw new Error(`Ошибка сервера (продукты): ${productsRes.status}`);

      const contactsData = JSON.parse(contactsText);
      const servicesData = JSON.parse(servicesText);
      const productsData = JSON.parse(productsText);
      return { contacts: contactsData, services: servicesData, products: productsData };
    } catch (error) {
      console.error('Не удалось получить данные', error);
      return null;
    }
  }

  populateContainer(contacts, services, products) {
    // console.log(contacts, 'contacts');
    // console.log(services, 'services');
    // console.log(products, 'products');
    const getByType = (type) => contacts.data.filter((item) => item.type === type);

    const phones = getByType('contact-phone')
      .map((item) => `<p>${item.title} ${item.content}</p>`)
      .join('');

    const socials = getByType('social')
      .map((item) => `<p>${item.title} ${item.content}</p>`)
      .join('');

    const address = getByType('address')
      .map((item) => `<p><strong>${item.title}</strong> ${item.content}</p>`)
      .join('');

    const email = getByType('email')
      .map((item) => `<p><strong>${item.title}</strong> ${item.content}</p>`)
      .join('');

    const site = getByType('site')
      .map((item) => `<p><strong>${item.title}</strong> ${item.content}</p>`)
      .join('');

    const schedule = getByType('schedule')
      .map((item) => `<p><strong>${item.title}</strong> ${item.content}</p>`)
      .join('');

    const locationDescription = getByType('location-description')
      .map((item) => `<h3>${item.title}</h3><p>${item.content}</p>`)
      .join('');

    const servicesHtml = services.main
      .map((item) => `<p>${item.name} ${item.cost} ${item.currency}</p><p>${item.description}</p>`)
      .join('');

    const servicesAddedHtml = services.added
      .map((item) => `<p>${item.title} ${item.price}</p>`)
      .join('');

    const productsHtml = Object.entries(products.category)
      .map(([categoryName, productsArray]) => {
        const productsList = productsArray
          .map(product => `
            <div class="product">
              <h4>${product.title} - ${product.price || ''} ${product.currency || ''}</h4>
              <p>установка от ${product.prices[0].price || ''} ${product.currency || ''}</p>
            </div>
          `)
          .join('');
        return `
          <section class="product-category">
            ${productsList}
          </section>
        `;
      })
      .join('');

    this.container.innerHTML = `
    <div>
      <h2>Auto Security <br/><span>Продажа и установка автосигнализаций, диагностика и ремонт автоэлектрики.</span></h2>

      <div class="section">
        <h3>Контактные телефоны</h3>
        ${phones}
      </div>

      <div class="section">
        <h3>Социальные сети</h3>
        ${socials}
      </div>

      <div class="section">
        <h3>Сайт и почта</h3>
        ${site}
        ${email}
      </div>

      <div class="section">
        <h3>Адрес и график</h3>
        ${address}
        ${schedule}
      </div>

      <div class="section">
        ${locationDescription}
      </div>

      <p class="footer">БУДЕМ РАДЫ ВИДЕТЬ ВАС В НАШЕМ УСТАНОВОЧНОМ ЦЕНТРЕ!</p>

      <div class="section">
        <h3>Прайс по оборудованию Starline и цены на установку</h3>
        ${productsHtml}
      </div>

      <div class="section">
        <h3>Прайс на дополнительные услуги</h3>
        ${servicesAddedHtml}
      </div>
    </div>
  `;
  }

  printIframe() {
    const iframe = document.createElement('iframe');
    document.body.appendChild(iframe);

    const iframeDoc = iframe.contentWindow || iframe.contentDocument;

    if (!iframe.contentWindow && !iframe.contentDocument) {
      console.error('Не удалось получить доступ к содержимому iframe.');
      return;
    }

    iframeDoc.document.write(`
    <!DOCTYPE html>
    <html lang="ru">
    <head>
      <meta charset="UTF-8">
      <title>Печать</title>
      <style>
        body {
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          margin: 40px;
          padding: 20px;
          background: #fff;
          color: #222;
        }

        h2 {
          text-align: center;
          font-size: 28px;
          margin-bottom: 10px;
          color: #2c3e50;
        }

        h3 {
          text-align: center;
          font-size: 20px;
          margin-top: 0;
          color: #34495e;
        }

        p {
          font-size: 16px;
          margin: 6px 0;
        }

        strong {
          color: #2d3436;
        }

        span {
          font-size: 16px;
          font-weight: 400;
        }

        .section {
          margin-top: 20px;
          padding: 15px;
          padding-top: 0;
          border: 1px solid #ccc;
          border-radius: 6px;
          background-color: #f9f9f9;
          break-inside: avoid;
          page-break-inside: avoid;
          display: block;
        }

        .footer {
          margin-top: 40px;
          text-align: center;
          font-weight: bold;
          font-size: 17px;
        }

      </style>
    </head>
    <body>
      ${this.container.innerHTML}
    </body>
    </html>
  `);

    iframeDoc.close();

    setTimeout(() => {
      iframe.contentWindow.focus();
      iframe.contentWindow.print();
      document.body.removeChild(iframe);
    }, 500);
  }

  // Удаляем методы showLoader и hideLoader
}
