export const initSwiper = () => {
  const swiperIntro = document.querySelector('.swiper-intro');
  if (swiperIntro && typeof Swiper !== 'undefined') {
    const swiperIntro1 = new Swiper('.swiper-intro', {
      loop: true,
      modules: [Autoplay, EffectFade],
      autoplay: {
        delay: 4500,
        disableOnInteraction: false,
      },
      slidesPerView: '1',
      effect: 'fade',
    });
    window.swiperIntro = swiperIntro1;
  }
};