import { defineStore } from 'pinia';
import { routes } from '../router';
import { ref } from 'vue';

export const useNavigationStore = defineStore('navigation', () => {
  const navigationRoutes = ref(routes);

  return {
    navigationRoutes,
  };
});
