// Модуль для работы с PDF превью
export function initPdfPreview() {
  // Находим все PDF iframe
  const pdfIframes = document.querySelectorAll('.pdf-preview-iframe');

  pdfIframes.forEach((iframe) => {
    // Добавляем класс загрузки
    const container = iframe.closest('.pdf-preview-link');
    if (container) {
      container.classList.add('loading');
    }

    // Обработчик загрузки iframe
    iframe.addEventListener('load', function () {
      if (container) {
        container.classList.remove('loading');
        container.classList.add('loaded');
      }
    });

    // Обработчик ошибки загрузки
    iframe.addEventListener('error', function () {
      if (container) {
        container.classList.remove('loading');
        container.classList.add('error');

        // Показываем fallback
        const fallback = document.createElement('div');
        fallback.className = 'pdf-fallback';
        fallback.innerHTML = `
          <div class="pdf-fallback-content">
            <span class="pdf-icon">📄</span>
            <p>PDF не загружен</p>
            <small>Нажмите для просмотра</small>
          </div>
        `;

        iframe.style.display = 'none';
        container.appendChild(fallback);
      }
    });
  });

  // Обработчик клика для PDF ссылок
  const pdfLinks = document.querySelectorAll('.pdf-preview-link');
  pdfLinks.forEach((link) => {
    link.addEventListener('click', function (e) {
      // Добавляем небольшую задержку для анимации
      setTimeout(() => {
        // Fancybox автоматически обработает клик
      }, 100);
    });
  });
}

// Функция для проверки поддержки PDF в браузере
export function checkPdfSupport() {
  const iframe = document.createElement('iframe');
  iframe.style.display = 'none';
  document.body.appendChild(iframe);

  try {
    iframe.src =
      'data:application/pdf;base64,JVBERi0xLjQKJcOkw7zDtsO8DQoxIDAgb2JqDQo8PA0KL1R5cGUgL0NhdGFsb2cNCi9QYWdlcyAyIDAgUg0KPj4NCmVuZG9iag0KMiAwIG9iag0KPDwNCi9UeXBlIC9QYWdlcw0KL0NvdW50IDANCi9LaWRzIFtdDQo+Pg0KZW5kb2JqDQp4cmVmDQowIDMNCjAwMDAwMDAwMDAgNjU1MzUgZg0KMDAwMDAwMDAwMCAwMDAwMCBuDQowMDAwMDAwMDAxIDAwMDAwIG4NCnRyYWlsZXINCjw8DQovU2l6ZSAzDQovUm9vdCAxIDAgUg0KL0luZm8gMyAwIFINCj4+DQpzdGFydHhyZWYNCjANCiUlRU9GDQo=';

    return new Promise((resolve) => {
      setTimeout(() => {
        const supported = iframe.contentDocument && iframe.contentDocument.body;
        document.body.removeChild(iframe);
        resolve(supported);
      }, 100);
    });
  } catch (e) {
    document.body.removeChild(iframe);
    return Promise.resolve(false);
  }
}
