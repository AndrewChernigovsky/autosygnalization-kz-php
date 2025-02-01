export default class PrintDocument {
  constructor(element) {
    this.element = element;
    this.container = document.createElement('div');
    document.body.appendChild(this.container);
    this.init();
  }

  init() {
    this.element.addEventListener('click', () => {
      this.populateContainer();
      this.printDiv(this.container);
    });
  }

  populateContainer() {
    this.container.innerHTML = `
      <div>
        <h2>Auto Security - <span>продажа и устaновка автосигнализаций, 
        диагностика и ремонт автоелектрики.</span></h2>
        <br>
        <p class="print-title">Beeline:<span class="print-description">+77077478212</span></p>
        <p class="print-title">Kcell:<span class="print-description">+77017478212</span></p>
        <p class="print-title">Whatsapp:<span class="print-description">+77077478212</span></p>
        <p class="print-title">Сайт:<span class="print-description">www.autosecurity.kz</span></p>
        <p class="print-title">Почта:<span class="print-description">autosecurity.kz@mail.ru</span></p>
        <p class="print-title">Адрес:<span class="print-description">Казахстан, г.Алматы, ул.Абая 145г, бокс №15</span></p>
        <p class="print-title">График работы:<span class="print-description">Вс. - Чт.: 10:00 - 18:00, Пт.: 10:00-15:00, Сб.: Выходной</span></p>
        <br>
        <h3>КАК К НАМ ДОБРАТЬСЯ</h3>
        <p>Едем по Абая со стороны Мате Залка в сторону Большой Алматинки, 
        перед речкой поворот направо, заезжаем на территорию СТО. Наш бокс №15.</p>
        <br>
        <p class="print-slogan">БУДЕМ РАДЫ ВИДЕТЬ ВАС В НАШЕМ УСТАНОВОЧНОМ ЦЕНТРЕ!</p>
      </div>
    `;
  }      

  printDiv(element) {
    if (!element) {
      console.error('Элемент для печати не найден.');
      return;
    }
  
    const divContent = element.innerHTML;
    if (!divContent) {
      console.error('Содержимое для печати отсутствует.');
      return;
    }

    const screenWidth = window.innerWidth;
    let width, height;
    if (screenWidth <= 767) {
      width = 400;
      height = 600;
    } else if (screenWidth >= 768 && screenWidth <= 1024) {
      width = 700;
      height = 600;
    } else {
      width = 1024;
      height = 800;
    }

    const printWin = window.open('', '_blank', `width=${width},height=${height}`);
    if (!printWin) {
      console.error('Не удалось открыть новое окно для печати.');
      return;
    }

    printWin.document.write(`
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
          ${divContent}
          <script>
            window.onload = function() {
              window.print(); // Открываем диалог печати
              window.onafterprint = function() {
                window.close(); // Закрываем окно после завершения печати
              };
            };
          </script>
        </body>
      </html>
    `);

    printWin.document.close();
  }
}