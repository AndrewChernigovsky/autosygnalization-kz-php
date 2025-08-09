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
const fileInputs = ref<Record<string, HTMLInputElement | null>>({}); // Для сброса инпутов

// НОВОЕ: Состояние для новых, еще не сохраненных слотов изображений
interface NewImageSlot {
  tempId: number;
  file: File | null;
  preview: string | null;
}
const newImageSlots = ref<NewImageSlot[]>([]);

// Для отслеживания перетаскиваемого элемента
const draggingItem = ref<number | null>(null);

const API_URL = '/server/php/admin/api/aboutUs/aboutUs.php';

const typeConfig: Record<
  string,
  {
    name: string;
    fields: ('content' | 'image' | 'list')[];
    single: boolean; // true, если должен быть только один экземпляр
  }
> = {
  'present-slogan-block': {
    name: 'Слоган',
    fields: ['list'],
    single: true,
  },
  'present-text-block': {
    name: 'Текст',
    fields: ['list'],
    single: true,
  },
  'advantages-list': {
    name: 'Список преимуществ',
    fields: ['list'],
    single: true,
  },
  'comment-block': {
    name: 'Комментарии',
    fields: ['list'],
    single: true,
  },
  'appeal-text-block': {
    name: 'Обращение к клиентам',
    fields: ['list'],
    single: true,
  },
  'tech-photo-image': {
    name: 'Фотографии',
    fields: ['image'],
    single: false,
  },
};

const groupedItems = computed(() => {
  const groups = Object.keys(typeConfig).reduce((acc, type) => {
    acc[type] = [];
    return acc;
  }, {} as Record<string, AboutUsItem[]>);

  items.value.forEach((item) => {
    if (groups[item.type]) {
      groups[item.type].push(item);
    }
  });

  for (const type in groups) {
    groups[type].sort((a, b) => a.position - b.position);
  }

  return groups;
});

const getAboutUsData = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await fetchWithCors(API_URL);
    if (response.success) {
      items.value = response.data;
      newImageSlots.value = []; // Сбрасываем временные слоты при перезагрузке
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

const handleCreate = async (
  event: Event,
  type: string,
  slot?: NewImageSlot,
  slotIndex?: number
) => {
  const form = event.target as HTMLFormElement;
  const formData = new FormData(form);
  const config = typeConfig[type];

  if (config.fields.includes('list')) {
    const content =
      (form.querySelector('.ql-editor') as HTMLElement)?.innerHTML || '';
    if (!content.replace(/<(.|\\n)*?>/g, '').trim()) {
      Swal.fire('Ошибка', 'Содержимое не может быть пустым.', 'error');
      return;
    }
    formData.append('content', content);
  }

  // Обновленная логика для изображений
  if (config.fields.includes('image')) {
    if (!slot || !slot.file) {
      Swal.fire('Ошибка', 'Необходимо выбрать изображение.', 'error');
      return;
    }
    formData.append('image', slot.file);
  }

  formData.append('type', type);

  try {
    const response = await fetchWithCors(API_URL, {
      method: 'POST',
      body: formData,
    });

    if (response.success && response.data) {
      items.value.push(response.data);
      Swal.fire('Создано!', 'Новый элемент успешно добавлен.', 'success');
      // Удаляем использованный слот
      if (slotIndex !== undefined) {
        newImageSlots.value.splice(slotIndex, 1);
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

  const config = typeConfig[item.type];

  // Валидация: для элементов-изображений нельзя сохранять без картинки
  if (config.fields.includes('image')) {
    const inputFile = form.querySelector(
      'input[type="file"]'
    ) as HTMLInputElement;
    const hasNewFile = inputFile?.files?.[0];

    if (!item.image_path && !hasNewFile) {
      Swal.fire(
        'Ошибка',
        'Для этого элемента необходимо изображение.',
        'error'
      );
      return; // Прерываем сохранение
    }
  }

  const editorContent = (form.querySelector('.ql-editor') as HTMLElement)
    ?.innerHTML;
  if (editorContent !== undefined) {
    formData.append('content', editorContent);
  }

  // Если путь к изображению был сброшен, отправляем флаг на удаление
  if (item.image_path === null) {
    formData.append('remove_image', '1');
  }

  try {
    const response = await fetchWithCors(API_URL, {
      method: 'POST',
      body: formData,
    });

    if (response.success) {
      if (response.data.image_path) {
        item.image_path = response.data.image_path;
        delete imagePreviews.value[item.about_us_id];
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

// Drag and Drop Handlers
const handleUpdatePositions = async (updatedGroup: AboutUsItem[]) => {
  const itemsToUpdate = updatedGroup.map((item, index) => ({
    about_us_id: item.about_us_id,
    position: index + 1,
  }));

  try {
    await fetchWithCors(API_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'update_positions',
        items: itemsToUpdate,
      }),
    });
  } catch (err) {
    Swal.fire('Ошибка', 'Не удалось обновить порядок элементов.', 'error');
  }
};

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
    const [movedItem] = group.splice(fromIndex, 1);
    group.splice(toIndex, 0, movedItem);
    group.forEach((item, index) => {
      item.position = index + 1;
    });
    handleUpdatePositions(group);
  }
  draggingItem.value = null;
};

// НОВОЕ: Функции для управления слотами
const addNewImageSlot = () => {
  newImageSlots.value.push({
    tempId: Date.now(),
    file: null,
    preview: null,
  });
};

const removeNewImageSlot = (index: number) => {
  newImageSlots.value.splice(index, 1);
};

const clearNewImageInSlot = (slot: NewImageSlot) => {
  slot.file = null;
  slot.preview = null;
  // Также сбрасываем значение в самом input-элементе
  const inputKey = `new-${slot.tempId}`;
  if (fileInputs.value[inputKey]) {
    fileInputs.value[inputKey]!.value = '';
  }
};

const clearExistingImage = (item: AboutUsItem) => {
  // Обнуляем путь и превью
  item.image_path = null;
  delete imagePreviews.value[item.about_us_id];

  // Сбрасываем значение в самом input-элементе
  const inputKey = `existing-${item.about_us_id}`;
  if (fileInputs.value[inputKey]) {
    fileInputs.value[inputKey]!.value = '';
  }
};

const onFileChange = (event: Event, itemId: number) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    imagePreviews.value[itemId] = URL.createObjectURL(input.files[0]);
  } else {
    delete imagePreviews.value[itemId];
  }
};

const onNewFileChangeInSlot = (event: Event, slot: NewImageSlot) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    slot.file = input.files[0];
    slot.preview = URL.createObjectURL(input.files[0]);
  } else {
    slot.file = null;
    slot.preview = null;
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
          <!-- Рендеринг для ОДИНОЧНЫХ блоков -->
          <template v-if="typeConfig[type]?.single">
            <form
              v-if="group.length > 0"
              :key="group[0].about_us_id"
              class="form-group"
              @submit.prevent="handleUpdate($event, group[0])"
            >
              <div class="content-wrapper">
                <QuillEditor
                  theme="snow"
                  :toolbar="toolbarOptions"
                  contentType="html"
                  v-model:content="group[0].content"
                />
                <div class="actions">
                  <button type="submit" class="btn-save">Сохранить</button>
                </div>
              </div>
            </form>
            <!-- Форма создания для одиночного блока, если он пуст -->
            <form
              v-else
              class="form-add"
              @submit.prevent="handleCreate($event, type)"
            >
              <QuillEditor
                theme="snow"
                :toolbar="toolbarOptions"
                contentType="html"
              />
              <button type="submit" class="btn-add">Создать</button>
            </form>
          </template>

          <!-- Рендеринг для СПИСКА (галереи) -->
          <template v-else>
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
                <label>Изображение:</label>
                <div class="image-uploader">
                  <!-- The file input is always in the DOM but hidden -->
                  <input
                    type="file"
                    name="image"
                    accept="image/*"
                    class="hidden-file-input"
                    :id="`file-input-existing-${item.about_us_id}`"
                    :ref="
                      (el) =>
                        (fileInputs[`existing-${item.about_us_id}`] =
                          el as HTMLInputElement)
                    "
                    @change="onFileChange($event, item.about_us_id)"
                  />
                  <!-- Preview with remove button, shown if an image exists -->
                  <div
                    v-if="imagePreviews[item.about_us_id] || item.image_path"
                    class="image-preview-wrapper"
                  >
                    <img
                      :src="
                        imagePreviews[item.about_us_id] || item.image_path || ''
                      "
                      alt="preview"
                      class="image-preview"
                    />
                    <button
                      type="button"
                      class="btn-remove-image"
                      @click="clearExistingImage(item)"
                    >
                      ×
                    </button>
                  </div>
                  <!-- Placeholder, shown if no image exists. It's a label for the input. -->
                  <label
                    v-else
                    :for="`file-input-existing-${item.about_us_id}`"
                    class="image-uploader-placeholder"
                  >
                    <span>+</span>
                  </label>
                </div>

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

            <!-- НОВЫЙ БЛОК: Рендеринг новых слотов для изображений -->
            <form
              v-for="(slot, index) in newImageSlots"
              :key="slot.tempId"
              class="form-add"
              @submit.prevent="handleCreate($event, type, slot, index)"
            >
              <div class="content-wrapper">
                <label>Новое изображение:</label>
                <div class="image-uploader">
                  <!-- The file input is always in the DOM but hidden -->
                  <input
                    type="file"
                    name="image"
                    accept="image/*"
                    class="hidden-file-input"
                    :id="`file-input-new-${slot.tempId}`"
                    :ref="
                      (el) =>
                        (fileInputs[`new-${slot.tempId}`] =
                          el as HTMLInputElement)
                    "
                    @change="onNewFileChangeInSlot($event, slot)"
                  />
                  <!-- Preview with remove button -->
                  <div v-if="slot.preview" class="image-preview-wrapper">
                    <img
                      :src="slot.preview"
                      alt="preview"
                      class="image-preview"
                    />
                    <button
                      type="button"
                      class="btn-remove-image"
                      @click="clearNewImageInSlot(slot)"
                    >
                      ×
                    </button>
                  </div>
                  <!-- Placeholder -->
                  <label
                    v-else
                    :for="`file-input-new-${slot.tempId}`"
                    class="image-uploader-placeholder"
                  >
                    <span>+</span>
                  </label>
                </div>
                <div class="actions">
                  <button type="submit" class="btn-save">Сохранить</button>
                  <button
                    type="button"
                    @click="removeNewImageSlot(index)"
                    class="btn-delete"
                  >
                    Удалить слот
                  </button>
                </div>
              </div>
            </form>

            <!-- Кнопка для добавления нового слота -->
            <div class="add-slot-wrapper">
              <button
                type="button"
                class="btn-add btn-add-slot"
                @click="addNewImageSlot"
              >
                Добавить слот для фото
              </button>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Стили остаются без изменений, т.к. они уже адаптированы под темную тему */
.container {
  background-color: #2d2d2d;
  color: #e0e0e0;
  padding: 2rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica,
    Arial, sans-serif;
  min-height: 100vh;
}
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
.actions {
  margin-top: 2rem;
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
.add-slot-wrapper {
  text-align: center;
  margin-top: 2rem;
}
.btn-add-slot {
  padding: 0.8rem 2rem;
  font-size: 1rem;
}
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

/* --- НОВЫЕ СТИЛИ ДЛЯ ЗАГРУЗЧИКА ИЗОБРАЖЕНИЙ --- */
.image-uploader {
  position: relative;
  width: 150px;
  height: 150px;
}

.image-uploader-placeholder {
  width: 100%;
  height: 100%;
  border: 2px dashed #555;
  border-radius: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  background-color: #3a3a3a;
  transition: background-color 0.3s, border-color 0.3s;
}
.image-uploader-placeholder:hover {
  background-color: #4a4a4a;
  border-color: #777;
}
.image-uploader-placeholder span {
  font-size: 48px;
  color: #777;
  transition: color 0.3s;
}
.image-uploader-placeholder:hover span {
  color: #ccc;
}

.hidden-file-input {
  display: none;
}

.image-preview-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
}

.image-preview {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #555;
}

.btn-remove-image {
  position: absolute;
  top: 0px;
  right: -10px;
  width: 28px;
  height: 28px;
  background-color: #dc3545;
  color: white;
  border: 2px solid #2d2d2d;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 20px;
  line-height: 1;
  cursor: pointer;
  transition: transform 0.2s, background-color 0.2s;
  padding: 0;
}
.btn-remove-image:hover {
  transform: scale(1.1);
  background-color: #c82333;
}
/* --- КОНЕЦ НОВЫХ СТИЛЕЙ --- */

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
