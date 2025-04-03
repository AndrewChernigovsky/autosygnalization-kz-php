export function createPolicyModal(title, initialContent = 'Загрузка...') {
    const modalContainer = document.createElement('div');
    modalContainer.className = 'policy-modal policy-modal__overlay';
    modalContainer.innerHTML = `
      <div class="policy-modal__content">
        <button class="policy-modal__close">&times;</button>
        <h2>${title}</h2>
        <div class="policy-modal__body">${initialContent}</div>
      </div>
    `;
  
    const closeModal = () => {
      modalContainer.remove();
      document.removeEventListener('keydown', handleEscPress);
    };
  
    const handleEscPress = (e) => {
      if (e.key === 'Escape') {
        closeModal();
      }
    };

    modalContainer.querySelector('.policy-modal__close').addEventListener('click', closeModal);

    modalContainer.addEventListener('click', (e) => {
      if (e.target === modalContainer) {
        closeModal();
      }
    });

    document.addEventListener('keydown', handleEscPress);
  
    document.body.appendChild(modalContainer);
  
    return {
      setContent: (content) => {
        modalContainer.querySelector('.policy-modal__body').innerHTML = content;
      },
      close: closeModal
    };
  }
  
  export async function loadPolicyDocument(path) {
    try {
      const response = await fetch(path);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const text = await response.text();
      return text.trim() ? text.replace(/\n/g, '<br>') : 'Текст документа пуст';
    } catch (error) {
      console.error('Error loading document:', error);
      return 'Ошибка при загрузке документа. Пожалуйста, попробуйте позже.';
    }
  }