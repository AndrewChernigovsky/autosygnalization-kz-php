export default class BuyService {

  constructor(element) {
    this.modalForm = document.querySelector('.popup.modal-form');
    this.closeBtn = document.querySelector('#modal-form-close');
    this.initBuy(element);
    this.phoneButton = document.querySelector('.phone-button');
    this.phoneButtonWrapper = document.querySelector('.phone-button__wrapper');
  }

  initBuy(element) {
    element.addEventListener('click', () => {
      this.modalForm.classList.toggle('active');
      this.phoneButton.classList.add('animated-calling')
      this.phoneButtonWrapper.classList.add('active')
    })
    this.closeBtn.addEventListener('click', () => {
      this.modalForm.classList.remove('active');
      this.phoneButton.classList.remove('animated-calling')
      this.phoneButtonWrapper.classList.remove('active')
    })
  }
}