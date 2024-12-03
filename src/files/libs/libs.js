import Swiper from 'swiper';
import { Autoplay, Pagination } from 'swiper/modules';
import Inputmask from 'inputmask';

const libraries = {
  Swiper: Swiper,
  Autoplay: Autoplay,
  Pagination: Pagination,
  Inputmask: Inputmask
}

for (const [key, value] of Object.entries(libraries)) {
  window[key] = value;
}

async function loadLibs() {
  const { Swiper, Autoplay, Pagination } = await import('swiper')
  const { Inputmask } = await import('inputmask')
}

loadLibs();