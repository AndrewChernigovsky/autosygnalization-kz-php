<script setup lang="ts">
import { ref } from 'vue';
import MyBtn from './MyBtn.vue';
import plus from '../../assets/input-file-plus.svg';

const props = defineProps<{
  width?: string;
  height?: string;
  accept?: string;
  imgPath?: string;
}>();

const emit = defineEmits<{
  fileChange: [file: File | null];
}>();

const imgPath = ref(props.imgPath);
const isLoading = ref(false);
const selectedFile = ref<File | null>(null);

const handleFileChange = async (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    isLoading.value = true;

    try {
      const imageUrl = URL.createObjectURL(file);

      await new Promise((resolve) => setTimeout(resolve, 500));

      imgPath.value = imageUrl;
      selectedFile.value = file;
      emit('fileChange', file);
    } catch (error) {
      console.error('Ошибка при загрузке файла:', error);
    } finally {
      isLoading.value = false;
    }
  }
};

const clearImage = (event: Event) => {
  event.stopPropagation();
  if (imgPath.value && imgPath.value !== plus) {
    URL.revokeObjectURL(imgPath.value);
  }
  imgPath.value = '';
  selectedFile.value = null;
  emit('fileChange', null);
};
</script>

<template>
  <div class="file-input-wrapper">
    <div v-if="isLoading" class="file-loader">
      <div class="spinner"></div>
    </div>

    <img
      :src="imgPath ? imgPath : plus"
      alt="image"
      :width="width"
      :height="height"
      class="file-input-image"
      :class="{ loading: isLoading }"
    />

    <MyBtn
      v-if="imgPath && !isLoading"
      class="my-input-delete"
      @click.stop="clearImage"
      >x</MyBtn
    >
    <input
      class="file-input"
      type="file"
      :accept="accept"
      @change="handleFileChange"
    />
  </div>
</template>

<style scoped>
.file-input-wrapper {
  position: relative;
  display: flex;
  cursor: pointer;
  background-color: #fff;
  padding: 10px;
  border-radius: 10px;
}

.file-input {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
  z-index: 1; /* Уменьшаем z-index чтобы кнопка была выше */
}

.file-input-image {
  width: 100%;
  height: 100%;
  display: block;
  transition: opacity 0.3s ease;
  pointer-events: none; /* Отключаем события мыши для изображения */
}

.file-input-image.loading {
  opacity: 0;
}

.file-loader {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 5;
}

.spinner {
  width: 60px;
  height: 60px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
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

.my-input-delete {
  transition: all 0.3s ease;
  position: absolute;
  top: 0px;
  right: 0px;
  min-width: 30px;
  max-width: 30px;
  height: 30px;
  background: linear-gradient(180deg, #280000 0%, #ff0000 100%);
  border-radius: 50%;
  border: none;
  padding: 0;
  font-size: 15px;
  line-height: 15px;
  text-transform: uppercase;
  color: #ffffff;
  cursor: pointer;
  z-index: 10;

  &:hover {
    opacity: 0.7;
  }

  &:active {
    opacity: 0.3;
  }
}
</style>
