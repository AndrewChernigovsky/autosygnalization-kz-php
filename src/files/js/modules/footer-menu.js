const toggles = document.querySelectorAll('.toggle-list');
const footer = document.querySelector('footer');
let viewPort;

if (footer) {
  window.addEventListener('resize', () => {
    viewPort = window.innerWidth;
    console.log(viewPort);
  })
}

export function toggleList() {
  toggles.forEach(toggle => {
    toggle.addEventListener('click', function () {

      if (viewPort > 768) {
        return;
      }

      const submenu = this.nextElementSibling;

      function visibleContent() {
        toggle.classList.toggle('active');
      }

      if (submenu && submenu.classList.contains('footer__menu-list')) {
        visibleContent();
      }
    });
  });
}
