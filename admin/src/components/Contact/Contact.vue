<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';

interface ContactItem {
  contact_id: number;
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
      contacts.value = data;
    } else {
      throw new Error('Failed to load navigation');
    }
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Unknown error';
    await Swal.fire({
      title: 'Ошибка!',
      text: 'Не удалось загрузить навигацию',
      icon: 'error',
    });
  } finally {
    isLoading.value = false;
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

    <!-- <div>
      <div class="add-nav-form">
        <h2 class="add-nav-form-title">Добавить новую ссылку</h2>
        <div class="input-group">
          <label :for="'title-create'">Заголовок:</label>
          <input type="text" :id="'title-create'" v-model="newNav.title" />
        </div>
        <div class="input-group">
          <label :for="'slug-create'">Slug:</label>
          <input type="text" :id="'slug-create'" v-model="newNav.slug" />
        </div>
        <div class="input-group">
          <label :for="'href-create'">Ссылка:</label>
          <input type="text" :id="'href-create'" v-model="newNav.href" />
        </div>
        <button class="btn save" @click="createNavigation">Добавить</button>
      </div>

      <div class="navigation-grid">
        <div
          v-for="item in navigation"
          :key="item.navigation_id"
          class="nav-item"
        >
          <div class="input-group">
            <label :for="'title-' + item.navigation_id">Заголовок:</label>
            <input
              :id="'title-' + item.navigation_id"
              type="text"
              v-model="item.title"
            />
          </div>
          <div class="input-group">
            <label :for="'title-' + item.slug">Slug:</label>
            <input type="text" :id="'title-' + item.slug" v-model="item.slug" />
          </div>
          <div class="input-group">
            <label :for="'title-' + item.href">Ссылка:</label>
            <input type="text" :id="'title-' + item.href" v-model="item.href" />
          </div>
          <div class="button-group">
            <button
              class="btn save"
              @click="updateNavigation(item.navigation_id)"
            >
              Сохранить
            </button>
            <button
              class="btn delete"
              @click="deleteNavigation(item.navigation_id)"
            >
              Удалить
            </button>
          </div>
        </div>
      </div>
    </div> -->
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
