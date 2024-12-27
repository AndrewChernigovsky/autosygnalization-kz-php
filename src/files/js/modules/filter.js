export function filterToggleMenu() {
    const fliterBtn = document.getElementById('filter-btn');
    const fliterCatalog = document.getElementById('filter-catalog');

    fliterBtn.addEventListener('click',() => {
        fliterCatalog.classList.toggle('open');
    });
};