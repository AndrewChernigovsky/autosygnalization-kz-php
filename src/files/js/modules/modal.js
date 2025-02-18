export function initModal() {
    // Получаем модальное окно
    const modal = document.getElementById("deliveryModal");
  
    // Получаем ссылку, которая открывает модальное окно
    const link = document.querySelector(".product-card__link");
  
    // Получаем элемент <span>, который закрывает модальное окно
    const span = document.getElementById("modalClose");
  
    // Функция для закрытия модального окна
    function closeModal() {
      modal.style.display = "none";
    }
  
    // Когда пользователь нажимает на ссылку, открываем модальное окно
    link.onclick = function(event) {
      event.preventDefault(); // Предотвращаем переход по ссылке
      modal.style.display = "block";
    }
  
    // Когда пользователь нажимает на <span> (x), закрываем модальное окно
    span.onclick = closeModal;
  
    // Когда пользователь нажимает в любом месте вне модального окна, закрываем его
    window.onclick = function(event) {
      if (event.target === modal) {
        closeModal();
      }
    }
  
    // Закрытие модального окна по нажатию клавиши ESC
    window.addEventListener('keydown', function(event) {
      if (event.key === "Escape") { // Проверяем, нажата ли клавиша ESC
        closeModal();
      }
    });
  }