const phoneButton = document.querySelector('.phone-button');
const phoneButtonWrapper = document.querySelector('.phone-button__wrapper');
const modalFormMob = document.querySelector('.popup.phone-popup');
const modalForm = document.querySelector('.popup.modal-form');
const closeButton = document.querySelector('#modal-form-close');

export function initPhone() {
  if (phoneButton && modalFormMob && modalForm) {
    const getWindowWidth = () => window.innerWidth;
    phoneButton.addEventListener('click', () => {
      const width = getWindowWidth();
      if (width > 768) {
        modalForm.classList.toggle('active');
      } else {
        modalFormMob.classList.toggle('active');
      }
      phoneButtonWrapper.classList.toggle('active');
      phoneButton.classList.toggle('animated-calling')
    })

    closeButton.addEventListener('click', () => {
      modalForm.classList.remove('active');
      phoneButtonWrapper.classList.remove('active');
      phoneButton.classList.add('animated-calling')
    })
  }
}
