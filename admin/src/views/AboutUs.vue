<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../utils/fetchWithCors';

interface AboutUsItem {
  about_us_id: number;
  type: string;
  title: string | null;
  content: string | null;
  image_path: string | null;
  position: number;
}

const items = ref<AboutUsItem[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);
const openAccordion = ref<string | null>(null);
const imagePreviews = ref<Record<number, string>>({}); // Для превью существующих
const newImagePreviews = ref<Record<string, string>>({}); // Для превью новых

const API_URL = '/server/php/admin/api/aboutUs/aboutUs.php';

const typeConfig: Record<
  string,
  { name: string; fields: ('title' | 'content' | 'image')[] }
> = {
  'present-slogan': { name: 'Слоганы в презентации', fields: ['content'] },
  'present-text': { name: 'Текст в презентации', fields: ['content'] },
  'advantages-item': { name: 'Преимущества', fields: ['content'] },
  comment: { name: 'Комментарии', fields: ['content'] },
  'tech-photo-image': { name: 'Фотографии тех. центра', fields: ['image'] },
  'appeal-text': { name: 'Призыв к действию', fields: ['content'] },
};

const groupedItems = computed(() => {
  return items.value.reduce((acc, item) => {
    if (!acc[item.type]) {
      acc[item.type] = [];
    }
    acc[item.type].push(item);
    return acc;
  }, {} as Record<string, AboutUsItem[]>);
});

const onFileChange = (event: Event, itemId: number) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    imagePreviews.value[itemId] = URL.createObjectURL(input.files[0]);
  } else {
    delete imagePreviews.value[itemId];
  }
};

const onNewFileChange = (event: Event, type: string) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    newImagePreviews.value[type] = URL.createObjectURL(input.files[0]);
  } else {
    delete newImagePreviews.value[type];
  }
};

const getAboutUsData = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await fetchWithCors(API_URL);
    if (response.success) {
      items.value = response.data;
    } else {
      throw new Error(response.error || 'Не удалось загрузить данные');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    error.value = errorMessage;
    Swal.fire('Ошибка', errorMessage, 'error');
  } finally {
    isLoading.value = false;
  }
};

const handleCreate = async (event: Event, type: string) => {
  const form = event.target as HTMLFormElement;
  const formData = new FormData(form);
  const config = typeConfig[type];

  // Валидация на стороне клиента
  if (config.fields.includes('content')) {
    const content = formData.get('content') as string;
    if (!content || content.trim() === '') {
      Swal.fire('Ошибка', 'Поле "Содержимое" не может быть пустым.', 'error');
      return;
    }
  }

  if (config.fields.includes('image')) {
    const imageFile = formData.get('image') as File;
    if (!imageFile || imageFile.size === 0) {
      Swal.fire('Ошибка', 'Необходимо выбрать изображение.', 'error');
      return;
    }
  }

  formData.append('type', type);

  try {
    const response = await fetchWithCors(API_URL, {
      method: 'POST',
      body: formData,
    });

    if (response.success && response.data) {
      items.value.push(response.data); // Добавляем новый элемент в список
      Swal.fire('Создано!', 'Новый элемент успешно добавлен.', 'success');
      form.reset(); // Сбрасываем форму
      delete newImagePreviews.value[type]; // Очищаем превью
    } else {
      throw new Error(response.error || 'Ошибка при создании элемента');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    Swal.fire('Ошибка', errorMessage, 'error');
  }
};

const handleUpdate = async (event: Event, item: AboutUsItem) => {
  const form = event.target as HTMLFormElement;
  const formData = new FormData(form);
  formData.append('about_us_id', item.about_us_id.toString());

  if (item.title !== null) formData.append('title', item.title);
  if (item.content !== null) formData.append('content', item.content);

  try {
    const response = await fetchWithCors(API_URL, {
      method: 'POST',
      body: formData,
    });

    if (response.success) {
      if (response.data.image_path) {
        item.image_path = response.data.image_path;
        delete imagePreviews.value[item.about_us_id]; // Очищаем превью после успешной загрузки
      }
      Swal.fire('Сохранено!', 'Данные успешно обновлены.', 'success');
    } else {
      throw new Error(response.error || 'Ошибка при сохранении');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    Swal.fire('Ошибка', errorMessage, 'error');
  }
};

const handleDelete = async (id: number) => {
  const result = await Swal.fire({
    title: 'Вы уверены?',
    text: 'Вы не сможете восстановить этот элемент!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
  });

  if (result.isConfirmed) {
    try {
      const response = await fetchWithCors(`${API_URL}?id=${id}`, {
        method: 'DELETE',
      });

      if (response.success) {
        items.value = items.value.filter((item) => item.about_us_id !== id);
        Swal.fire('Удалено!', 'Элемент был успешно удален.', 'success');
      } else {
        throw new Error(response.error || 'Ошибка при удалении');
      }
    } catch (err) {
      const errorMessage =
        err instanceof Error ? err.message : 'Неизвестная ошибка';
      Swal.fire('Ошибка', errorMessage, 'error');
    }
  }
};

const toggleAccordion = (type: string) => {
  openAccordion.value = openAccordion.value === type ? null : type;
};

onMounted(getAboutUsData);
</script>

<template>
  <div class="container">
    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner"></div>
    </div>
    <div v-else-if="error" class="error-message">
      <p>{{ error }}</p>
      <button @click="getAboutUsData">Попробовать снова</button>
    </div>
    <div v-else class="accordion">
      <div
        v-for="(group, type) in groupedItems"
        :key="type"
        class="accordion-item"
      >
        <button class="accordion-header" @click="toggleAccordion(type)">
          <span>{{ typeConfig[type]?.name || type }}</span>
          <span
            class="accordion-arrow"
            :class="{ 'is-open': openAccordion === type }"
          ></span>
        </button>
        <div v-show="openAccordion === type" class="accordion-content">
          <!-- Формы редактирования существующих элементов -->
          <form
            v-for="item in group"
            :key="item.about_us_id"
            class="form-group"
            @submit.prevent="handleUpdate($event, item)"
          >
            <template v-if="typeConfig[type]?.fields.includes('content')">
              <label>Содержимое:</label>
              <textarea
                v-model="item.content"
                name="content"
                rows="4"
              ></textarea>
            </template>

            <template v-if="typeConfig[type]?.fields.includes('image')">
              <label>Изображение:</label>
              <img
                v-if="imagePreviews[item.about_us_id] || item.image_path"
                :src="imagePreviews[item.about_us_id] || item.image_path || ''"
                alt="preview"
                class="image-preview"
              />
              <input
                type="file"
                name="image"
                accept="image/*"
                @change="onFileChange($event, item.about_us_id)"
              />
            </template>

            <div class="actions">
              <button type="submit" class="btn-save">Сохранить</button>
              <button
                type="button"
                @click="handleDelete(item.about_us_id)"
                class="btn-delete"
              >
                Удалить
              </button>
            </div>
          </form>

          <!-- Форма для добавления нового элемента -->
          <form class="form-add" @submit.prevent="handleCreate($event, type)">
            <h4 class="form-add-title">Добавить новое содержимое</h4>

            <template v-if="typeConfig[type]?.fields.includes('content')">
              <label>Новое содержимое:</label>
              <textarea name="content" rows="4" required></textarea>
            </template>

            <template v-if="typeConfig[type]?.fields.includes('image')">
              <label>Новое изображение:</label>
              <img
                v-if="newImagePreviews[type]"
                :src="newImagePreviews[type]"
                alt="preview"
                class="image-preview"
              />
              <input
                type="file"
                name="image"
                accept="image/*"
                @change="onNewFileChange($event, type)"
                required
              />
            </template>

            <button type="submit" class="btn-add">Добавить</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  color: black;
  padding: 2rem;
  font-family: 'Arial', sans-serif;
}
.loading-overlay {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
.spinner {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 50px;
  height: 50px;
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
  color: red;
  text-align: center;
}
.accordion {
  width: 100%;
}
.accordion-item {
  border-bottom: 1px solid #ddd;
}
.accordion-header {
  width: 100%;
  background-color: black;
  border: none;
  padding: 1rem;
  text-align: left;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 1.1rem;
  font-weight: bold;
}
.accordion-arrow {
  width: 10px;
  height: 10px;
  border-right: 2px solid #333;
  border-bottom: 2px solid #333;
  transform: rotate(45deg);
  transition: transform 0.3s;
}
.accordion-arrow.is-open {
  transform: rotate(-135deg);
}
.accordion-content {
  padding: 1rem;
  background-color: #fff;
}
.form-group {
  margin-bottom: 1.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #eee;
}
.form-group:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}
.form-add {
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 2px dashed #ccc;
}
.form-add-title {
  margin-bottom: 1rem;
  font-size: 1.2rem;
}
label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}
input,
textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.image-preview {
  max-width: 200px;
  margin-top: 0.5rem;
  display: block;
}
.actions {
  margin-top: 1rem;
  display: flex;
  gap: 1rem;
}
.btn-save,
.btn-delete,
.btn-add {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  color: white;
  cursor: pointer;
}
.btn-save {
  background-color: #28a745;
}
.btn-delete {
  background-color: #dc3545;
}
.btn-add {
  background-color: #007bff;
  margin-top: 1rem;
}
</style>
