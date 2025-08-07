import { defineStore } from 'pinia';
import { ref } from 'vue';
import fetchWithCors from '../utils/fetchWithCors';

const contactsStore = defineStore('contactsStore', () => {
  const contactsApiUrl = ref('/server/php/admin/api/contacts/contact.php');
  const contacts = ref([]);
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  const contactsTypes = ref<string[]>([
    'Основной телефон',
    'Адрес',
    'Контактный телефон',
    'Соц. сети',
    'Электронная почта',
    'Расписание',
    'Карта',
    'Как к нам добраться',
    'Сайт',
    'Мессенджер',
  ]);

  const getContacts = async () => {
    try {
      isLoading.value = true;
      error.value = null;
      const response = await fetchWithCors(contactsApiUrl.value);

      if (response.success && response.data) {
        contacts.value = response.data;
      } else {
        throw new Error(response.error || 'Failed to load contacts');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
    } finally {
      isLoading.value = false;
    }
  };

  getContacts();

  return {
    contacts,
    contactsTypes,
    contactsApiUrl,
    isLoading,
    error,
    getContacts,
  };
});

export default contactsStore;
