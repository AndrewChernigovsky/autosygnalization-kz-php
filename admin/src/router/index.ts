import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import SliderIntro from '../views/SliderIntro.vue';
import Navigation from '../views/Navigation.vue';
import Contacts from '../views/Contacts.vue';
import Products from '../views/Products.vue';
import Services from '../views/Services.vue';

export const routes = [
  {
    path: '/',
    name: 'Главная',
    component: Home,
  },
  {
    path: '/slider-intro',
    name: 'Слайдер',
    component: SliderIntro,
  },
  {
    path: '/navigation',
    name: 'Навигация',
    component: Navigation,
  },
  {
    path: '/contacts',
    name: 'Контакты',
    component: Contacts,
  },
  {
    path: '/products',
    name: 'Продукты',
    component: Products,
  },
  {
    path: '/services',
    name: 'Услуги',
    component: Services,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
