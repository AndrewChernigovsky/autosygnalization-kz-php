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
  icon_path: string | null;
  link: string | null;
  content: string | null;
}

const contacts = ref<ContactItem[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);
const newContact = ref<Partial<ContactItem>>({
  title: 'Адреc:',
  content: '',
});

const API_BASE_URL = '/server/php/admin/api/contacts/contact.php';

const getContacts = async (): Promise<void> => {
  try {
    isLoading.value = true;
    error.value = null;

    const { success, data } = await fetchWithCors(API_BASE_URL);

    if (success && data) {
      console.log(data, 'data');
      contacts.value = data.filter(
        (item: ContactItem) => item.type === 'address'
      );
    } else {
      throw new Error('Failed to load contacts');
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

    const { success } = await fetchWithCors(`${API_BASE_URL}?id=${id}`, {
      method: 'PUT',
      body: JSON.stringify({
        title: 'Адреc:',
        content: item.content,
        link: null,
        type: 'address',
        icon_path: null,
      }),
    });

    if (success) {
      await Swal.fire({
        title: 'Успешно!',
        text: 'Контакты обновлены',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true,
      });
    } else {
      throw new Error('Ошибка обновления');
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

    const { success } = await fetchWithCors(`${API_BASE_URL}?id=${id}`, {
      method: 'DELETE',
    });

    if (success) {
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
      throw new Error('Ошибка удаления');
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
    const { success, error: apiError } = await fetchWithCors(API_BASE_URL, {
      method: 'POST',
      body: JSON.stringify({
        title: 'Адреc:',
        content: newContact.value.content,
        link: null,
        type: 'address',
        icon_path: newContact.value.icon_path,
      }),
    });
    if (success) {
      await Swal.fire({
        title: 'Успешно!',
        text: 'Ссылка добавлена',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true,
      });
      newContact.value = {
        title: 'Адреc:',
        content: '',
        link: null,
      };
      await getContacts();
    } else {
      throw new Error(apiError || 'Ошибка создания');
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
    <h2 class="contacts-title">Адреса</h2>

    <div class="add-contact-wrapper">
      <h2 class="add-contact-title">Добавить новый адрес</h2>
      <div class="add-contact-form">
        <div class="input-group">
          <label :for="'phone-create'">Адрес:</label>
          <input
            type="text"
            :id="'phone-create'"
            v-model="newContact.content"
          />
        </div>
        <button class="btn save add" @click="createContact">Добавить</button>
      </div>
    </div>

    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner"></div>
    </div>

    <div v-else-if="error" class="error-message">
      {{ error }}
    </div>

    <div>
      <div class="contact-list">
        <div v-for="item in contacts" :key="item.contact_id" class="nav-item">
          <div class="input-group">
            <label :for="'content-' + item.content?.replace(/[+\s]/g, '')"
              >Адрес:</label
            >
            <input
              type="text"
              :id="'content-' + item.content?.replace(/[+\s]/g, '')"
              v-model="item.content"
            />
          </div>
          <div class="button-group">
            <button class="btn save" @click="updateContact(item.contact_id)">
              Сохранить
            </button>
            <button class="btn delete" @click="deleteContact(item.contact_id)">
              Удалить
            </button>
          </div>
        </div>
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
}

.add-contact-form .input-group {
  width: 100%;
  max-width: 500px;
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

.add-nav-form {
  background: #f9f9f9;
  border-radius: 8px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  max-width: 500px;
}

.add-nav-form-title {
  margin-bottom: 1rem;
  color: #2c3e50;
}

.navigation-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.nav-item {
  display: flex;
  gap: 1rem;
  align-items: end;
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
  width: 100%;
}

.input-group label {
  font-size: 0.875rem;
  color: #666;
  margin-bottom: 0.5rem;
}

.input-group input,
.add-nav-form select {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.input-group input:focus,
.add-nav-form select:focus {
  border-color: #42b883;
  outline: none;
  box-shadow: 0 0 0 2px rgba(66, 184, 131, 0.2);
}

.button-group {
  display: flex;
  gap: 0.75rem;
  margin-top: 1rem;
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
</style>
