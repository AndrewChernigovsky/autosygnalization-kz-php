import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import Home from './views/Home.vue';
import SliderIntro from './views/SliderIntro.vue';
import Navigation from './views/Navigation.vue';
import Contacts from './views/Contacts.vue';
import UI from './views/UI.vue';
import './style.css';

const app = createApp(App);
const pinia = createPinia();

// Создаем маршруты
const routes = [
  { path: '/', component: Home },
  { path: '/slider-intro', component: SliderIntro },
  { path: '/navigation', component: Navigation },
  { path: '/contacts', component: Contacts },
  { path: '/ui', component: UI },
];

// Создаем роутер
const router = createRouter({
  history: createWebHistory(),
  routes,
});

app.use(router);
app.use(pinia);
app.mount('#app');
