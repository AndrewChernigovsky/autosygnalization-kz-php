<template>
  <div class="image-upload-wrapper">
    <label class="image-upload-box">
      <input ref="inputRef" type="file" accept="image/*" @change="handleImageUpload" class="upload-input" />
      <img v-if="imagePreview" :src="imagePreview" alt="Предпросмотр" class="image-preview-img" />
      <div v-else class="placeholder">
        <span class="plus-icon">+</span>
      </div>
    </label>
    <button v-if="imagePreview" class="btn-delete-img" @click.stop="clearInput"></button>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watchEffect } from 'vue';
import Swal from 'sweetalert2';

interface Props {
  imageId?: number;
  data?: {
    filename: string;
    path: string;
  } | null;
  path: string;
  serviceImage?: boolean;
  advantageImage?: boolean;
  extraData?: { [key: string]: string };
}

interface Emits {
  (
    event: 'upload-success',
    data: { id: number; filename: string; path: string }
  ): void;
  (event: 'status-update', status: string): void;
  (event: 'progress-update', progress: number): void;
  (event: 'image-preview', preview: string): void;
  (event: 'image-cleared'): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const imageFile = ref<File | null>(null);
const imageFileName = ref<string | null>(null);
const inputRef = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | null>(null);

watchEffect(() => {
  if (props.path) {
    imagePreview.value = props.path;
  } else {
    imagePreview.value = null;
  }
});

const endpointUrl = computed(() => {
  if (props.serviceImage) {
    return '/server/php/admin/api/services/upload_service_image.php';
  }
  if (props.advantageImage) {
    return '/server/php/admin/api/advantages/upload-advantage-image.php';
  }
  return '/server/php/admin/api/upload-image.php';
});

// Обработка загрузки видеофайла
function handleImageUpload(event: Event) {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const fileSize = file.size / 1024 / 1024; // в МБ
    const fileType = file.type;

    if (fileSize > 10) {
      Swal.fire({
        icon: 'error',
        title: 'Ошибка',
        text: 'Размер файла не должен превышать 10 МБ.',
      });
      if (input) input.value = '';
      imageFileName.value = null;
      return;
    }

    if (!fileType.startsWith('image/')) {
      Swal.fire({
        icon: 'error',
        title: 'Ошибка',
        text: 'Разрешен только изображение формат.',
      });
      if (input) input.value = '';
      imageFileName.value = null;
      return;
    }

    imageFile.value = file;
    imageFileName.value = file.name;
    const preview = URL.createObjectURL(imageFile.value);
    imagePreview.value = preview;
    emit('image-preview', preview);
    uploadImage();
  } else {
    imageFileName.value = null;
  }
}

// Отправка видео на сервер
function uploadImage() {
  if (!imageFile.value) return;

  emit('status-update', 'Загрузка...');
  emit('progress-update', 0);

  const formData = new FormData();
  formData.append('image', imageFile.value);
  formData.append('path', props.path);

  if (props.extraData) {
    for (const key in props.extraData) {
      formData.append(key, props.extraData[key]);
    }
  }

  const xhr = new XMLHttpRequest();
  xhr.open('POST', endpointUrl.value, true);

  xhr.upload.onprogress = function (event) {
    if (event.lengthComputable) {
      const progress = Math.round((event.loaded * 100) / event.total);
      emit('progress-update', progress);
    }
  };

  xhr.onload = function () {
    console.log('Response status:', xhr.status);
    console.log('Response text:', xhr.responseText);

    if (xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        emit('upload-success', {
          id: response.id,
          filename: response.filename,
          path: response.path,
        });
        emit('status-update', 'Загрузка завершена. ID записи: ' + response.id);
      } catch (e: any) {
        console.error('JSON parse error:', e);
        emit('status-update', 'Ошибка обработки ответа сервера: ' + e.message);
      }
    } else {
      emit('status-update', 'Ошибка загрузки: ' + xhr.statusText);
    }
  };

  xhr.onerror = function () {
    emit('status-update', 'Ошибка соединения с сервером');
  };

  xhr.send(formData);
}

// Метод для очистки input file (вызывается извне)
function clearInput() {
  if (inputRef.value) {
    inputRef.value.value = '';
  }
  imageFile.value = null;
  imageFileName.value = null;
  imagePreview.value = null;
  emit('image-cleared');
}

// Экспортируем методы для родительского компонента
defineExpose({
  clearInput,
});
</script>

<script lang="ts">
export default {
  name: 'ImageUpload',
};
</script>



<style scoped lang="scss">
.image-upload-wrapper {
  position: relative;
  width: 300px;
  height: 300px;
}

.image-upload-box {
  position: relative;
  width: 100%;
  height: 100%;
  border: 1px dashed #666;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  background-color: #555;
  transition: background-color 0.2s ease;

  &:hover {
    background-color: #666;
  }
}

.upload-input {
  display: none;
}

.image-preview-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.placeholder {
  display: flex;
  justify-content: center;
  align-items: center;
}

.plus-icon {
  font-size: 40px;
  color: #888;
}

.btn-delete-img {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  width: 24px;
  height: 24px;
  padding: 0;
  box-sizing: border-box;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;

  &::before,
  &::after {
    content: '';
    position: absolute;
    width: 12px;
    height: 2px;
    background-color: white;
  }

  &::before {
    transform: rotate(45deg);
  }

  &::after {
    transform: rotate(-45deg);
  }
}
</style>
