<script setup lang="ts">
import { ref, onMounted } from 'vue';
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

// Интерфейс для нового контакта
interface NewContact {
  title: string;
  content: string;
  icon_path: File | null;
  icon_path_url: string | null;
}

const contacts = ref<ContactItem[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);

// Типизированный объект для нового контакта
const newContact = ref<NewContact>({
  title: '',
  content: '',
  icon_path: null,
  icon_path_url: null,
});

const newIconFile = ref<File | null>(null);

const API_BASE_URL = '/server/php/admin/api/contacts/contact.php';

const getContacts = async (): Promise<void> => {
  try {
    isLoading.value = true;
    error.value = null;

    const response = await fetchWithCors(API_BASE_URL);

    if (response.success && response.data) {
      contacts.value = response.data.filter(
        (item: ContactItem) => item.type === 'main-phone'
      );
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

const handleFileChange = (event: Event, id: number | 'new') => {
  const target = event.target as HTMLInputElement;
  console.log(target.files, 'target.files');

  // Проверяем существование файлов
  if (target.files && target.files[0]) {
    const file = target.files[0];

    if (id === 'new') {
      newContact.value.icon_path = file;
      newContact.value.icon_path_url = URL.createObjectURL(file);
    } else {
      const contact = contacts.value.find((c) => c.contact_id === id);
      if (contact) {
        // Для существующих контактов можно хранить файл временно, если нужно
        // Например, в отдельном ref-объекте, сопоставленном с ID
      }
    }
  }
};

const updateContact = async (id: number): Promise<void> => {
  try {
    const item = contacts.value.find((n) => n.contact_id === id);
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
    formData.append('type', 'main-phone');
    formData.append('title', item.title);
    formData.append('content', item.content || '');
    formData.append('link', `tel:${item.content?.replace(/\s+/g, '').trim()}`);

    const fileInput = document.getElementById(`icon-${id}`) as HTMLInputElement;
    if (fileInput && fileInput.files && fileInput.files[0]) {
      formData.append('icon_path', fileInput.files[0]);
    }

    const response = await fetchWithCors(`${API_BASE_URL}?id=${id}`, {
      method: 'POST', // Используем POST для обновления с файлом
      body: formData,
    });

    if (response.success) {
      await Swal.fire({
        title: 'Успешно!',
        text: 'Контакты обновлены',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true,
      });
      await getContacts(); // Обновляем список
    } else {
      throw new Error(response.error || 'Ошибка обновления');
    }
  } catch (err) {
    console.error('Error updating contacts:', err);
    await Swal.fire({
      title: 'Ошибка!',
      text: 'Не удалось обновить контакты',
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
      contacts.value = contacts.value.filter((item) => item.contact_id !== id);
      await Swal.fire({
        title: 'Удалено!',
        text: 'Элемент контактов был успешно удален',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true,
      });
    } else {
      throw new Error(response.error || 'Ошибка удаления');
    }
  } catch (err) {
    console.log(err);
    console.error('Error deleting contacts:', err);
    await Swal.fire({
      title: 'Ошибка!',
      text: 'Не удалось удалить элемент контактов',
      icon: 'error',
    });
  }
};

const createContact = async (): Promise<void> => {
  try {
    if (!newContact.value.title || !newContact.value.content) {
      await Swal.fire({
        title: 'Ошибка!',
        text: 'Все поля обязательны',
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
    formData.append(
      'link',
      `${newContact.value.content.replace(/\s+/g, '').trim()}`
    );
    formData.append('type', 'main-phone');

    // Безопасная проверка типа File
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
        text: 'Ссылка добавлена',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true,
      });

      // Сброс с правильными типами
      newContact.value = {
        title: '',
        content: '',
        icon_path: null,
        icon_path_url: null,
      };
      newIconFile.value = null;
      const fileInput = document.getElementById('icon-new') as HTMLInputElement;
      if (fileInput) fileInput.value = '';

      await getContacts();
    } else {
      throw new Error(response.error || 'Ошибка создания');
    }
  } catch (err) {
    console.error(err);
    await Swal.fire({
      title: 'Ошибка!',
      text: err instanceof Error ? err.message : 'Не удалось добавить ссылку',
      icon: 'error',
    });
  }
};

onMounted(() => {
  getContacts();
});
</script>

<template>
  <div class="container">
    <h2 class="contacts-title">Основные контакты</h2>

    <div class="add-contact-wrapper">
      <h2 class="add-contact-title">Добавить новый контакт</h2>
      <form @submit.prevent="createContact" class="add-contact-form">
        <div class="input-group">
          <label for="title-create">Заголовок:</label>
          <input type="text" id="title-create" v-model="newContact.title" />
        </div>
        <div class="input-group">
          <label for="phone-create">Телефон:</label>
          <input type="text" id="phone-create" v-model="newContact.content" />
        </div>
        <div class="input-group icon-input-group">
          <label for="icon-new">Добавить иконку</label>
          <input
            class="icon-input"
            type="file"
            id="icon-new"
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
          v-for="item in contacts"
          :key="item.contact_id"
          @submit.prevent="updateContact(item.contact_id)"
          class="nav-item"
        >
          <div class="input-group">
            <label :for="'title-' + item.contact_id">Заголовок:</label>
            <input
              :id="'title-' + item.contact_id"
              type="text"
              v-model="item.title"
            />
          </div>
          <div class="input-group">
            <label :for="'content-' + item.contact_id">Телефон:</label>
            <input
              type="text"
              :id="'content-' + item.contact_id"
              v-model="item.content"
            />
          </div>
          <div class="input-group">
            <label :for="'icon-' + item.contact_id">Иконка:</label>
            <div v-if="item.icon_path" class="icon-preview">
              <img
                :src="item.icon_path as string"
                alt="icon"
                width="24"
                height="24"
              />
            </div>
            <input
              type="file"
              :id="'icon-' + item.contact_id"
              accept="image/svg+xml"
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

.input-group input {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.input-group input:focus {
  border-color: #42b883;
  outline: none;
  box-shadow: 0 0 0 2px rgba(66, 184, 131, 0.2);
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

.icon-preview {
  margin-top: 5px;
}

.icon-input-group {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.5rem;

  & label {
    grid-column: 1 / 2;
    grid-row: 1 / 2;
  }

  & .icon-svg {
    grid-column: 2 / 3;
    grid-row: 1 / 2;
  }

  & input {
    grid-column: 1 / 3;
    grid-row: 2 / 3;
    color: #2c3e50;
  }
}
</style>
