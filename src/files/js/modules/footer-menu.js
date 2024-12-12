const toggles = document.querySelectorAll('.toggle-list');

export function toggleList() {
  toggles.forEach(toggle => {
    toggle.addEventListener('click', function () {
      const submenu = this.nextElementSibling;

      if (submenu && submenu.classList.contains('footer__submenu-list') || submenu && submenu.classList.contains('footer__menu-title')) {
        if (submenu.style.display === 'block') {
          submenu.style.display = 'none';
        } else {
          submenu.style.display = 'block';
        }
      }
    });
  });
}
