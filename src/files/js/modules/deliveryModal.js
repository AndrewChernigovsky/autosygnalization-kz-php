export function initDeliveryModal() {
    const modal = document.getElementById("deliveryModal");
    const link = document.querySelector(".product-card__link");
    const span = document.getElementById("modalClose");
    function closeModal() {
      modal.style.display = "none";
    }
    link.onclick = function(event) {
      event.preventDefault(); 
      modal.style.display = "block";
    }
  
    span.onclick = closeModal;
  
    window.onclick = function(event) {
      if (event.target === modal) {
        closeModal();
      }
    }
  
    window.addEventListener('keydown', function(event) {
      if (event.key === "Escape") { 
        closeModal();
      }
    });
  }