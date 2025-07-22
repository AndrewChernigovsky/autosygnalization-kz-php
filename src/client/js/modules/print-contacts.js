export default class PrintDocument {
  constructor(element) {
    this.element = element;
    this.container = document.createElement('div'); // Контейнер для печати
    this.init();
  }

  init() {
    this.element.addEventListener('click', async () => {
      const data = await this.getData();

      this.populateContainer(data.data); // Заполняем контейнер данными
      this.printIframe(); // Печатаем содержимое через временный iframe
    });
  }

  async getData() {
    try {
      const response = await fetch(
        '/server/php/admin/api/contacts/contact.php',
        {
          method: 'GET',
          headers: {
            'Content-type': 'application/json',
          },
        }
      );

      if (!response.ok) {
        throw new Error(`Ошибка сервера: ${response.status}`);
      }

      const data = await response.json();
      console.log('Данные полученны:', data);

      return data;
    } catch (error) {
      console.error('Не удаолсь получить список контактов', error);
      return null;
    }
  }
  // populateContainer(data) {
  //   const getByType = (type) => data.filter((item) => item.type === type);

  //   const phones = getByType('contact-phone')
  //     .map((item) => `<p>${item.title} ${item.content}</p>`)
  //     .join('');

  //   const socials = getByType('social')
  //     .map((item) => `<p>${item.title} ${item.content}</p>`)
  //     .join('');

  //   const address = getByType('address')
  //     .map((item) => `<p><strong>${item.title}</strong> ${item.content}</p>`)
  //     .join('');
  //   const email = getByType('email')
  //     .map((item) => `<p><strong>${item.title}</strong> ${item.content}</p>`)
  //     .join('');
  //   const site = getByType('site')
  //     .map((item) => `<p><strong>${item.title}</strong> ${item.content}</p>`)
  //     .join('');
  //   const schedule = getByType('schedule')
  //     .map((item) => `<p><strong>${item.title}</strong> ${item.content}</p>`)
  //     .join('');

  //   const locationDescription = getByType('location-description')
  //     .map((item) => `<h3>${item.title}</h3><br/><p>${item.content}</p>`)
  //     .join('');

  //   this.container.innerHTML = `
  //   <div>
  //     <h2>Auto Security - <span>продажа и установка автосигнализаций, диагностика и ремонт автоэлектрики.</span></h2>

  //     ${phones}
  //     ${socials}

  //     ${site}
  //     ${email}
  //     ${address}
  //     ${schedule}

  //     ${locationDescription}

  //     <p>БУДЕМ РАДЫ ВИДЕТЬ ВАС В НАШЕМ УСТАНОВОЧНОМ ЦЕНТРЕ!</p>
  //   </div>
  // `;
  // }

  populateContainer(data) {
    const getByType = (type) => data.filter((item) => item.type === type);

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
}
