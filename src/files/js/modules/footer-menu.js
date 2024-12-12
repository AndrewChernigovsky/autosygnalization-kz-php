const toggles = document.querySelectorAll('.toggle-list');

export function toggleList() {
  toggles.forEach(toggle => {
    toggle.addEventListener('click', function () {
      const submenu = this.nextElementSibling;

      function visibleContent(submenu) {
        if (submenu.style.display === 'block') {
          submenu.style.display = 'none';
          toggle.classList.add('active')
        } else {
          submenu.style.display = 'block';
          toggle.classList.remove('active')
        }
      }

      if (submenu && submenu.classList.contains('footer__menu-list')) {
        visibleContent(submenu);
      }
    });
  });
}
