<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
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

const toolbarOptions = [
  [{ header: [1, 2, 3, false] }],
  ['bold', 'italic', 'underline', 'strike'],
  [{ list: 'ordered' }, { list: 'bullet' }],
  ['clean'],
];

const items = ref<AboutUsItem[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);
const openAccordion = ref<string | null>(null);
const imagePreviews = ref<Record<number, string>>({}); // Для превью существующих
const newImagePreviews = ref<Record<string, string>>({}); // Для превью новых
const newItemContent = ref<Record<string, string>>({}); // Для контента нового QuillEditor

// Для отслеживания перетаскиваемого элемента
const draggingItem = ref<number | null>(null);

const API_URL = '/server/php/admin/api/aboutUs/aboutUs.php';

const typeConfig: Record<
  string,
  { name: string; fields: ('title' | 'content' | 'image' | 'list')[] }
> = {
  'present-slogan': { name: 'Слоганы в презентации', fields: ['content'] },
  'present-text': { name: 'Текст в презентации', fields: ['content'] },
  'advantages-list': { name: 'Список преимуществ', fields: ['list'] },
  comment: { name: 'Комментарии', fields: ['content'] },
  'tech-photo-image': { name: 'Фотографии тех. центра', fields: ['image'] },
  'appeal-text': { name: 'Обращение к клиентам', fields: ['content'] },
};

const groupedItems = computed(() => {
  // Инициализируем группы всеми возможными типами из typeConfig,
  // чтобы аккордеон отображался, даже если для типа нет данных.
  const groups = Object.keys(typeConfig).reduce((acc, type) => {
    acc[type] = [];
    return acc;
  }, {} as Record<string, AboutUsItem[]>);

  // Заполняем группы элементами из базы данных.
  items.value.forEach((item) => {
    // Убеждаемся, что тип элемента существует в нашей конфигурации.
    if (groups[item.type]) {
      groups[item.type].push(item);
    }
  });

  // Сортировка каждой группы по position
  for (const type in groups) {
    groups[type].sort((a, b) => a.position - b.position);
  }

  return groups;
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
  if (config.fields.includes('list')) {
    const content = newItemContent.value[type] || '';
    // Проверяем, что контент не пустой, игнорируя HTML теги
    if (!content.replace(/<(.|\n)*?>/g, '').trim()) {
      Swal.fire('Ошибка', 'Поле "Содержимое" не может быть пустым.', 'error');
      return;
    }
    formData.append('content', content);
  } else if (config.fields.includes('content')) {
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
      if (newItemContent.value[type]) {
        newItemContent.value[type] = ''; // Очищаем редактор
      }
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

const handleUpdatePositions = async (updatedGroup: AboutUsItem[]) => {
  const itemsToUpdate = updatedGroup.map((item, index) => ({
    about_us_id: item.about_us_id,
    position: index + 1,
  }));

  try {
    await fetchWithCors(API_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }, // Важно для этого запроса
      body: JSON.stringify({
        action: 'update_positions',
        items: itemsToUpdate,
      }),
    });
  } catch (err) {
    Swal.fire('Ошибка', 'Не удалось обновить порядок элементов.', 'error');
    // Можно добавить логику отката изменений в UI, если нужно
  }
};

// Drag and Drop Handlers
const onDragStart = (id: number) => {
  draggingItem.value = id;
};

const onDrop = (targetId: number, type: string) => {
  if (draggingItem.value === null) return;

  const group = groupedItems.value[type];
  const fromIndex = group.findIndex(
    (it) => it.about_us_id === draggingItem.value
  );
  const toIndex = group.findIndex((it) => it.about_us_id === targetId);

  if (fromIndex !== -1 && toIndex !== -1) {
    // 1. Перемещаем элемент в локальной копии группы
    const [movedItem] = group.splice(fromIndex, 1);
    group.splice(toIndex, 0, movedItem);

    // 2. Обновляем позиции для всей группы
    group.forEach((item, index) => {
      item.position = index + 1;
    });

    // 3. Отправляем изменения на сервер
    handleUpdatePositions(group);
  }

  draggingItem.value = null; // Сбрасываем
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
            class="form-group draggable"
            draggable="true"
            @dragstart="onDragStart(item.about_us_id)"
            @dragover.prevent
            @drop="onDrop(item.about_us_id, type)"
            @submit.prevent="handleUpdate($event, item)"
          >
            <div class="drag-handle">⠿</div>
            <div class="content-wrapper">
              <template v-if="typeConfig[type]?.fields.includes('content')">
                <label>Содержимое:</label>
                <textarea
                  v-model="item.content"
                  name="content"
                  rows="4"
                ></textarea>
              </template>

              <template v-if="typeConfig[type]?.fields.includes('list')">
                <label>Содержимое:</label>
                <QuillEditor
                  :key="item.about_us_id + '-list'"
                  theme="snow"
                  :toolbar="toolbarOptions"
                  contentType="html"
                  v-model:content="item.content"
                />
              </template>

              <template v-if="typeConfig[type]?.fields.includes('image')">
                <label>Изображение:</label>
                <img
                  v-if="imagePreviews[item.about_us_id] || item.image_path"
                  :src="
                    imagePreviews[item.about_us_id] || item.image_path || ''
                  "
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
            </div>
          </form>

          <!-- Форма для добавления нового элемента -->
          <form class="form-add" @submit.prevent="handleCreate($event, type)">
            <template v-if="typeConfig[type]?.fields.includes('content')">
              <label>Новое содержимое:</label>
              <textarea name="content" rows="4" required></textarea>
            </template>

            <template v-if="typeConfig[type]?.fields.includes('list')">
              <label>Новое содержимое:</label>
              <QuillEditor
                theme="snow"
                :toolbar="toolbarOptions"
                contentType="html"
                v-model:content="newItemContent[type]"
              />
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
/* Общие стили контейнера */
.container {
  background-color: #2d2d2d;
  color: #e0e0e0;
  padding: 2rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica,
    Arial, sans-serif;
  min-height: 100vh;
}

/* Стили загрузчика и ошибок */
.loading-overlay {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
.spinner {
  border: 4px solid #444;
  border-top: 4px solid #007bff;
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
  color: #ff6b6b;
  text-align: center;
}
.error-message button {
  margin-top: 1rem;
}

/* Стили аккордеона */
.accordion {
  width: 100%;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid #444;
}
.accordion-item {
  border-bottom: 1px solid #444;
}
.accordion-item:last-child {
  border-bottom: none;
}
.accordion-header {
  width: 100%;
  background-color: #3a3a3a;
  border: none;
  padding: 1rem 1.5rem;
  text-align: left;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 1.2rem;
  font-weight: 600;
  color: #fff;
  transition: background-color 0.3s;
}
.accordion-header:hover {
  background-color: #4a4a4a;
}
.accordion-arrow {
  width: 10px;
  height: 10px;
  border-right: 2px solid #ccc;
  border-bottom: 2px solid #ccc;
  transform: rotate(45deg);
  transition: transform 0.3s;
}
.accordion-arrow.is-open {
  transform: translateY(2px) rotate(-135deg);
}
.accordion-content {
  padding: 1.5rem;
  background-color: #333;
}

/* Стили форм */
.form-group {
  margin-bottom: 1.5rem;
  padding: 1.5rem;
  border: 1px solid #444;
  border-radius: 8px;
  background-color: #3c3c3c;
}
.form-add {
  margin-top: 2rem;
  padding: 1.5rem;
  border: 2px dashed #555;
  border-radius: 8px;
}
label {
  display: block;
  margin-bottom: 0.75rem;
  font-weight: 500;
  color: #ccc;
}
input,
textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #555;
  border-radius: 4px;
  background-color: #2c2c2c;
  color: #e0e0e0;
  transition: border-color 0.3s, box-shadow 0.3s;
}
input:focus,
textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
}
.image-preview {
  max-width: 200px;
  margin: 1rem 0;
  border-radius: 4px;
  border: 1px solid #555;
}

/* Стили кнопок */
.actions {
  margin-top: 1rem;
  display: flex;
  gap: 1rem;
}
.btn-save,
.btn-delete,
.btn-add {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  color: white;
  cursor: pointer;
  font-weight: bold;
  transition: transform 0.2s, filter 0.2s;
}
.btn-save:hover,
.btn-delete:hover,
.btn-add:hover {
  transform: translateY(-2px);
  filter: brightness(1.1);
}
.btn-save {
  background-image: linear-gradient(45deg, #28a745, #218838);
}
.btn-delete {
  background-image: linear-gradient(45deg, #dc3545, #c82333);
}
.btn-add {
  background-image: linear-gradient(45deg, #007bff, #0069d9);
  margin-top: 1rem;
}

/* Стили для Drag-n-Drop */
.draggable {
  cursor: grab;
  position: relative;
  display: flex;
  align-items: flex-start;
  gap: 15px;
}
.draggable:active {
  cursor: grabbing;
}
.drag-handle {
  font-size: 24px;
  color: #777;
  padding-top: 2.5rem; /* Выравнивание по центру */
  transition: color 0.3s;
}
.draggable:hover .drag-handle {
  color: #ccc;
}
.content-wrapper {
  flex-grow: 1;
}

/* Стили для Quill Editor под темную тему */
:deep(.ql-toolbar) {
  background: #3c3c3c;
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
  border-color: #555 !important;
}
:deep(.ql-container) {
  background: #2c2c2c;
  border-bottom-left-radius: 4px;
  border-bottom-right-radius: 4px;
  color: #e0e0e0;
  border-color: #555 !important;
}
:deep(.ql-editor) {
  min-height: 150px;
}
:deep(.ql-snow .ql-stroke) {
  stroke: #ccc;
}
:deep(.ql-snow .ql-fill) {
  fill: #ccc;
}
:deep(.ql-snow .ql-picker-label) {
  color: #ccc;
}
</style>
