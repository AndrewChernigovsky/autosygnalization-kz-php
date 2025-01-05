import { render } from 'preact';
import { html } from 'htm/preact';

export default class InitFormModal {
  constructor() {
    this.modalFormMob = document.querySelector('.popup');
    this.modalForm = document.querySelector('.popup.modal-form');
    this.phoneButton = document.querySelector('.phone-button');
    this.phoneButtonWrapper = document.querySelector('.phone-button__wrapper');
    this.closeButton = document.querySelector('#modal-form-close');

    // Привязываем методы к контексту
    this.handlePhoneButtonClick = this.handlePhoneButtonClick.bind(this);
    this.handleCloseButtonClick = this.handleCloseButtonClick.bind(this);
  }

  async initPhone() {
    if (this.phoneButton && this.modalForm && this.modalFormMob) {
      const { ModalForm } = await import("./../components/ModalForm.jsx");

      // Передаем метод закрытия в компонент
      render(html`<${ModalForm} onClose=${this.closeModal.bind(this)} />`, document.querySelector('.popup-body'));

      this.phoneButton.addEventListener('click', this.handlePhoneButtonClick);
      this.closeButton.addEventListener('click', this.handleCloseButtonClick);
    }
  }

  handlePhoneButtonClick() {
    const width = window.innerWidth;
    if (width > 768) {
      this.modalForm.classList.toggle('active');
    } else {
      this.modalFormMob.classList.toggle('active');
    }
    this.phoneButtonWrapper.classList.toggle('active');
    this.phoneButton.classList.toggle('animated-calling');
  }

  handleCloseButtonClick() {
    this.closeModal();
  }

  closeModal() {
    this.modalForm.classList.remove('active');
    this.modalFormMob.classList.remove('active');
    this.phoneButtonWrapper.classList.remove('active');
    this.phoneButton.classList.remove('animated-calling');
  }
}
