const toggles = document.querySelectorAll('.toggle-list');

export function toggleList() {
  toggles.forEach(toggle => {
    toggle.addEventListener('click', function () {
      const submenu = this.nextElementSibling;

      function visibleContent(submenu) {
        toggle.classList.toggle('active')
      }

      if (submenu && submenu.classList.contains('footer__menu-list')) {
        visibleContent(submenu);
      }
    });
  });
}
