const phoneButton = document.querySelector('.phone-button');
const modalForm = document.querySelector('.modal-form');
const closeButton = document.querySelector('#modal-form-close');

export function initPhone() {
  if (phoneButton && modalForm) {
    phoneButton.addEventListener('click', () => {
      modalForm.classList.add('active');
    })

    closeButton.addEventListener('click', () => {
      modalForm.classList.remove('active');
    })
  }
}
