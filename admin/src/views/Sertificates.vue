<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../utils/fetchWithCors';
import MyBtn from '../components/UI/MyBtn.vue';

// --- ИНТЕРФЕЙСЫ ---
interface Sertificate {
  sertificate_id: number;
  image_path: string;
  position: number;
}

interface NewSertificateSlot {
  tempId: number;
  file: File | null;
  preview: string | null;
}

// --- КОНСТАНТЫ ---
const API_URL = '/server/php/admin/api/sertificates/sertificates.php';

// --- СОСТОЯНИЕ ---
const sertificates = ref<Sertificate[]>([]);
const newSlots = ref<NewSertificateSlot[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);
const draggingItem = ref<number | null>(null);
const fileInputs = ref<Record<string, HTMLInputElement | null>>({});
const filesToUpdate = ref<Record<number, File | null>>({});
const previewsForUpdate = ref<Record<number, string | null>>({});

// --- ПОЛУЧЕНИЕ ДАННЫХ ---
const fetchData = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await fetchWithCors(API_URL);
    if (response.success) {
      sertificates.value = response.data.sort(
        (a: Sertificate, b: Sertificate) => a.position - b.position
      );
    } else {
      throw new Error(response.error || 'Не удалось загрузить сертификаты');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    error.value = errorMessage;
    Swal.fire(
      'Ошибка',
      `Не удалось загрузить данные: ${errorMessage}`,
      'error'
    );
  } finally {
    isLoading.value = false;
  }
};

// --- CRUD ОПЕРАЦИИ ---

const handleCreate = async (slot: NewSertificateSlot, index: number) => {
  if (!slot.file) {
    Swal.fire('Ошибка', 'Сначала загрузите фото.', 'error');
    return;
  }

  const formData = new FormData();
  formData.append('action', 'create');
  formData.append('image', slot.file);

  try {
    const response = await fetchWithCors(API_URL, {
      method: 'POST',
      body: formData,
    });

    if (response.success && response.data) {
      sertificates.value.push(response.data);
      // Удаляем слот после успешного создания
      newSlots.value.splice(index, 1);
      Swal.fire('Создано!', 'Новый сертификат успешно добавлен.', 'success');
    } else {
      throw new Error(response.error || 'Ошибка при создании');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    Swal.fire('Ошибка', errorMessage, 'error');
  }
};

const handleUpdate = async (id: number) => {
  const file = filesToUpdate.value[id];
  if (!file) {
    Swal.fire('Ошибка', 'Нет файла для обновления.', 'error');
    return;
  }

  const formData = new FormData();
  formData.append('action', 'update');
  formData.append('sertificate_id', id.toString());
  formData.append('image', file);

  try {
    const response = await fetchWithCors(API_URL, {
      method: 'POST',
      body: formData,
    });

    if (response.success && response.data) {
      const index = sertificates.value.findIndex(
        (s) => s.sertificate_id === id
      );
      if (index !== -1) {
        sertificates.value[index] = response.data;
      }
      // Очистка состояния обновления
      delete filesToUpdate.value[id];
      delete previewsForUpdate.value[id];

      Swal.fire('Обновлено!', 'Сертификат успешно заменен.', 'success');
    } else {
      throw new Error(response.error || 'Ошибка при обновлении');
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
    text: 'Вы не сможете восстановить этот сертификат!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
  });

  if (result.isConfirmed) {
    try {
      const response = await fetchWithCors(`${API_URL}?id=${id}`, {
        method: 'DELETE',
      });

      if (response.success) {
        sertificates.value = sertificates.value.filter(
          (item) => item.sertificate_id !== id
        );
        Swal.fire('Удалено!', 'Сертификат был успешно удален.', 'success');
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

const handleUpdatePositions = async (itemsToUpdate: Sertificate[]) => {
  const positions = itemsToUpdate.map((item, index) => ({
    sertificate_id: item.sertificate_id,
    position: index + 1,
  }));

  try {
    const response = await fetchWithCors(API_URL, {
      method: 'POST',
      body: JSON.stringify({
        action: 'update_positions',
        items: positions,
      }),
    });

    if (!response.success) {
      throw new Error(response.error || 'Не удалось обновить порядок');
    }
    Swal.fire({
      toast: true,
      position: 'top',
      icon: 'success',
      title: 'Порядок изменен',
      showConfirmButton: false,
      timer: 1500,
    });
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    Swal.fire(
      'Ошибка',
      `Не удалось обновить порядок: ${errorMessage}`,
      'error'
    );
    await fetchData(); // Возвращаем к исходному порядку
  }
};

// --- DRAG-AND-DROP ЛОГИКА ---

const onDragStart = (id: number) => {
  draggingItem.value = id;
};

const onDrop = (targetId: number) => {
  if (draggingItem.value === null || draggingItem.value === targetId) {
    draggingItem.value = null;
    return;
  }

  const fromIndex = sertificates.value.findIndex(
    (it) => it.sertificate_id === draggingItem.value
  );
  const toIndex = sertificates.value.findIndex(
    (it) => it.sertificate_id === targetId
  );

  if (fromIndex !== -1 && toIndex !== -1) {
    const [movedItem] = sertificates.value.splice(fromIndex, 1);
    sertificates.value.splice(toIndex, 0, movedItem);
    handleUpdatePositions(sertificates.value);
  }
  draggingItem.value = null;
};

// --- УПРАВЛЕНИЕ ВЫБОРОМ ФАЙЛОВ ---

const addNewSlot = () => {
  newSlots.value.push({ tempId: Date.now(), file: null, preview: null });
};

const removeNewSlot = (index: number) => {
  newSlots.value.splice(index, 1);
  Swal.fire({
    toast: true,
    position: 'top',
    icon: 'info',
    title: 'Слот удален',
    showConfirmButton: false,
    timer: 1500,
  });
};

const onFileChangeForNew = (event: Event, slot: NewSertificateSlot) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    const file = input.files[0];
    // --- ВАЛИДАЦИЯ ФАЙЛА ---
    if (file.size > 10485760) {
      // 10 МБ
      Swal.fire(
        'Ошибка',
        'Файл слишком большой. Максимальный размер - 10 МБ.',
        'error'
      );
      clearImageInNewSlot(slot);
      return;
    }
    if (file.type !== 'application/pdf') {
      Swal.fire(
        'Ошибка',
        'Неверный формат файла. Разрешены только PDF.',
        'error'
      );
      clearImageInNewSlot(slot);
      return;
    }
    // --- КОНЕЦ ВАЛИДАЦИИ ---
    slot.file = file;
    slot.preview = URL.createObjectURL(file);
  }
};

const clearImageInNewSlot = (slot: NewSertificateSlot) => {
  slot.file = null;
  slot.preview = null;
  const inputKey = `new-sertificate-${slot.tempId}`;
  if (fileInputs.value[inputKey]) {
    fileInputs.value[inputKey]!.value = '';
  }
};

const triggerFileSelect = (id: number) => {
  const inputKey = `existing-sertificate-${id}`;
  fileInputs.value[inputKey]?.click();
};

const onFileChangeForExisting = (event: Event, id: number) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    const file = input.files[0];
    // --- ВАЛИДАЦИЯ ФАЙЛА ---
    if (file.size > 10485760) {
      // 10 МБ
      Swal.fire(
        'Ошибка',
        'Файл слишком большой. Максимальный размер - 10 МБ.',
        'error'
      );
      cancelUpdate(id);
      return;
    }
    if (file.type !== 'application/pdf') {
      Swal.fire(
        'Ошибка',
        'Неверный формат файла. Разрешены только PDF.',
        'error'
      );
      cancelUpdate(id);
      return;
    }
    // --- КОНЕЦ ВАЛИДАЦИИ ---
    filesToUpdate.value[id] = file;
    previewsForUpdate.value[id] = URL.createObjectURL(file);
  }
};

const cancelUpdate = (id: number) => {
  delete filesToUpdate.value[id];
  delete previewsForUpdate.value[id];
  const inputKey = `existing-sertificate-${id}`;
  if (fileInputs.value[inputKey]) {
    fileInputs.value[inputKey]!.value = '';
  }
};

// --- LIFECYCLE HOOKS ---
onMounted(fetchData);
</script>

<template>
  <div class="container-sertificates">
    <h1 class="main-title">Управление сертификатами</h1>

    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner"></div>
    </div>
    <div v-else-if="error" class="error-message">
      <p>{{ error }}</p>
      <button @click="fetchData">Попробовать снова</button>
    </div>

    <div v-else class="sertificates-grid">
      <!-- Существующие сертификаты -->
      <div
        v-for="sertificate in sertificates"
        :key="sertificate.sertificate_id"
        class="sertificate-card"
        :class="{ 'is-updating': filesToUpdate[sertificate.sertificate_id] }"
        draggable="true"
        @dragstart="onDragStart(sertificate.sertificate_id)"
        @dragover.prevent
        @drop="onDrop(sertificate.sertificate_id)"
      >
        <div
          class="image-container"
          @click="triggerFileSelect(sertificate.sertificate_id)"
        >
          <iframe
            v-if="
              filesToUpdate[sertificate.sertificate_id]?.type ===
                'application/pdf' || sertificate.image_path?.endsWith('.pdf')
            "
            :src="
              previewsForUpdate[sertificate.sertificate_id] ||
              sertificate.image_path
            "
            class="sertificate-pdf-preview"
            frameborder="0"
          ></iframe>
          <img
            v-else
            :src="
              previewsForUpdate[sertificate.sertificate_id] ||
              sertificate.image_path
            "
            alt="Сертификат"
            class="sertificate-image"
          />
          <div class="overlay-edit">
            <span class="icon-edit">✎</span>
            <span>Заменить</span>
          </div>
        </div>
        <input
          type="file"
          accept="application/pdf"
          class="hidden-file-input"
          :ref="(el) => (fileInputs[`existing-sertificate-${sertificate.sertificate_id}`] = el as HTMLInputElement)"
          @change="onFileChangeForExisting($event, sertificate.sertificate_id)"
        />

        <div
          v-if="filesToUpdate[sertificate.sertificate_id]"
          class="actions-update"
        >
          <MyBtn
            variant="primary"
            class="btn-save"
            @click="handleUpdate(sertificate.sertificate_id)"
          >
            Сохранить
          </MyBtn>
          <MyBtn
            variant="secondary"
            class="btn-cancel"
            @click="cancelUpdate(sertificate.sertificate_id)"
          >
            Отмена
          </MyBtn>
        </div>
        <div v-else class="drag-handle">⠿</div>

        <button
          class="btn-delete"
          @click="handleDelete(sertificate.sertificate_id)"
        >
          ×
        </button>
      </div>

      <!-- Слоты для новых сертификатов -->
      <div
        v-for="(slot, index) in newSlots"
        :key="slot.tempId"
        class="sertificate-card sertificate-card--new"
      >
        <div class="image-uploader">
          <input
            type="file"
            accept="application/pdf"
            class="hidden-file-input"
            :id="`file-input-new-sertificate-${slot.tempId}`"
            :ref="
              (el) =>
                (fileInputs[`new-sertificate-${slot.tempId}`] =
                  el as HTMLInputElement)
            "
            @change="onFileChangeForNew($event, slot)"
          />
          <div v-if="slot.preview" class="image-preview-wrapper">
            <iframe
              v-if="slot.file?.type === 'application/pdf'"
              :src="slot.preview"
              class="sertificate-pdf-preview"
              frameborder="0"
            ></iframe>
            <img
              v-else
              :src="slot.preview"
              alt="preview"
              class="sertificate-image"
            />
            <button class="btn-remove-image" @click="clearImageInNewSlot(slot)">
              ×
            </button>
          </div>
          <label
            v-else
            :for="`file-input-new-sertificate-${slot.tempId}`"
            class="image-uploader-placeholder"
          >
            <div class="image-uploader-placeholder-content">
              <span class="download-icon"
                >Кликните сюда для загрузки сертификата</span
              >
              <span class="download-icon"
                >Загрузите можно только в формате PDF, но не более 10 МБ</span
              >
            </div>
          </label>
        </div>
        <div class="actions-new">
          <MyBtn
            variant="primary"
            class="btn-save"
            @click="handleCreate(slot, index)"
            :disabled="!slot.file"
          >
            Сохранить
          </MyBtn>
          <MyBtn
            variant="secondary"
            class="btn-delete-slot"
            @click="removeNewSlot(index)"
          >
            Удалить
          </MyBtn>
        </div>
      </div>

      <!-- Кнопка добавления -->
      <div class="sertificate-card add-new-card" @click="addNewSlot">
        <div class="add-new-placeholder">
          <span>+</span>
          <p>Добавить сертификат</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Общие стили контейнера и заголовков */
.container-sertificates {
  background-color: inherit;
  color: #e0e0e0;
  padding: 2rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica,
    Arial, sans-serif;
  min-height: 100vh;
}
.main-title {
  font-size: 1.8rem;
  font-weight: 600;
  color: #fff;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #444;
}

/* Стили загрузки и ошибок */
.loading-overlay,
.error-message {
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

/* Сетка сертификатов */
.sertificates-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1.5rem;
}

.sertificate-card {
  position: relative;
  border-radius: 8px;
  overflow: hidden;
  background-color: #3c3c3c;
  border: 1px solid #444;
  transition: transform 0.2s, box-shadow 0.2s;
  aspect-ratio: 3 / 4;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.image-container {
  width: 100%;
  height: 100%;
  position: relative;
  cursor: pointer;
}

.sertificate-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: filter 0.3s;
}

.sertificate-pdf-preview {
  width: 100%;
  height: 100%;
  border: none;
  border-radius: 8px;
}

.image-container:hover .sertificate-image {
  filter: brightness(0.6);
}

.overlay-edit {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: white;
  background-color: rgba(0, 0, 0, 0.3);
  opacity: 0;
  transition: opacity 0.3s;
  pointer-events: none;
}
.image-container:hover .overlay-edit {
  opacity: 1;
}
.icon-edit {
  font-size: 2rem;
}

/* Состояние обновления */
.sertificate-card.is-updating {
  border-color: #007bff;
  box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
}

.actions-update {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  display: flex;
  gap: 0;
  padding: 0;
  z-index: 11;
}
.actions-update .btn-save,
.actions-update .btn-cancel {
  min-width: 0;
  flex-grow: 1;
  padding: 0.75rem;
  border: none;
  border-radius: 0;
  color: white;
  cursor: pointer;
  font-weight: bold;
  font-size: 0.9rem;
}
.actions-update .btn-save {
  background-color: #28a745;
}
.actions-update .btn-cancel {
  background-color: #6c757d;
}

/* Ручка для перетаскивания */
.drag-handle {
  position: absolute;
  top: 10px;
  left: 10px;
  font-size: 24px;
  color: rgba(255, 255, 255, 0.7);
  background-color: rgba(0, 0, 0, 0.4);
  border-radius: 50%;
  width: 30px;
  height: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10;
  cursor: grab;
  transition: color 0.3s;
}
.sertificate-card:hover .drag-handle {
  color: white;
}
.sertificate-card:active {
  cursor: grabbing;
}

/* Кнопка удаления */
.btn-delete {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 32px;
  height: 32px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 50%; /* Небольшое скругление вместо овала */
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 24px; /* Увеличенный крестик */
  line-height: 1;
  cursor: pointer;
  z-index: 10;
  opacity: 0;
  transition: opacity 0.2s, transform 0.2s;
  padding: 0;
}
.sertificate-card:hover .btn-delete {
  opacity: 1;
}

/* Карточка добавления нового */
.add-new-card {
  cursor: pointer;
  border: 2px dashed #555;
  background-color: #3a3a3a;
}
.add-new-card:hover {
  background-color: #4a4a4a;
  border-color: #777;
}
.add-new-placeholder {
  text-align: center;
  color: #888;
}
.add-new-placeholder span {
  font-size: 48px;
}
.add-new-placeholder p {
  margin-top: 0.5rem;
  font-weight: 500;
}

/* Стили для нового слота */
.sertificate-card--new {
  flex-direction: column;
  padding: 0; /* Убираем внутренние отступы, чтобы дочерние элементы могли занимать все пространство */
  gap: 0;
  cursor: default;
  justify-content: initial; /* Отменяем центрирование */
}
.image-uploader {
  width: 100%;
  flex-grow: 1;
  position: relative;
  height: calc(100% - 45px); /* Занимает всю высоту минус высота кнопок */
}
.image-uploader-placeholder {
  width: 100%;
  height: 100%;
  border-radius: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  border: 2px dashed #555;
  background-color: #3a3a3a;
}
.hidden-file-input {
  display: none;
}
.image-preview-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
}
.btn-remove-image {
  position: absolute;
  top: -10px;
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
  line-height: 1;
  cursor: pointer;
  z-index: 10;
}
.actions-new {
  display: flex;
  gap: 0;
  width: 100%;
  position: absolute; /* Позиционируем абсолютно */
  bottom: 0; /* Прижимаем к низу */
  left: 0;
  height: 45px; /* Задаем фиксированную высоту */
}
.btn-save,
.btn-delete-slot {
  min-width: 0;
  flex-grow: 1;
  padding: 0.5rem;
  border: none;
  border-radius: 4px;
  color: white;
  cursor: pointer;
  font-weight: bold;
  border-radius: 0;
}
.btn-save {
  background-color: #28a745;
}
.btn-save:disabled {
  background-color: #555;
  cursor: not-allowed;
}
.btn-delete-slot {
  background-color: #6c757d;
}

.download-icon {
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: 500;
  color: #777;
  text-align: center;
}
</style>
