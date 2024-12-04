export const initSwiper = () => {
  const swiperIntro = document.querySelector('.swiper-intro');
  if (swiperIntro && typeof Swiper !== 'undefined') {
    const swiperIntro1 = new Swiper('.swiper-intro', {
      loop: true,
      modules: [Autoplay],
      autoplay: {
        delay: 4500,
        disableOnInteraction: false,
      },
      slidesPerView: '1',
    });
    window.swiperIntro = swiperIntro1;
  }
};