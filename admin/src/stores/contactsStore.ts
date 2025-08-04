import { defineStore } from 'pinia';
import { ref } from 'vue';
import fetchWithCors from '../utils/fetchWithCors';
import Swal from 'sweetalert2';

const contactsStore = defineStore('contactsStore', () => {
  const contactsUrl = ref('/server/php/admin/api/contacts/contact.php');
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

  const addContact = async (url: string, data: any) => {
    try {
      isLoading.value = true;
      error.value = null;

      const formData = new FormData();
      formData.append('title', data.title);
      formData.append('content', data.content);
      formData.append('link', data.link || '');
      formData.append('type', data.type);
      formData.append('on_page', data.on_page);
      formData.append('sort_order', data.sort_order);

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
          text: 'Контакт добавлен',
          icon: 'success',
        });
        getContacts(url);
      } else {
        throw new Error(response.error || 'Failed to add contact');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
      await Swal.fire({
        title: 'Ошибка!',
        text: 'Не удалось добавить контакт',
        icon: 'error',
      });
    } finally {
      isLoading.value = false;
    }
  };

  const updateContact = async (url: string, id: number, data: any) => {
    try {
      isLoading.value = true;
      error.value = null;

      const formData = new FormData();
      formData.append('title', data.title || '');
      formData.append('content', data.content || '');
      formData.append('link', data.link || '');
      formData.append('type', data.type || '');

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
          text: 'Контакт обновлен',
          icon: 'success',
        });
        getContacts(url);
      } else {
        throw new Error(response.error || 'Failed to update contact');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
      await Swal.fire({
        title: 'Ошибка!',
        text: 'Не удалось обновить контакт',
        icon: 'error',
      });
    } finally {
      isLoading.value = false;
    }
  };

  const deleteContact = async (url: string, id: number) => {
    try {
      isLoading.value = true;
      error.value = null;

      const response = await fetchWithCors(`${url}?id=${id}`, {
        method: 'DELETE',
      });

      if (response.success) {
        await Swal.fire({
          title: 'Успех!',
          text: 'Контакт удален',
          icon: 'success',
        });
        getContacts(url);
      } else {
        throw new Error(response.error || 'Failed to delete contact');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
      await Swal.fire({
        title: 'Ошибка!',
        text: 'Не удалось удалить контакт',
        icon: 'error',
      });
    } finally {
      isLoading.value = false;
    }
  };

  return {
    contacts,
    contactsTypes,
    contactsUrl,
    getContacts,
    addContact,
    updateContact,
    deleteContact,
    isLoading,
    error,
  };
});

export default contactsStore;
