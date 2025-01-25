export default class DownloadDocument {
  constructor(button) {
    this.button = button;
    this.init();
  }

  init() {
    // Добавляем обработчик события на кнопку
    this.button.addEventListener('click', async () => {
      const content = await this.fetchPageContent('../../pages/price/price.php');
      if (content) {
        this.downloadFile(content, 'price.html');
      }
    });
  }

  async fetchPageContent(url) {
    try {
      // Делаем запрос к серверу для получения содержимого страницы
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`Ошибка загрузки: ${response.statusText}`);
      }
      return await response.text(); // Возвращаем HTML-контент
    } catch (error) {
      console.error('Ошибка:', error);
      return null;
    }
  }

  downloadFile(content, fileName) {
    // Создаем временный элемент <a> для скачивания файла
    const blob = new Blob([content], { type: 'text/html' }); // Создаем Blob с типом HTML
    const url = URL.createObjectURL(blob); // Создаем временную ссылку на Blob

    const anchor = document.createElement('a'); // Создаем элемент <a>
    anchor.href = url; // Устанавливаем URL для скачивания
    anchor.download = fileName; // Устанавливаем имя файла для загрузки
    document.body.appendChild(anchor); // Добавляем элемент в DOM
    anchor.click(); // Инициируем скачивание
    document.body.removeChild(anchor); // Удаляем элемент из DOM

    // Освобождаем память, удаляя временную ссылку на Blob
    URL.revokeObjectURL(url);
  }
}


