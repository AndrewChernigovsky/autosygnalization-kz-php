import Swiper from 'swiper';
import { Autoplay, Pagination, EffectFade, EffectCards } from 'swiper/modules';
import Inputmask from 'inputmask';
import { Fancybox } from "@fancyapps/ui";

const libraries = {
  Swiper: Swiper,
  Autoplay: Autoplay,
  Pagination: Pagination,
  EffectFade: EffectFade,
  EffectCards: EffectCards,
  Inputmask: Inputmask,
  Fancybox: Fancybox
};

for (const [key, value] of Object.entries(libraries)) {
  window[key] = value;
}

async function loadLibs() {
  await import('swiper');
  await import('inputmask');
  await import('@fancyapps/ui');
}

loadLibs();