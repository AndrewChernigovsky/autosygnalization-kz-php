import { Fancybox } from '@fancyapps/ui';

export function initFancybox() {
  Fancybox.bind('[data-fancybox]', {
    infinite: false,
    // Настройки для PDF файлов
    on: {
      done: (fancybox, slide) => {
        // Если это PDF файл, добавляем полные элементы управления
        if (slide.src && slide.src.endsWith('.pdf')) {
          // Убираем ограничения для PDF в полноэкранном режиме
          slide.src = slide.src.replace(
            '#toolbar=0&navpanes=0&scrollbar=0&view=FitH',
            ''
          );
        }
      },
    },
    // Настройки для изображений
    Image: {
      zoom: true,
      wheel: 'slide',
    },
    // Настройки для PDF
    Html: {
      video: {
        autoplay: false,
      },
    },
  });
}
