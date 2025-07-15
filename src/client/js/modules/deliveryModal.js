export function initDeliveryModal() {
  const modal = document.getElementById("deliveryModal");
  const links = document.querySelectorAll(".footer__menu-list li a"); // сразу выбираем ссылки
  const span = document.getElementById("modalClose");

  // Перебираем все ссылки
  links.forEach(link => {
    // Проверяем текст внутри ссылки (без обертки span или другого элемента)
    const textContent = link.textContent.trim();

    if (textContent === 'Оплата и доставка') {
      link.addEventListener('click', function (event) {
        event.preventDefault(); // Предотвращаем переход
        modal.style.display = "block"; // Показываем модальное окно
      });
    }
  });

  function closeModal() {
    modal.style.display = "none";
  }

  // Закрытие по крестику
  if (span) {
    span.addEventListener('click', closeModal);
  }

  // Закрытие по клику вне окна
  window.addEventListener('click', function (event) {
    if (event.target === modal) {
      closeModal();
    }
  });

  // Закрытие по ESC
  window.addEventListener('keydown', function (event) {
    if (event.key === "Escape") {
      closeModal();
    }
  });
}