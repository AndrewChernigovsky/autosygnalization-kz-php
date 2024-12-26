export default class PrintDocument {
  constructor(element) {
    this.element = element;
    this.container = document.createElement('div');
    this.container.setAttribute('id', 'print-content');
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
    this.container.innerHTML = 'Содержимое для печати';
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

    const printWin = window.open('', '_blank', 'width=800,height=600');

    if (!printWin) {
      console.error('Не удалось открыть новое окно для печати.');
      return;
    }

    printWin.document.write(`
      <html>
        <head>
          <title>Печать</title>
          <style>
            /* Добавьте стили для печати, если необходимо */
            body {
              font-family: Arial, sans-serif;
              margin: 20px;
            }
          </style>
        </head>
        <body>
          ${divContent}
          <script>
            window.onload = function() {
              window.print(); // Вызываем печать после полной загрузки содержимого
              window.onafterprint = function() {
                window.close(); // Закрываем окно после печати
              };
            };
          </script>
        </body>
      </html>
    `);
    printWin.document.close(); // Закрываем документ для завершения записи
  }
}