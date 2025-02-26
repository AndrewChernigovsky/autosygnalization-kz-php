import Swiper from 'swiper';
import { Pagination, Autoplay, Zoom } from 'swiper/modules';

const worksSwiper = new Swiper('.swiper-works', {
    modules: [Pagination, Autoplay, Zoom],
    slidesPerView: 3,
    spaceBetween: 30,
    loop: true,
    zoom: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.works__swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        320: {
            slidesPerView: 1,
        },
        768: {
            slidesPerView: 2,
        },
        1024: {
            slidesPerView: 3,
        }
    }
});

document.querySelectorAll('.works__slide-image img').forEach(img => {
    img.addEventListener('click', function(e) {
        e.preventDefault();
        const modal = document.createElement('div');
        modal.className = 'modal-image';
        
        const modalImg = document.createElement('img');
        modalImg.src = this.src;
        
        modal.appendChild(modalImg);
        document.body.appendChild(modal);
        
        modal.addEventListener('click', function() {
            this.remove();
        });
    });
});

export default worksSwiper;
