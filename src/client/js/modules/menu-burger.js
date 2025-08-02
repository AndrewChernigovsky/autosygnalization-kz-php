const toggleMenuButton = document.querySelector('#btn-open-menu');
const menuButton = document.querySelector('.menu-toggle');
const btns = document.querySelector('.menu-btns');
const geo = document.querySelector('.geo');
const wrapper = document.querySelector('.nav');
const phones = document.querySelector('.phone');
const geoClass = document.querySelector('.menu-geo-phone');
const hiddenHeaderGeo = document.querySelector('.hidden-header-geo');
const geoAddress = document.querySelector('#geoAddress');

export function toToggleMenu() {
  let lastScroll = 0;

  function toggleMenu() {
    const links = document.querySelectorAll('.nav .link');

    wrapper.classList.toggle('active')
    toggleMenuButton.classList.toggle('active')
    menuButton.classList.toggle('active')
    btns.classList.toggle('active')
    geo.classList.toggle('active')
    phones.classList.toggle('active')
    geoClass.classList.toggle('menu-geo-phone');
    document.querySelector('body').classList.toggle('overflow')

    if (wrapper.classList.contains('active')) {
      links.forEach(link => link.addEventListener('click', () => {
        wrapper.classList.remove('active');
        toggleMenuButton.classList.remove('active');
        menuButton.classList.remove('active');
        btns.classList.remove('active');
        geo.classList.remove('active');
        phones.classList.remove('active');
        geoClass.classList.remove('menu-geo-phone');
        document.querySelector('body').classList.remove('overflow');
      }))
    }
  }

  function handleScroll() {
    const currentScroll = window.pageYOffset;

    if (currentScroll > lastScroll) {
      hiddenHeaderGeo.classList.add('hidden');
      geoAddress.classList.add('hidden');
    }
    lastScroll = currentScroll;
  }

  if (toggleMenuButton && wrapper) {
    toggleMenuButton.addEventListener('click', toggleMenu);
    window.addEventListener('scroll', handleScroll);
  }

  return;
}