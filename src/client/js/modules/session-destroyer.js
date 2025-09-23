class SessionDestroyer {
  constructor() {
    this.destroyUrl = '/server/php/auth/sessions/destroy-session.php';
    this.init();
  }

  init() {
    // Событие закрытия браузера/вкладки (НЕ перехода на другую страницу)
    window.addEventListener('beforeunload', (event) => {
      // Проверяем, что это действительно закрытие, а не переход
      if (this.isRealClose()) {
        console.log('Real browser close detected');
        this.destroySession();
      }
    });
  }

  isRealClose() {
    // Если есть ссылка на другую страницу - это переход, НЕ закрытие
    if (document.activeElement && document.activeElement.tagName === 'A') {
      return false;
    }

    // Если есть форма в процессе отправки - это переход, НЕ закрытие
    if (document.querySelector('form[data-submitting="true"]')) {
      return false;
    }

    // В остальных случаях считаем что это закрытие браузера
    return true;
  }

  destroySession() {
    // Используем sendBeacon для надёжной отправки при закрытии
    if (navigator.sendBeacon) {
      navigator.sendBeacon(this.destroyUrl);
    } else {
      // Fallback для старых браузеров
      fetch(this.destroyUrl, {
        method: 'GET',
        keepalive: true,
      }).catch(() => {}); // Игнорируем ошибки
    }
  }
}

// Автоматически инициализируем при загрузке
document.addEventListener('DOMContentLoaded', () => {
  new SessionDestroyer();
});

export default SessionDestroyer;
