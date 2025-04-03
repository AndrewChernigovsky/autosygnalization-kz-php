const search = document.querySelector('.search');

export function initSearch() {
  if (search) {
    const input = search.querySelector('input');
    search.addEventListener('click', (e) => {
      e.stopPropagation();
      if (e.target !== input) {
        e.target.classList.toggle('active')
      } else {
        return
      }
    })
    document.addEventListener('click', () => {
      search.classList.remove('active');
    });
    
  }
}
