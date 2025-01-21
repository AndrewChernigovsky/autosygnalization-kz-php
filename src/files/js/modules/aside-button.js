const buttonClose = document.getElementById('aside-button');
const buttonOpen = document.getElementById('aside-open');
const asideOffers = document.querySelector('.aside__offers');

export  function asideButtons() {
    buttonClose.addEventListener('click', function() {
        asideOffers.style.display = 'none';
    });

    buttonOpen.addEventListener('click', function () {
      asideOffers.style.display = 'flex';
    });
};
