export default class BuyService {

  constructor(element) {
    this.modalForm = document.querySelector('.popup.modal-form');
    this.initBuy(element);
  }

  initBuy(element) {
    element.addEventListener('click', () => {
      this.modalForm.classList.toggle('active');
    })
  }
}