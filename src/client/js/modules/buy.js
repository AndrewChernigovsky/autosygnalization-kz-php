export default class BuyService {

  constructor(element) {
    this.modalForm = document.querySelector('.popup.modal-form');
    this.closeBtn = document.querySelector('#modal-form-close');
    this.initBuy(element);
  }

  initBuy(element) {
    element.addEventListener('click', () => {
      this.modalForm.classList.toggle('active');
    })
    this.closeBtn.addEventListener('click', () => {
      this.modalForm.classList.remove('active');
    })
  }
}