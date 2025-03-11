import Swiper from 'swiper';
import { Pagination, Autoplay, Zoom } from 'swiper/modules';

const worksSwiper = new Swiper('.swiper-works', {
    modules: [Pagination, Autoplay, Zoom],
    spaceBetween: 10,
    slidesPerView: 1.5,
    // spaceBetween: 20,
    // slidesPerView: 3,
    centeredSlides: true,
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
        // 320: {
        //     slidesPerView: 1,
        // },
        768: {
            slidesPerView: 2,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 30,
            centeredSlides: true,
        }
    },
    // on: {
    //     slideChange: function () {

    //         this.slides.forEach(slide => {
    //             slide.classList.remove('active');
    //         });
    //         // центральный слайд
    //         const centerIndex = this.activeIndex + Math.floor(this.params.slidesPerView / 2) - (this.params.slidesPerView % 2 === 0 ? 1 : 0);
    //         this.slides[centerIndex].classList.add('active');
    //     },
    // },
    on: {
        slideChange: function () {
            this.slides.forEach(slide => {
                slide.classList.remove('active');
            });
            // Определяем центральный слайд
            const centerIndex = this.activeIndex % this.slides.length; // Используем модуль для корректного индекса
            this.slides[centerIndex].classList.add('active');
        },
    },
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
