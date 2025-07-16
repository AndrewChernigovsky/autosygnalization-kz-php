<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';

interface ContactItem {
  contact_id: number;
  created_at: string;
  updated_at: string;
  title: string;
  phone: string;
  link: string;
}
const contacts = ref<ContactItem[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);

const API_BASE_URL = '/server/php/admin/api/contact.php';

const fetchWithCors = async (url: string, options: RequestInit = {}) => {
  const response = await fetch(url, {
    ...options,
    headers: {
      'Content-Type': 'application/json',
      ...options.headers,
    },
    credentials: 'include',
  });

  if (!response.ok) {
    console.log(response.json());
    throw new Error(`HTTP error! status: ${response.status}`);
  }

  return response.json();
};

const getContacts = async (): Promise<void> => {
  try {
    isLoading.value = true;
    error.value = null;

    const { success, data } = await fetchWithCors(API_BASE_URL);

    if (success && data) {
      console.log(data);
      contacts.value = data;
      console.log(contacts.value);
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
        title: item.title,
        phone: item.phone,
        link: `tel:${item.phone}`,
      }),
    });

    if (success) {
      await Swal.fire({
        title: 'Успешно!',
        text: 'Навигация обновлена',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true,
      });
    } else {
      throw new Error('Ошибка обновления');
    }
  } catch (err) {
    console.error('Error updating navigation:', err);
    await Swal.fire({
      title: 'Ошибка!',
      text: 'Не удалось обновить навигацию',
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
    console.log();
    if (!isConfirmed) return;

    const { success } = await fetchWithCors(`${API_BASE_URL}?id=${id}`, {
      method: 'DELETE',
    });

    if (success) {
      contacts.value = contacts.value.filter((item) => item.contact_id !== id);
      await Swal.fire({
        title: 'Удалено!',
        text: 'Элемент навигации был успешно удален',
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
    console.error('Error deleting navigation:', err);
    await Swal.fire({
      title: 'Ошибка!',
      text: 'Не удалось удалить элемент навигации',
      icon: 'error',
    });
  }
};

// --- Добавление новой ссылки ---
const newContact = ref<Partial<ContactItem>>({
  title: '',
  phone: '',
});

const createContact = async (): Promise<void> => {
  try {
    if (!newContact.value.title || !newContact.value.phone) {
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
        title: newContact.value.title,
        phone: newContact.value.phone,
        link: `tel:${newContact.value.phone}`,
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
        title: '',
        phone: '',
        link: '',
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
    <h2 class="title">Контакты</h2>

    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner"></div>
    </div>

    <div v-else-if="error" class="error-message">
      {{ error }}
    </div>

    <div>
      <div class="add-nav-form">
        <h2 class="add-nav-form-title">Добавить новую ссылку</h2>
        <div class="input-group">
          <label :for="'title-create'">Заголовок:</label>
          <input type="text" :id="'title-create'" v-model="newContact.title" />
        </div>
        <div class="input-group">
          <label :for="'phone-create'">Телефон:</label>
          <input type="text" :id="'phone-create'" v-model="newContact.phone" />
        </div>
        <button class="btn save" @click="createContact">Добавить</button>
      </div>

      <div class="navigation-grid">
        <div v-for="item in contacts" :key="item.contact_id" class="nav-item">
          <div class="input-group">
            <label :for="'title-' + item.contact_id">Заголовок:</label>
            <input
              :id="'title-' + item.contact_id"
              type="text"
              v-model="item.title"
            />
          </div>
          <div class="input-group">
            <label :for="'phone-' + item.phone">Телефон:</label>
            <input
              type="text"
              :id="'phone-' + item.phone"
              v-model="item.phone"
            />
          </div>
          <div class="button-group">
            <button class="btn save" @click="updateContact(item.contact_id)">
              Сохранить
            </button>
            <button
              class="btn delete"
              @click="deleteContact(item.contact_id)"
            >
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
.add-nav-form .input-group {
  margin-bottom: 1rem;
}

.navigation-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.nav-item {
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
  margin-bottom: 1rem;
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
</style>
