import { defineStore } from 'pinia';
import { ref } from 'vue';
import fetchWithCors from '../utils/fetchWithCors';
import Swal from 'sweetalert2';

const mainNavStore = defineStore('mainNavStore', () => {
  const navItems = ref([]);
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  const getNavItems = async (url: string) => {
    try {
      isLoading.value = true;
      error.value = null;
      const response = await fetchWithCors(url);

      if (response.success && response.data) {
        navItems.value = response.data;
      } else {
        throw new Error(response.error || 'Failed to load navigation items');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
      console.error('Ошибка загрузки навигации:', err);
    } finally {
      isLoading.value = false;
    }
  };

  const addNavItem = async (url: string, data: any) => {
    try {
      isLoading.value = true;
      error.value = null;

      const formData = new FormData();
      formData.append('title', data.title || '');
      formData.append('link', data.link || '');

      if (data.icon_path instanceof File) {
        formData.append('icon_path', data.icon_path);
      }

      const response = await fetchWithCors(url, {
        method: 'POST',
        body: formData,
      });

      if (response.success) {
        await Swal.fire({
          title: 'Успех!',
          text: 'Элемент навигации добавлен',
          icon: 'success',
        });
        // Перезагружаем данные
        await getNavItems(url);
      } else {
        throw new Error(response.error || 'Failed to add navigation item');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
      console.error('Ошибка добавления навигации:', err);
      await Swal.fire({
        title: 'Ошибка!',
        text: `Не удалось добавить элемент навигации: ${error.value}`,
        icon: 'error',
      });
    } finally {
      isLoading.value = false;
    }
  };

  const updateNavItem = async (url: string, id: number, data: any) => {
    try {
      isLoading.value = true;
      error.value = null;

      const formData = new FormData();
      formData.append('title', data.title || '');
      formData.append('link', data.link || '');

      if (data.icon_path instanceof File) {
        formData.append('icon_path', data.icon_path);
      }

      const response = await fetchWithCors(`${url}?id=${id}`, {
        method: 'POST',
        body: formData,
      });

      if (response.success) {
        await Swal.fire({
          title: 'Успех!',
          text: 'Элемент навигации обновлен',
          icon: 'success',
        });
        // Перезагружаем данные
        await getNavItems(url);
      } else {
        throw new Error(response.error || 'Failed to update navigation item');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
      console.error('Ошибка обновления навигации:', err);
      await Swal.fire({
        title: 'Ошибка!',
        text: `Не удалось обновить элемент навигации: ${error.value}`,
        icon: 'error',
      });
    } finally {
      isLoading.value = false;
    }
  };

  const deleteNavItem = async (url: string, id: number) => {
    try {
      isLoading.value = true;
      error.value = null;

      const response = await fetchWithCors(`${url}?id=${id}`, {
        method: 'DELETE',
      });

      if (response.success) {
        await Swal.fire({
          title: 'Успех!',
          text: 'Элемент навигации удален',
          icon: 'success',
        });
        getNavItems(url);
      } else {
        throw new Error(response.error || 'Failed to delete navigation item');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
      await Swal.fire({
        title: 'Ошибка!',
        text: 'Не удалось удалить элемент навигации',
        icon: 'error',
      });
    } finally {
      isLoading.value = false;
    }
  };

  return {
    navItems,
    getNavItems,
    addNavItem,
    updateNavItem,
    deleteNavItem,
    isLoading,
    error,
  };
});

export default mainNavStore;
