import { defineStore } from 'pinia';
import { ref } from 'vue';
import fetchWithCors from '../utils/fetchWithCors';
import Swal from 'sweetalert2';

const contactsStore = defineStore('contactsStore', () => {
  const contacts = ref([]);
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  const getContacts = async (url: string) => {
    try {
      isLoading.value = true;
      error.value = null;
      const response = await fetchWithCors(url);

      if (response.success && response.data) {
        contacts.value = response.data;
      } else {
        throw new Error(response.error || 'Failed to load contacts');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
      await Swal.fire({
        title: 'Ошибка!',
        text: 'Не удалось загрузить контакты',
        icon: 'error',
      });
    } finally {
      isLoading.value = false;
    }
  };

  return { contacts, getContacts, isLoading, error };
});

export default contactsStore;
