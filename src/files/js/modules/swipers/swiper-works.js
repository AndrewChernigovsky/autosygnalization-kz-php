import Swiper from 'swiper';
import { Pagination, Autoplay } from 'swiper/modules';

const worksSwiper = new Swiper('.swiper-works', {
    modules: [Pagination, Autoplay],
    slidesPerView: 3,
    spaceBetween: 30,
    loop: true,
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

export default worksSwiper;
