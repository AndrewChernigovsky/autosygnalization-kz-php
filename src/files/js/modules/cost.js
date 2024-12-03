const costForm = document.querySelector('#cost')
const steps = document.querySelector('.cost__steps p');
const line = document.querySelector('.cost__line');
const lineActive = document.querySelector('.cost__line--active');
const swiperCostSlides = document.querySelectorAll('.swiper-cost .swiper-slide');
const stepBack = document.querySelector('#step-back');

const inputRange = document.getElementById("inputRange");
const activeColor = "#ff7300";
const inactiveColor = "#4b4747";
const valueDisplay = document.getElementById('total-cost');

function updatePrice() {
  const priceValue = inputRange.value;
  valueDisplay.value = priceValue;
  const ratio = (priceValue - inputRange.min) / (inputRange.max - inputRange.min) * 100;
  inputRange.style.background = `linear-gradient(90deg, ${activeColor} ${ratio}%, ${inactiveColor} ${ratio}%)`;
}

function changeStep() {
  steps.textContent = `Шаг ${swiperCost.realIndex + 1}/${swiperCostSlides.length}`;
  changeLineProgress();
}

function changeLineProgress() {
  const lineWidth = line.offsetWidth;
  const totalSlides = swiperCostSlides.length;

  if (swiperCost.realIndex === totalSlides - 1) {
    lineActive.style.width = '100%';
  } else if (swiperCost.realIndex > 0) {
    lineActive.style.width = (lineWidth / totalSlides) * swiperCost.realIndex + 'px';
  } else {
    lineActive.style.width = '0px';
  }
}

function checkFormSlide() {
  const currentSlide = swiperCostSlides[swiperCost.realIndex];
  const inputs = currentSlide.querySelectorAll('input[type="radio"], input[type="checkbox"], input[type="range"]');

  inputs.forEach(input => {
    input.addEventListener('change', () => {
      if (input.type == 'range') {
        swiperCost.slideNext();
      }
      if (input.checked) {
        swiperCost.slideNext();
      }
    })
  })
}

export function initCost() {
  if (costForm) {
    const phone = document.querySelector("input[name='user-tel']");
    Inputmask({
      mask: '+7 (999) 999-99-99',
    }).mask(phone);
    inputRange.addEventListener('input', function () {
      valueDisplay.value = this.value;
      updatePrice();
    });
    changeStep();
    updatePrice();
    checkFormSlide();

    stepBack.addEventListener('click', () => {
      swiperCost.slidePrev();
    })

    swiperCost.on('slideChange', function () {
      changeStep();
      updatePrice();
      checkFormSlide()
    });
  }
}