import { defineStore } from 'pinia';
import { ref } from 'vue';
import fetchWithCors from '../utils/fetchWithCors';

interface AvailablePage {
  title: string;
  link: string;
}

interface NavItem {
  id: number;
  title: string;
  content: string;
  link: string;
  icon_path: string | null;
  sort_order: number;
  on_page: boolean;
  created_at: string;
  updated_at: string;
}

const mainNavStore = defineStore('mainNavStore', () => {
  const API_BASE_URL = '/server/php/admin/api/navigation/navigation.php';
  const API_PAGE_URL = '/server/php/admin/api/pages/available_pages.php';

  const navItems = ref<NavItem[]>([]);
  const availablePages = ref<AvailablePage[]>([]);
  const isLoading = ref(false);
  const error = ref<string | null>(null);
  const isValid = ref(true);

  const newNavItem = ref({
    title: '',
    content: '',
    link: '',
    icon_path: null as File | null,
    sort_order: 0,
    on_page: true,
    isExternal: false,
  });

  const getNavItems = async () => {
    try {
      isLoading.value = true;
      error.value = null;
      const response = await fetchWithCors(API_BASE_URL);

      if (response.success && response.data) {
        navItems.value = response.data;
      } else {
        throw new Error(response.error || 'Failed to load navigation items');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
    } finally {
      isLoading.value = false;
    }
  };

  const getAvailablePages = async () => {
    try {
      isLoading.value = true;
      error.value = null;

      const response = await fetchWithCors(API_PAGE_URL);

      if (response.success && response.data) {
        availablePages.value = response.data;
      } else {
        throw new Error(response.error || 'Failed to load available pages');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
    } finally {
      isLoading.value = false;
    }
  };

  const resetNewNavItem = () => {
    newNavItem.value = {
      title: '',
      content: '',
      link: '',
      icon_path: null,
      sort_order: 0,
      on_page: true,
      isExternal: false,
    };
    isValid.value = true;
  };

  const updateNavItemsOrder = (orderData: { id: number; sort_order: number }[]) => {
    // Обновляем локальные данные на основе нового порядка
    orderData.forEach((item) => {
      const navItem = navItems.value.find((nav) => nav.id === item.id);
      if (navItem) {
        navItem.sort_order = item.sort_order;
      }
    });
    
    // Пересортировываем массив
    navItems.value.sort((a, b) => a.sort_order - b.sort_order);
  };

  return {
    navItems,
    availablePages,
    newNavItem,
    getNavItems,
    getAvailablePages,
    resetNewNavItem,
    updateNavItemsOrder,
    isLoading,
    error,
    isValid,
    API_BASE_URL,
  };
});

export default mainNavStore;
