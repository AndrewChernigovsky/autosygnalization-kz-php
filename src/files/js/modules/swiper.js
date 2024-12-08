export const initSwiper = () => {
  const swiperIntro = document.querySelector('.swiper-intro');
  if (swiperIntro && typeof Swiper !== 'undefined') {
    const swiperIntro1 = new Swiper('.swiper-intro', {
      loop: true,
      modules: [Autoplay, EffectFade],
      effect: 'fade',
      speed: 1000,
      autoplay: {
        delay: 4500,
        disableOnInteraction: false,
      },
      fadeEffect: {
        crossFade: true
      },
      slidesPerView: '1',
    });
    window.swiperIntro = swiperIntro1;
  }
};