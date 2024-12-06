const toggleMenuButton = document.querySelector('#btn-open-menu');
const menuButton = document.querySelector('.menu-toggle');
const btns = document.querySelector('.menu-btns');
const geo = document.querySelector('.geo');
const wrapper = document.querySelector('.nav');

export function toToggleMenu() {
  function toggleMenu() {

    wrapper.classList.toggle('active')
    toggleMenuButton.classList.toggle('active')
    menuButton.classList.toggle('active')
    btns.classList.toggle('active')
    geo.classList.toggle('active')
    document.querySelector('body').classList.toggle('overflow')

    if (wrapper.classList.contains('active')) {
      links.forEach(link => link.addEventListener('click', () => {
        wrapper.classList.remove('active');
        toggleMenuButton.classList.remove('active');
        menuButton.classList.remove('active')
        btns.classList.remove('active')
        geo.classList.remove('active')
        document.querySelector('body').classList.remove('overflow')
      }))
    }
  }

  if (toggleMenuButton && wrapper) {
    toggleMenuButton.addEventListener('click', toggleMenu)
  }
  return;
}