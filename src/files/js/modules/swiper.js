export function initSwiper() {
  const swiperAutosygnals = document.querySelector('.swiper-autosygnals');
  const swiperService = document.querySelector('.swiper-service');
  const swiperSertificates = document.querySelector('.swiper-sertificates');
  const swiperPopular = document.querySelector('.swiper-popular');
  const swiperPopularGallery = document.querySelectorAll(
    '.popular__item .swiper-popular-gallery'
  );
  const swiperIntro = document.querySelector('.swiper-intro');
  const swiperTechPhoto = document.querySelector('.swiper-tech-photo');
  const swiperReviews = document.querySelector('.swiper-reviews');
  const swiperArticle = document.querySelector('#swiper-article');

  async function loadModule() {
    if (swiperIntro != null) {
      const { initSwiperIntro } = await import('./swipers/swiper-intro.js');
      initSwiperIntro();
    }
    if (swiperAutosygnals != null) {
      const { initSwiperAutosygnal } = await import(
        './swipers/swiper-autosygnals.js'
      );
      initSwiperAutosygnal();
    }
    if (swiperService != null) {
      const { initSwiperService } = await import('./swipers/swiper-service.js');
      initSwiperService();
    }
    if (swiperSertificates != null) {
      const { initSwiperSertificates } = await import(
        './swipers/swiper-sertificates.js'
      );
      initSwiperSertificates();
    }
    if (swiperPopular != null && swiperPopularGallery.length > 0) {
      const { initSwiperPopular } = await import('./swipers/swiper-popular.js');
      initSwiperPopular();
    }
    if (swiperTechPhoto != null) {
      const { initSwiperTechPhoto } = await import(
        './swipers/swiper-tech-photo.js'
      );
      initSwiperTechPhoto();
    }
    if (swiperReviews != null) {
      const { initSwiperReviews } = await import('./swipers/swiper-reviews.js');
      initSwiperReviews();
    }
    if (swiperArticle != null) {
      const { initSwiperArticle } = await import('./swipers/swiper-article.js');
      initSwiperArticle();
    }
  }
  loadModule();
}
