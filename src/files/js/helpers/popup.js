export function setPopup(msg = "Нажмите на 'Капча!', пройдите её", time = 3) {
  const popup = document.querySelector('.popup');
  if (popup) {
    popup.classList.add('active');
    popup.querySelector('h3').textContent = msg;
    setTimeout(() => popup.classList.remove('active'), time * 1000);
  }
  return;
}