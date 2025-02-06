export default class PrintDocument {
  constructor(element) {
    this.element = element;
    this.container = document.createElement('div'); // Контейнер для печати
    this.init();
  }

  init() {
    this.element.addEventListener('click', () => {
      this.populateContainer(); // Заполняем контейнер данными
      this.printIframe();  // Печатаем содержимое через временный iframe
    });
  }

  populateContainer() {
    // Статическое содержимое для печати
    this.container.innerHTML = `
      <div>
        <h2>Auto Security - <span>продажа и установка автосигнализаций, 
        диагностика и ремонт автоэлектрики.</span></h2>
        <p>Beeline: +77077478212</p>
        <p>Kcell: +77017478212</p>
        <p>Whatsapp: +77077478212</p>
        <p>Сайт: www.autosecurity.kz</p>
        <p>Почта: autosecurity.kz@mail.ru</p>
        <p>Адрес: Казахстан, г.Алматы, ул.Абая 145г, бокс №15</p>
        <p>График работы: Вс. - Чт.: 10:00 - 18:00, Пт.: 10:00-15:00, Сб.: Выходной</p>
        <h3>КАК К НАМ ДОБРАТЬСЯ</h3>
        <p>Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, 
        перед речкой поворот направо, заезжаем на территорию СТО. Наш бокс №15.</p>
        <p>БУДЕМ РАДЫ ВИДЕТЬ ВАС В НАШЕМ УСТАНОВОЧНОМ ЦЕНТРЕ!</p>
      </div>
    `;
  }

  printIframe() {
    const iframe = document.createElement('iframe');
    document.body.appendChild(iframe);
  
    const iframeDoc = iframe.contentWindow || iframe.contentDocument; //получаем возможность записывать HTML-код и стили в документ iframe
    
    if (!iframe.contentWindow && !iframe.contentDocument) {
      console.error('Не удалось получить доступ к содержимому iframe.');
    return;
    }

    iframeDoc.document.write(`
      <!DOCTYPE html>
      <html>
      <head>
        <title>Печать</title>
        <style>
          body {
            font-family: Arial, sans-serif;
            margin: 20px;
          }

          h2 {
            text-align: center;
            font-size: 24px;
          }

          h3,
          .print-slogan {
            text-align: center;
          }

          p {
            font-size: 16px;
          }

          span {
            font-size: 16px;
            font-weight: 400;
          }

          .print-title {
            font-weight: 600;
          }

          .print-description {
            font-size: 16px;
            font-weight: 400;
            margin-left: 10px;
          }
        </style>
      </head>
      <body>
      ${this.container.innerHTML}
      </body>
      </html>
    `);
  
    iframeDoc.print();
    document.body.removeChild(iframe);
  }
}  
  
  
  
  
  
  