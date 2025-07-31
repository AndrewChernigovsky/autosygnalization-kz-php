<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../utils/fetchWithCors';
import ContactManager from '../components/Contacts/ContactManager.vue';

interface ContactItem {
  contact_id: number;
  created_at: string;
  updated_at: string;
  title: string;
  type: string;
  icon_path: File | string | null;
  icon_path_url: string | null;
  link: string | null;
  content: string | null;
}

const contacts = ref<ContactItem[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);

const API_BASE_URL = '/server/php/admin/api/contacts/contact.php';

// Конфигурации для разных типов контактов
const contactConfigs = [
  {
    type: 'main-phone',
    sectionTitle: 'Основные контакты',
    addFormTitle: 'Добавить новый контакт',
    fields: {
      title: { label: 'Заголовок', required: true },
      content: { label: 'Телефон', required: true, inputType: 'tel' as const },
      icon: { enabled: true, label: 'Добавить иконку' },
    },
    linkGeneration: (item: ContactItem) =>
      `tel:${item.content?.replace(/\s+/g, '').trim()}`,
  },
  {
    type: 'contact-phone',
    sectionTitle: 'Дополнительные контакты',
    addFormTitle: 'Добавить новый дополнительный контакт',
    fields: {
      title: { label: 'Заголовок', required: true },
      content: { label: 'Телефон', required: true, inputType: 'tel' as const },
      icon: { enabled: true, label: 'Добавить иконку' },
    },
    linkGeneration: (item: ContactItem) =>
      `tel:${item.content?.replace(/\s+/g, '').trim()}`,
  },
  {
    type: 'social',
    sectionTitle: 'Социальные сети',
    addFormTitle: 'Добавить новую социальную сеть',
    fields: {
      title: { label: 'Заголовок', required: true },
      content: {
        label: 'Телефон',
        required: false,
        inputType: 'text' as const,
      },
      link: { label: 'Ссылка', required: false },
      icon: { enabled: true, label: 'Добавить иконку' },
    },
    linkGeneration: (item: ContactItem) =>
      item.link ? item.link : item.content || '',
  },
  {
    type: 'address',
    sectionTitle: 'Адреса',
    addFormTitle: 'Добавить новый адрес',
    fields: {
      title: {
        label: 'Заголовок',
        required: true,
        defaultValue: 'Адреc:',
        readonly: true,
      },
      content: { label: 'Адрес', required: true, inputType: 'text' as const },
      icon: { enabled: true, label: 'Добавить иконку' },
    },
    linkGeneration: () => '',
  },
  {
    type: 'map',
    sectionTitle: 'Карта',
    addFormTitle: 'Добавить новую карту',
    fields: {
      title: {
        label: 'Заголовок',
        required: true,
        defaultValue: 'Карта',
        readonly: true,
      },
      link: { label: 'Ссылка на карту', required: true },
    },
  },
  {
    type: 'schedule',
    sectionTitle: 'График работы',
    addFormTitle: 'Добавить новый График работы',
    fields: {
      title: {
        label: 'Заголовок',
        required: true,
        defaultValue: 'График работы:',
        readonly: true,
      },
      content: {
        label: 'График работы',
        required: true,
        inputType: 'textarea' as const,
      },
      icon: { enabled: true, label: 'Добавить иконку' },
    },
    linkGeneration: () => '',
  },
  {
    type: 'email',
    sectionTitle: 'Почта',
    addFormTitle: 'Добавить новую почту',
    fields: {
      title: { label: 'Заголовок', required: true, defaultValue: 'Почта:' },
      content: { label: 'Email', required: true, inputType: 'email' as const },
      icon: { enabled: true, label: 'Добавить иконку' },
    },
    linkGeneration: (item: ContactItem) => `mailto:${item.content?.trim()}`,
  },
];

const getContacts = async (): Promise<void> => {
  try {
    isLoading.value = true;
    error.value = null;

    const response = await fetchWithCors(API_BASE_URL);

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

const handleContactCreated = async (contact: ContactItem) => {
  await getContacts(); // Перезагружаем все контакты
};

const handleContactUpdated = async (contact: ContactItem) => {
  await getContacts(); // Перезагружаем все контакты
};

const handleContactDeleted = (contactId: number) => {
  contacts.value = contacts.value.filter(
    (item) => item.contact_id !== contactId
  );
};

onMounted(() => {
  getContacts();
});
</script>

<template>
  <div class="container">
    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner"></div>
      <p>Загрузка контактов...</p>
    </div>

    <div v-else-if="error" class="error-message">
      {{ error }}
      <button @click="getContacts" class="retry-btn">Попробовать снова</button>
    </div>

    <div v-else>
      <ContactManager
        v-for="config in contactConfigs"
        :key="config.type"
        :contacts="contacts"
        :config="config"
        @contact-created="handleContactCreated"
        @contact-updated="handleContactUpdated"
        @contact-deleted="handleContactDeleted"
      />
    </div>
  </div>
</template>

<style scoped>
.container {
  grid-column: 2 / 3;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.9);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  gap: 1rem;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #42b883;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.error-message {
  color: #dc3545;
  padding: 2rem;
  background: #f8d7da;
  border-radius: 8px;
  margin: 2rem 0;
  text-align: center;
}

.retry-btn {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  background: #42b883;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s;
}

.retry-btn:hover {
  background: #3aa876;
}
</style>
