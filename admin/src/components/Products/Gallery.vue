<template>
  <div class="gallery-manager">
    <h4>Галерея:</h4>
    <div class="gallery-images">
      <div
        v-for="(image, index) in product.gallery"
        :key="index + '-' + product.id + '-' + image"
        class="gallery-image"
        @click="
          !isImageUploading(product.id, index) &&
            emit('triggerFileUpload', product, index)
        "
      >
        <div v-if="isImageUploading(product.id, index)" class="loader-overlay">
          <Loader size="small" />
        </div>
        <img
          :src="image"
          alt="Product image"
          :class="{
            uploading: isImageUploading(product.id, index),
          }"
        />
        <button
          v-if="!isImageUploading(product.id, index)"
          class="btn-delete-img"
          @click.stop="emit('deleteImage', product, index)"
        ></button>
      </div>
      <div v-if="isImageUploading(product.id, null)" class="gallery-image">
        <div class="loader-overlay">
          <Loader size="small" />
        </div>
      </div>
      <div
        v-if="!isImageUploading(product.id, null)"
        class="gallery-upload-placeholder"
        @click="emit('triggerFileUpload', product, null)"
      >
        <span class="plus-icon">+</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { ProductI } from './interfaces/Products';
import Loader from '../../UI/Loader.vue';

defineProps<{
  product: ProductI;
  isImageUploading: (productId: string, index: number | null) => boolean;
}>();

const emit = defineEmits<{
  (e: 'deleteImage', product: ProductI, imageIndex: number): void;
  (e: 'triggerFileUpload', product: ProductI, imageIndex: number | null): void;
}>();
</script>

<script lang="ts">
export default {
  name: 'Gallery',
};
</script>

<style scoped>
.gallery-manager {
  border-top: 1px solid #555;
  padding-top: 15px;
}

.gallery-manager h4 {
  margin-top: 0;
  font-weight: bold;
}

.gallery-images {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-top: 10px;
}

.gallery-image,
.gallery-upload-placeholder {
  position: relative;
  width: 100px;
  height: 100px;
  border: 1px dashed #666;
  border-radius: 4px;
  cursor: pointer;
}

.gallery-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 4px;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 2;
}

.gallery-image img.uploading {
  opacity: 0.5;
}

.loader-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  height: 100%;
  background-color: transparent;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 4px;
  z-index: 1;
}

.gallery-upload-placeholder {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #555;
}

.gallery-upload-placeholder:hover {
  background-color: #666;
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
}

.btn-delete-img::before,
.btn-delete-img::after {
  content: '';
  position: absolute;
  width: 12px;
  height: 2px;
  background-color: white;
}

.btn-delete-img::before {
  transform: rotate(45deg);
}

.btn-delete-img::after {
  transform: rotate(-45deg);
}
</style>
