<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../../utils/fetchWithCors';

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

interface ContactConfig {
  type: string;
  sectionTitle: string;
  addFormTitle: string;
  fields: {
    title?: {
      label: string;
      required: boolean;
      defaultValue?: string;
      readonly?: boolean;
    };
    content?: {
      label: string;
      required: boolean;
      inputType: 'text' | 'textarea' | 'email' | 'tel';
    };
    link?: {
      label: string;
      required: boolean;
    };
    icon?: {
      enabled: boolean;
      label: string;
    };
  };
  linkGeneration?: (item: ContactItem) => string;
}

interface Props {
  contacts: ContactItem[];
  config: ContactConfig;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  'contact-created': [contact: ContactItem];
  'contact-updated': [contact: ContactItem];
  'contact-deleted': [contactId: number];
}>();

// Фильтруем контакты по типу
const filteredContacts = computed(() =>
  props.contacts.filter((item) => item.type === props.config.type)
);

const isLoading = ref(false);
const error = ref<string | null>(null);

// Создаем новый контакт с дефолтными значениями
const createNewContact = () => ({
  title: props.config.fields.title?.defaultValue || '',
  content: '',
  link: '',
  icon_path: null as File | null,
  icon_path_url: null as string | null,
});

const newContact = ref(createNewContact());

// Добавляем ref для хранения превью файлов существующих контактов
const existingContactPreviews = ref<
  Record<number, { file: File; preview: string }>
>({});

const API_BASE_URL = '/server/php/admin/api/contacts/contact.php';

const handleFileChange = (event: Event, id: number | 'new') => {
  const target = event.target as HTMLInputElement;

  if (target.files && target.files[0]) {
    const file = target.files[0];

    if (id === 'new') {
      newContact.value.icon_path = file;
      newContact.value.icon_path_url = URL.createObjectURL(file);
    } else {
      const contact = filteredContacts.value.find((c) => c.contact_id === id);
      if (contact) {
        existingContactPreviews.value[id] = {
          file: file,
          preview: URL.createObjectURL(file),
        };
      }
    }
  }
};

const generateLink = (item: ContactItem): string => {
  if (props.config.linkGeneration) {
    return props.config.linkGeneration(item);
  }
  return item.link || '';
};

const updateContact = async (id: number): Promise<void> => {
  try {
    const item = filteredContacts.value.find((n) => n.contact_id === id);
    if (!item) return;

    const { isConfirmed } = await Swal.fire({
      title: 'Обновить элемент?',
      text: 'Вы уверены, что хотите сохранить изменения?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Да, обновить',
      cancelButtonText: 'Отмена',
    });

    if (!isConfirmed) return;

    Swal.fire({
      title: 'Обновление...',
      text: 'Пожалуйста, подождите',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => Swal.showLoading(),
    });

    const formData = new FormData();
    formData.append('type', props.config.type);
    formData.append('title', item.title);
    formData.append('content', item.content || '');
    formData.append('link', generateLink(item));

    // Проверяем, есть ли новый файл для этого контакта
    const contactPreview = existingContactPreviews.value[id];
    if (contactPreview) {
      formData.append('icon_path', contactPreview.file);
    }

    const response = await fetchWithCors(`${API_BASE_URL}?id=${id}`, {
      method: 'POST',
      body: formData,
    });

    if (response.success) {
      await Swal.fire({
        title: 'Успешно!',
        text: 'Контакт обновлен',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true,
      });

      // Очищаем превью после успешного обновления
      if (existingContactPreviews.value[id]) {
        URL.revokeObjectURL(existingContactPreviews.value[id].preview);
        delete existingContactPreviews.value[id];
      }

      emit('contact-updated', item);
    } else {
      throw new Error(response.error || 'Ошибка обновления');
    }
  } catch (err) {
    console.error('Error updating contact:', err);
    await Swal.fire({
      title: 'Ошибка!',
      text: 'Не удалось обновить контакт',
      icon: 'error',
    });
  }
};

const deleteContact = async (id: number): Promise<void> => {
  try {
    const { isConfirmed } = await Swal.fire({
      title: 'Вы уверены?',
      text: 'Это действие нельзя будет отменить!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Да, удалить!',
      cancelButtonText: 'Отмена',
    });

    if (!isConfirmed) return;

    const response = await fetchWithCors(`${API_BASE_URL}?id=${id}`, {
      method: 'DELETE',
    });

    if (response.success) {
      await Swal.fire({
        title: 'Удалено!',
        text: 'Контакт был успешно удален',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true,
      });

      emit('contact-deleted', id);
    } else {
      throw new Error(response.error || 'Ошибка удаления');
    }
  } catch (err) {
    console.error('Error deleting contact:', err);
    await Swal.fire({
      title: 'Ошибка!',
      text: 'Не удалось удалить контакт',
      icon: 'error',
    });
  }
};

const createContact = async (): Promise<void> => {
  try {
    // Валидация обязательных полей
    const requiredFields = [];
    if (props.config.fields.title?.required && !newContact.value.title) {
      requiredFields.push(props.config.fields.title.label);
    }
    if (props.config.fields.content?.required && !newContact.value.content) {
      requiredFields.push(props.config.fields.content.label);
    }
    if (props.config.fields.link?.required && !newContact.value.link) {
      requiredFields.push(props.config.fields.link.label);
    }

    if (requiredFields.length > 0) {
      await Swal.fire({
        title: 'Ошибка!',
        text: `Обязательные поля: ${requiredFields.join(', ')}`,
        icon: 'error',
      });
      return;
    }

    Swal.fire({
      title: 'Создание...',
      text: 'Пожалуйста, подождите',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => Swal.showLoading(),
    });

    const formData = new FormData();
    formData.append('title', newContact.value.title);
    formData.append('content', newContact.value.content);
    formData.append('link', newContact.value.link);
    formData.append('type', props.config.type);

    if (newContact.value.icon_path instanceof File) {
      formData.append('icon_path', newContact.value.icon_path);
    }

    const response = await fetchWithCors(API_BASE_URL, {
      method: 'POST',
      body: formData,
    });

    if (response.success) {
      await Swal.fire({
        title: 'Успешно!',
        text: 'Контакт добавлен',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true,
      });

      // Сброс формы
      newContact.value = createNewContact();
      const fileInput = document.getElementById(
        `icon-new-${props.config.type}`
      ) as HTMLInputElement;
      if (fileInput) fileInput.value = '';

      emit('contact-created', response.data);
    } else {
      throw new Error(response.error || 'Ошибка создания');
    }
  } catch (err) {
    console.error(err);
    await Swal.fire({
      title: 'Ошибка!',
      text: err instanceof Error ? err.message : 'Не удалось добавить контакт',
      icon: 'error',
    });
  }
};

// Следим за изменением конфигурации и обновляем новый контакт
watch(
  () => props.config,
  () => {
    newContact.value = createNewContact();
  },
  { immediate: true }
);
</script>

<template>
  <div class="container">
    <h2 class="contacts-title">{{ config.sectionTitle }}</h2>

    <div class="add-contact-wrapper">
      <h2 class="add-contact-title">{{ config.addFormTitle }}</h2>
      <form @submit.prevent="createContact" class="add-contact-form">
        <!-- Title Field -->
        <div v-if="config.fields.title" class="input-group">
          <label :for="`title-create-${config.type}`"
            >{{ config.fields.title.label }}:</label
          >
          <input
            :type="config.fields.title.readonly ? 'text' : 'text'"
            :id="`title-create-${config.type}`"
            v-model="newContact.title"
            :readonly="config.fields.title.readonly"
          />
        </div>

        <!-- Content Field -->
        <div v-if="config.fields.content" class="input-group">
          <label :for="`content-create-${config.type}`"
            >{{ config.fields.content.label }}:</label
          >
          <textarea
            v-if="config.fields.content.inputType === 'textarea'"
            :id="`content-create-${config.type}`"
            v-model="newContact.content"
          />
          <input
            v-else
            :type="config.fields.content.inputType"
            :id="`content-create-${config.type}`"
            v-model="newContact.content"
          />
        </div>

        <!-- Link Field -->
        <div v-if="config.fields.link" class="input-group">
          <label :for="`link-create-${config.type}`"
            >{{ config.fields.link.label }}:</label
          >
          <input
            type="text"
            :id="`link-create-${config.type}`"
            v-model="newContact.link"
          />
        </div>

        <!-- Icon Field -->
        <div
          v-if="config.fields.icon?.enabled"
          class="input-group icon-input-group"
        >
          <label :for="`icon-new-${config.type}`">{{
            config.fields.icon.label
          }}</label>
          <input
            class="icon-input"
            type="file"
            :id="`icon-new-${config.type}`"
            accept="image/svg+xml"
            @change="handleFileChange($event, 'new')"
          />
          <img
            v-if="newContact.icon_path_url"
            class="icon-svg"
            width="50"
            height="50"
            :src="newContact.icon_path_url"
          />
        </div>

        <button type="submit" class="btn save add">Добавить</button>
      </form>
    </div>

    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner"></div>
    </div>

    <div v-else-if="error" class="error-message">
      {{ error }}
    </div>

    <div v-else>
      <div class="contact-list">
        <form
          v-for="item in filteredContacts"
          :key="item.contact_id"
          @submit.prevent="updateContact(item.contact_id)"
          class="nav-item"
        >
          <!-- Title Field -->
          <div v-if="config.fields.title" class="input-group">
            <label :for="`title-${item.contact_id}`"
              >{{ config.fields.title.label }}:</label
            >
            <input
              :id="`title-${item.contact_id}`"
              type="text"
              v-model="item.title"
              :readonly="config.fields.title.readonly"
            />
          </div>

          <!-- Content Field -->
          <div v-if="config.fields.content" class="input-group">
            <label :for="`content-${item.contact_id}`"
              >{{ config.fields.content.label }}:</label
            >
            <textarea
              v-if="config.fields.content.inputType === 'textarea'"
              :id="`content-${item.contact_id}`"
              v-model="item.content"
            />
            <input
              v-else
              :type="config.fields.content.inputType"
              :id="`content-${item.contact_id}`"
              v-model="item.content"
            />
          </div>

          <!-- Link Field -->
          <div v-if="config.fields.link" class="input-group">
            <label :for="`link-${item.contact_id}`"
              >{{ config.fields.link.label }}:</label
            >
            <input
              type="text"
              :id="`link-${item.contact_id}`"
              v-model="item.link"
            />
          </div>

          <!-- Icon Field -->
          <div
            v-if="config.fields.icon?.enabled"
            class="input-group icon-input-group"
          >
            <label :for="`icon-${item.contact_id}`"
              >{{ config.fields.icon.label }}:</label
            >

            <div class="icons-container">
              <!-- Существующая иконка -->
              <div
                v-if="item.icon_path && typeof item.icon_path === 'string'"
                class="existing-icon"
              >
                <span class="icon-label">Текущая:</span>
                <img
                  :src="item.icon_path"
                  alt="current icon"
                  width="50"
                  height="50"
                />
              </div>

              <!-- Превью нового выбранного файла -->
              <div
                v-if="existingContactPreviews[item.contact_id]"
                class="new-icon"
              >
                <span class="icon-label">Новая:</span>
                <img
                  :src="existingContactPreviews[item.contact_id].preview"
                  alt="new icon preview"
                  width="50"
                  height="50"
                  class="new-icon-preview"
                />
              </div>
            </div>

            <input
              type="file"
              :id="`icon-${item.contact_id}`"
              accept="image/svg+xml"
              @change="handleFileChange($event, item.contact_id)"
            />
          </div>

          <div class="button-group">
            <button type="submit" class="btn save">Сохранить</button>
            <button
              type="button"
              class="btn delete"
              @click="deleteContact(item.contact_id)"
            >
              Удалить
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  grid-column: 2 / 3;
}

.contacts-title {
  font-size: 2rem;
  margin: 0.4rem;
}

.add-contact-wrapper {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
  gap: 1rem;
  background: white;
  padding: 1rem;
  border-radius: 8px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
}

.add-contact-form {
  display: flex;
  align-items: flex-end;
  gap: 1rem;
  flex-wrap: wrap;
}

.add-contact-form .input-group {
  width: 100%;
  max-width: 300px;
}

.add-contact-title {
  font-size: 1.5rem;
  color: #2c3e50;
  margin: 0;
  margin-bottom: 1rem;
}

.contact-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 100;
}

.spinner {
  width: 40px;
  height: 40px;
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
  padding: 1rem;
  background: #f8d7da;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.nav-item {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  align-items: flex-end;
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.nav-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.input-group {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.input-group label {
  font-size: 0.875rem;
  color: #666;
  margin-bottom: 0.5rem;
}

.input-group input,
.input-group textarea {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.input-group input:focus,
.input-group textarea:focus {
  border-color: #42b883;
  outline: none;
  box-shadow: 0 0 0 2px rgba(66, 184, 131, 0.2);
}

.input-group textarea {
  min-height: 80px;
  resize: vertical;
}

.button-group {
  display: flex;
  gap: 0.75rem;
  margin-top: 1rem;
  align-self: flex-end;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn.save {
  background: #42b883;
  color: white;
}

.btn.save:hover {
  background: #3aa876;
}

.btn.delete {
  background: #dc3545;
  color: white;
}

.btn.delete:hover {
  background: #c82333;
}

.btn.add {
  margin-left: auto;
}

.icons-container {
  display: flex;
  gap: 1rem;
  align-items: flex-start;
  margin: 0.5rem 0;
}

.existing-icon,
.new-icon {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
}

.icon-label {
  font-size: 0.75rem;
  color: #666;
  font-weight: 500;
}

.new-icon-preview {
  border: 2px solid #42b883;
  border-radius: 4px;
}

.icon-input-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.icon-input-group label {
  font-size: 0.875rem;
  color: #666;
  margin-bottom: 0.5rem;
}

.icon-input-group input {
  color: #2c3e50;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.icon-input-group input:focus {
  border-color: #42b883;
  outline: none;
  box-shadow: 0 0 0 2px rgba(66, 184, 131, 0.2);
}

.icon-svg {
  margin-top: 0.5rem;
}
</style>
