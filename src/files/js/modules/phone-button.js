const phoneButton = document.querySelector('.phone-button');
const phoneButtonWrapper = document.querySelector('.phone-button__wrapper');
const modalForm = document.querySelector('.modal-form');
const closeButton = document.querySelector('#modal-form-close');

export function initPhone() {
  if (phoneButton && modalForm) {
    phoneButton.addEventListener('click', () => {
      modalForm.classList.add('active');
      phoneButtonWrapper.classList.add('active');
      phoneButton.classList.remove('animated-calling')
    })

    closeButton.addEventListener('click', () => {
      modalForm.classList.remove('active');
      phoneButtonWrapper.classList.remove('active');
      phoneButton.classList.add('animated-calling')
    })
  }
}
