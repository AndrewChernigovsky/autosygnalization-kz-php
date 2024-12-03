const toggleMenuButton = document.querySelector('#btn-open-menu');
const wrapper = document.querySelector('.header__intro');
const links = document.querySelectorAll('nav a');

export function toToggleMenu() {
  function toggleMenu() {

    wrapper.classList.toggle('active')
    toggleMenuButton.classList.toggle('active')
    document.querySelector('body').classList.toggle('overflow')

    if (wrapper.classList.contains('active')) {
      links.forEach(link => link.addEventListener('click', () => {
        wrapper.classList.remove('active');
        toggleMenuButton.classList.remove('active');
        document.querySelector('body').classList.remove('overflow')
      }))
    }
  }

  if (toggleMenuButton && wrapper) {
    toggleMenuButton.addEventListener('click', toggleMenu)
  }
  return;
}