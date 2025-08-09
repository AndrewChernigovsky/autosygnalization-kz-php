<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../utils/fetchWithCors';
import MyBtn from '../components/UI/MyBtn.vue';

// --- Interfaces ---
interface Work {
  work_id: number;
  title: string;
  link: string;
  image_path: string;
  position: number;
}

interface Service {
  title: string;
  link: string;
}

// --- State ---
const works = ref<Work[]>([]);
const services = ref<Service[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);

const formState = ref({
  title: '',
  link: '',
  image_file: null as File | null,
  image_preview: '',
});
const editingWorkId = ref<number | null>(null);

const draggedIndex = ref<number | null>(null);

const API_URL = '/server/php/admin/api/works/works.php';

// --- Computed Properties ---
const isEditing = computed(() => editingWorkId.value !== null);

// --- API Calls ---
const getWorks = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await fetchWithCors(API_URL);
    if (!response.success)
      throw new Error(response.error || 'Failed to fetch works');
    works.value = response.data.works;
    services.value = response.data.services;
  } catch (err: any) {
    error.value = err.message;
    Swal.fire('Ошибка', `Не удалось загрузить данные: ${err.message}`, 'error');
  } finally {
    isLoading.value = false;
  }
};

const handleSubmit = async () => {
  if (!formState.value.title || !formState.value.link) {
    Swal.fire(
      'Ошибка',
      'Заголовок и ссылка являются обязательными полями.',
      'error'
    );
    return;
  }

  if (!isEditing.value && !formState.value.image_file) {
    Swal.fire('Ошибка', 'Изображение обязательно для новой работы.', 'error');
    return;
  }

  const formData = new FormData();
  formData.append('title', formState.value.title);
  formData.append('link', formState.value.link);

  if (formState.value.image_file) {
    formData.append('image', formState.value.image_file);
  }

  if (isEditing.value) {
    formData.append('work_id', String(editingWorkId.value));
  } else {
    // Для новой работы вычисляем следующую позицию
    const maxPosition = works.value.reduce(
      (max, work) => Math.max(max, work.position),
      0
    );
    formData.append('position', String(maxPosition + 1));
  }

  isLoading.value = true;
  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      body: formData,
    });
    const result = await response.json();
    if (!result.success) throw new Error(result.error);
    Swal.fire(
      'Успех!',
      `Работа успешно ${isEditing.value ? 'обновлена' : 'создана'}.`,
      'success'
    );
    await getWorks();
    cancelEdit();
  } catch (err: any) {
    Swal.fire('Ошибка', `Не удалось сохранить данные: ${err.message}`, 'error');
  } finally {
    isLoading.value = false;
  }
};

const handleDelete = async (id: number) => {
  const result = await Swal.fire({
    title: 'Вы уверены?',
    text: 'Вы не сможете отменить это действие!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
  });

  if (result.isConfirmed) {
    isLoading.value = true;
    try {
      const response = await fetchWithCors(`${API_URL}?id=${id}`, {
        method: 'DELETE',
      });
      if (!response.success) throw new Error(response.error);
      Swal.fire('Удалено!', 'Работа была успешно удалена.', 'success');
      await getWorks();
    } catch (err: any) {
      Swal.fire('Ошибка', `Не удалось удалить работу: ${err.message}`, 'error');
    } finally {
      isLoading.value = false;
    }
  }
};

const updatePositions = async () => {
  const itemsToUpdate = works.value.map((work, index) => ({
    work_id: work.work_id,
    position: index + 1,
  }));

  try {
    const response = await fetchWithCors(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        action: 'update_positions',
        items: itemsToUpdate,
      }),
    });
    if (!response.success) throw new Error(response.error);
    // Позиции обновлены, можно показать тихое уведомление, если нужно
  } catch (err: any) {
    Swal.fire(
      'Ошибка',
      `Не удалось сохранить новый порядок: ${err.message}`,
      'error'
    );
    // В случае ошибки, перезагружаем с сервера, чтобы вернуть старый порядок
    await getWorks();
  }
};

// --- Form & UI Handlers ---
const startEdit = (work: Work) => {
  editingWorkId.value = work.work_id;
  formState.value = {
    title: work.title,
    link: work.link,
    image_file: null,
    image_preview: work.image_path,
  };
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const cancelEdit = () => {
  editingWorkId.value = null;
  formState.value = {
    title: '',
    link: '',
    image_file: null,
    image_preview: '',
  };
};

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    formState.value.image_file = target.files[0];
    formState.value.image_preview = URL.createObjectURL(target.files[0]);
  }
};

// --- Drag and Drop Handlers ---
const onDragStart = (index: number) => {
  draggedIndex.value = index;
};

const onDrop = (targetIndex: number) => {
  if (draggedIndex.value === null) return;

  const draggedItem = works.value.splice(draggedIndex.value, 1)[0];
  works.value.splice(targetIndex, 0, draggedItem);

  draggedIndex.value = null;
  updatePositions();
};

// --- Lifecycle Hooks ---
onMounted(() => {
  getWorks();
});
</script>

<template>
  <div class="works-container">
    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner"></div>
    </div>

    <h1>Управление работами</h1>

    <!-- Форма создания/редактирования -->
    <div class="form-section card">
      <h2>
        {{ isEditing ? 'Редактирование работы' : 'Добавить новую работу' }}
      </h2>
      <form @submit.prevent="handleSubmit" class="form-grid">
        <div class="form-group">
          <label for="title">Заголовок</label>
          <input
            id="title"
            type="text"
            v-model="formState.title"
            placeholder="Название работы"
            class="form-input"
          />
        </div>
        <div class="form-group">
          <label for="link">Ссылка на услугу</label>
          <select id="link" v-model="formState.link" class="form-input">
            <option disabled value="">-- Выберите услугу --</option>
            <option
              v-for="service in services"
              :key="service.link"
              :value="service.link"
            >
              {{ service.title }}
            </option>
          </select>
        </div>
        <div class="form-group form-group-full">
          <label for="image">Изображение</label>
          <input
            id="image"
            type="file"
            @change="handleFileChange"
            accept="image/png, image/jpeg, image/webp"
            class="form-input-file"
          />
          <div v-if="formState.image_preview" class="image-preview">
            <img :src="formState.image_preview" alt="Предпросмотр" />
          </div>
        </div>
        <div class="form-actions form-group-full">
          <MyBtn variant="primary" type="submit">
            {{ isEditing ? 'Обновить' : 'Создать' }}
          </MyBtn>
          <MyBtn
            v-if="isEditing"
            type="button"
            @click="cancelEdit"
            class="btn btn-secondary"
          >
            Отмена
          </MyBtn>
        </div>
      </form>
    </div>

    <!-- Список работ -->
    <h2>Существующие работы</h2>
    <div class="works-list">
      <div
        v-for="(work, index) in works"
        :key="work.work_id"
        class="work-card card"
        draggable="true"
        @dragstart="onDragStart(index)"
        @dragover.prevent
        @drop="onDrop(index)"
      >
        <div
          class="work-card-img"
          :style="{ backgroundImage: `url(${work.image_path})` }"
        ></div>
        <div class="work-card-content">
          <p class="work-card-title">{{ work.title }}</p>
          <div class="work-card-actions">
            <MyBtn variant="primary" type="button" @click="startEdit(work)">
              <MyIcon name="edit" />
              РЕДАКТИРОВАТЬ
            </MyBtn>
            <MyBtn
              variant="secondary"
              @click="handleDelete(work.work_id)"
              class="btn btn-sm btn-danger"
            >
              УДАЛИТЬ
            </MyBtn>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.works-container {
  grid-column: 2 / 3;
  padding: 2rem;
  background-image: linear-gradient(90deg, #121010 0%, #0e0c0c 100%);
  color: #fff;
}

h1,
h2 {
  color: #ffffff;
  font-weight: 600;
}

h2 {
  margin-top: 2rem;
  margin-bottom: 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  padding-bottom: 0.5rem;
}

.card {
  background: transparent;
  border-radius: 8px;
  box-shadow: inset 0 0 0 1px #ffffff;
  border: none;
  padding: 1.5rem;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-weight: 500;
  color: #ffffff;
  margin-bottom: 8px;
}

.form-group-full {
  grid-column: 1 / -1;
}

.form-input,
.form-input-file,
select {
  width: 100%;
  padding: 0.75rem;
  border-radius: 4px;
  margin-top: 0.5rem;
  background-color: rgba(0, 0, 0, 0.2);
  color: #ffffff;
  border: 1px solid #ffffff;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.form-input:focus,
select:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.3);
}

.form-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: 1px solid #ffffff;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.2s;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  background-color: transparent;
  color: #ffffff;
}
.btn-primary:hover {
  background-color: #ffffff;
  color: #121010;
}
.btn-secondary {
  border-color: #6c757d;
  color: #6c757d;
}
.btn-secondary:hover {
  background-color: #6c757d;
  color: #ffffff;
}

.btn-danger:hover {
  background-color: #dc3545;
  color: #ffffff;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
}
.btn-icon {
  background: none;
  border: none;
  font-size: 1.2rem;
  padding: 0.5rem;
  color: #cccccc;
}
.btn-icon:hover {
  color: #ffffff;
}
.btn-icon.btn-danger {
  color: #dc3545;
}
.btn-icon.btn-danger:hover {
  color: #ff6b81;
}

.image-preview {
  margin-top: 1rem;
  max-width: 200px;
}
.image-preview img {
  width: 100%;
  border-radius: 4px;
  border: 1px solid #ffffff;
}

.works-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1.5rem;
}

.work-card {
  display: flex;
  flex-direction: column;
  padding: 0;
  overflow: hidden;
  cursor: grab;
  transition: transform 0.2s, box-shadow 0.2s;
  box-shadow: inset 0 0 0 1px #ffffff;
  border-radius: 8px;
  transform: translateZ(0); /* Добавлено для исправления бага с обрезкой */
}
.work-card:hover {
  transform: translateY(-5px);
  box-shadow: inset 0 0 0 1px #ffffff, 0 4px 8px rgba(0, 0, 0, 0.1);
}
.work-card:active {
  cursor: grabbing;
}
.work-card-img {
  width: 100%;
  height: 150px;
  background-size: cover;
  background-position: center;
}
.work-card-content {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}
.work-card-title {
  flex-grow: 1;
  font-weight: 600;
  margin: 0 0 1rem 0;
  color: #ffffff;
}
.work-card-actions {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

select.form-input option {
  background: #121010;
  color: #ffffff;
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 5px solid #ffffff30;
  border-top: 5px solid #ffffff;
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
</style>
