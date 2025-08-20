import { defineStore } from 'pinia';
import { ref } from 'vue';
import fetchWithCors from '../utils/fetchWithCors';

interface INewContact {
  type: string;
  title: string;
  content: string;
  icon_path?: File | null;
  link: string;
  sort_order: number | null;
  on_page: boolean;
}

interface IContact {
  contact_id: number;
  type: string;
  title: string;
  content: string;
  icon_path: string;
  link: string;
  sort_order: number;
  on_page: boolean;
}

const contactsStore = defineStore('contactsStore', () => {
  const contactsApiUrl = ref('/server/php/admin/api/contacts/contact.php');
  const contacts = ref<IContact[]>([]);
  const newContact = ref<INewContact>({
    type: '',
    title: '',
    content: '',
    icon_path: null,
    link: '',
    sort_order: null,
    on_page: false,
  });
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  const isValid = ref(true);

  const contactsTypes = ref<string[]>([
    'Основной телефон',
    'Адрес',
    'Контактный телефон',
    'Социальные сети',
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
        contacts.value = response.data.map((contact: any) => ({
          ...contact,
          on_page: !!parseInt(String(contact.on_page), 10),
        }));
      } else {
        throw new Error(response.error || 'Failed to load contacts');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
    } finally {
      isLoading.value = false;
    }
  };

  const resetNewContact = () => {
    newContact.value = {
      type: '',
      title: '',
      content: '',
      icon_path: null,
      link: '',
      sort_order: 0,
      on_page: false,
    };
  };

  const updateContactsOrder = (
    updateData: Array<{ contact_id: number; sort_order: number }>
  ) => {
    const updatedContacts = contacts.value.map((contact) => {
      const updateItem = updateData.find(
        (item) => item.contact_id === contact.contact_id
      );
      if (updateItem) {
        return { ...contact, sort_order: updateItem.sort_order };
      }
      return contact;
    });

    // Заменяем весь массив для гарантии реактивности
    contacts.value = updatedContacts;
  };

  return {
    contacts,
    contactsTypes,
    newContact,
    contactsApiUrl,
    isLoading,
    error,
    isValid,
    getContacts,
    resetNewContact,
    updateContactsOrder,
  };
});

export default contactsStore;
