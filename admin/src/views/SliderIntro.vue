<script setup lang="ts">
import { ref, onMounted, onBeforeUpdate, defineAsyncComponent } from 'vue';

const UploadButton = defineAsyncComponent(
  () => import('../components/SliderIntro/UploadButton.vue')
);
const DeleteButton = defineAsyncComponent(
  () => import('../components/SliderIntro/DeleteButton.vue')
);
import { Container, Draggable } from 'vue-dndrop';
import { Navigation, Pagination } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

import { useSlides } from '../components/SliderIntro/functions/useSlides';
import { useDnD } from '../components/SliderIntro/functions/useDnD';
import { useVideo } from '../components/SliderIntro/functions/useVideo';
import { usePoster } from '../components/SliderIntro/functions/usePoster';

const {
  items,
  fetchSlides,
  addSlide,
  removeSlide,
  updateSlide,
  addAdvantage,
  removeAdvantage,
} = useSlides();

const uploadButtonRef = ref<any[]>([]);
onBeforeUpdate(() => {
  uploadButtonRef.value = [];
});

const swiperInstance = ref<any>(null);
const currentSlideIndex = ref<number>(0);

const { onDrop, saveOrder } = useDnD(items, swiperInstance);
const {
  videoPreview,
  uploadProgress,
  formData,
  handleUploadSuccess,
  handleStatusUpdate,
  handleProgressUpdate,
  handleVideoPreview,
  handleVideoDeleted,
} = useVideo(items, currentSlideIndex, uploadButtonRef);

const {
  handlePosterUpload: posterUploader,
  handlePosterDeleted: posterDeleter,
  isDeleting: isPosterDeleting,
} = usePoster(items);

const posterFileNames = ref<Record<string, string | null>>({});

async function handlePosterUpload(event: Event, slideId: number | undefined) {
  const input = event.target as HTMLInputElement;
  if (slideId && input.files && input.files[0]) {
    posterFileNames.value[slideId] = input.files[0].name;
  }
  await posterUploader(event, slideId);
}

async function handlePosterDeleted(slideId: number | undefined) {
  if (slideId) {
    posterFileNames.value[slideId] = null;
  }
  await posterDeleter(slideId);
}

function onSwiper(swiper: any) {
  swiperInstance.value = swiper;
}

function handleSlideChange(swiper: any) {
  currentSlideIndex.value = swiper.activeIndex;
  const slide = items.value[currentSlideIndex.value];
  if (slide) {
    videoPreview.value = slide.video_path || null;
  }
}

onMounted(async () => {
  await fetchSlides();
  if (items.value.length > 0) {
    const slide = items.value[0];
    if (slide) {
      videoPreview.value = slide.video_path || null;
    }
    items.value.forEach((item) => {
      if (item.id && item.poster_path) {
        posterFileNames.value[item.id] =
          item.poster_path.split('/').pop() || null;
      }
    });
  }
});
</script>

<template>
  <div class="slider-intro">
    <h1>Главный слайдер на главной странице</h1>

    <h2>Порядок слайдов</h2>
    <Container @drop="onDrop">
      <Draggable v-for="item in items" :key="item.id">
        <div class="slide-item">
          <span class="drag-handle">☰</span>
          <span>{{ item.title }} - Позиция: {{ item.position }}</span>
        </div>
      </Draggable>
    </Container>
    <button @click="saveOrder" class="save-order-btn">Сохранить порядок</button>
    <hr />

    <Swiper
      :modules="[Navigation, Pagination]"
      :slides-per-view="1"
      :space-between="30"
      :pagination="{ clickable: true }"
      :navigation="true"
      class="swiper-container"
      @slide-change="handleSlideChange"
      @swiper="onSwiper"
    >
      <SwiperSlide v-for="(item, index) in items" :key="item.id || index">
        <div class="video-upload">
          <label>Загрузить видео для слайда {{ index + 1 }}:</label>
          <div class="preview-video">
            <div
              v-if="videoPreview && currentSlideIndex === index"
              class="video-preview"
            >
              <video
                :src="videoPreview"
                controls
                width="300"
                height="auto"
              ></video>
              <DeleteButton
                :video-id="item.id"
                @deleted="handleVideoDeleted"
                @status-update="handleStatusUpdate"
              />
            </div>
            <div v-else-if="item.video_path" class="video-preview">
              <video
                :src="item.video_path"
                controls
                width="300"
                height="auto"
              ></video>
              <DeleteButton
                :video-id="item.id"
                @deleted="handleVideoDeleted"
                @status-update="handleStatusUpdate"
              />
            </div>
            <div v-else class="no-video">
              <div class="no-video-placeholder"></div>
            </div>
          </div>
          <UploadButton
            :ref="
              (el) => {
                if (el) uploadButtonRef[index] = el;
              }
            "
            :slide-id="item.id"
            :title="item.title"
            :advantages="item.advantages"
            :button-text="item.button_text"
            :button-link="item.link"
            :form-data="formData"
            @upload-success="handleUploadSuccess"
            @status-update="handleStatusUpdate"
            @progress-update="handleProgressUpdate"
            @video-preview="handleVideoPreview"
          />
          <div class="upload-status">
            <div
              class="progress-bar"
              v-if="uploadProgress > 0 && uploadProgress < 100"
            >
              <div
                class="progress"
                :style="{ width: uploadProgress + '%' }"
              ></div>
            </div>
          </div>
        </div>

        <div class="poster-upload">
          <label>Постер для слайда {{ index + 1 }}:</label>
          <div class="preview-poster">
            <img
              v-if="item.poster_path"
              :src="item.poster_path"
              alt="Постер"
              width="300"
            />
            <div v-else class="no-poster-placeholder"></div>
          </div>
          <span
            v-if="item.id !== undefined && posterFileNames[item.id]"
            class="file-name"
            >{{ posterFileNames[item.id] }}</span
          >
          <div class="wrapper-upload">
            <label class="upload-label">
              <span class="upload-label-text">Выбрать постер</span>
              <input
                type="file"
                accept="image/*"
                @change="handlePosterUpload($event, item.id)"
                class="upload-input"
              />
            </label>

            <button
              v-if="item.poster_path"
              @click="handlePosterDeleted(item.id)"
              class="remove-slide-btn poster-delete-btn"
              :disabled="item.id !== undefined && isPosterDeleting[item.id]"
            >
              {{
                item.id !== undefined && isPosterDeleting[item.id]
                  ? 'Удаление...'
                  : 'Удалить постер'
              }}
            </button>
          </div>
        </div>

        <div class="title-input">
          <label>Заголовок слайда {{ index + 1 }}:</label>
          <input
            type="text"
            v-model="item.title"
            placeholder="Введите заголовок"
          />
        </div>

        <div class="advantages-list">
          <h3>Преимущества слайда {{ index + 1 }}:</h3>
          <div
            v-for="(advantage, advIndex) in item.advantages"
            :key="advIndex"
            class="advantage-item"
          >
            <input
              type="text"
              v-model="item.advantages[advIndex]"
              placeholder="Введите преимущество"
            />
            <button @click="removeAdvantage(item, advIndex)" class="remove-btn">
              Удалить
            </button>
          </div>
          <button @click="addAdvantage(item)" class="add-btn">
            Добавить преимущество
          </button>
        </div>

        <div class="button-config">
          <div>
            <label>Текст кнопки:</label>
            <input
              type="text"
              v-model="item.button_text"
              placeholder="Введите текст кнопки"
            />
          </div>
          <div>
            <label>Ссылка кнопки:</label>
            <input
              type="text"
              v-model="item.link"
              placeholder="Введите ссылку"
            />
          </div>
        </div>

        <div class="buttons-container">
          <button
            @click="item.id ? updateSlide(item) : () => {}"
            class="send-btn"
            :disabled="!item.id"
          >
            Обновить \ Сохранить
          </button>
          <button
            @click="removeSlide(item.id, index)"
            class="remove-slide-btn"
            :disabled="items.length === 1"
          >
            Удалить
          </button>
        </div>
      </SwiperSlide>
    </Swiper>
    <button @click="addSlide" class="add-slide-btn">Добавить слайд</button>
  </div>
</template>

<style scoped lang="scss">
.slider-intro {
  padding: 20px;
  max-width: 800px;
  margin: 0 auto;
}

.slide-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px;
  color: #000;
  background-color: #f0f0f0;
  border: 1px solid #ccc;
  margin-bottom: 5px;
  cursor: grab;
}

.drag-handle {
  font-size: 20px;
  cursor: grab;
}

.swiper-container {
  width: 100%;
  height: auto;
  padding-bottom: 50px;
  position: relative;
}

.swiper-slide {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
  width: 100%;
}

.video-upload {
  margin-bottom: 20px;
}

.preview-video {
  margin-bottom: 10px;
}

.video-preview {
  margin-top: 10px;
  position: relative;
}

.no-video {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 300px;
  height: 169px;
  background-color: #e0e0e0;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.no-video-placeholder {
  width: 100%;
  height: 100%;
  background-color: #808080;
}

.upload-status {
  margin-top: 10px;
}

.progress-bar {
  width: 100%;
  background-color: #e0e0e0;
  border-radius: 5px;
  overflow: hidden;
}

.progress {
  height: 20px;
  background-color: #007bff;
  transition: width 0.3s ease;
}

.title-input {
  margin-bottom: 20px;
}

.advantages-list {
  margin-bottom: 20px;
}

.advantage-item {
  display: flex;
  gap: 10px;
  margin-bottom: 10px;
}

.send-btn,
.save-order-btn,
.add-slide-btn,
.add-btn,
.remove-btn {
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.remove-btn {
  background-color: #6c757d;
  font-size: 14px;
  padding: 8px 12px;
  margin-top: 0;
}

.buttons-container {
  display: flex;
  gap: 10px;
}

.remove-slide-btn {
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #dc3545;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.poster-delete-btn {
  margin-top: 10px;
}

.wrapper-upload {
  display: flex;
  align-items: center;
  gap: 15px;

  .upload-label,
  .remove-slide-btn {
    margin: 0;
  }
}

.poster-upload {
  margin-bottom: 20px;
}

.preview-poster {
  margin-bottom: 10px;
  width: 300px;
  min-height: 150px;
  border: 1px solid #ccc;
  display: flex;
  justify-content: center;
  align-items: center;
}

.no-poster-placeholder {
  width: 100%;
  height: 150px;
  background-color: #e0e0e0;
}

.upload-input {
  display: none;
}

.upload-label {
  display: inline-block;
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  text-align: center;
  margin-bottom: 10px;
}

.file-name {
  margin-top: 5px;
  font-size: 14px;
  color: #333;
}

.button-config {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

input {
  padding: 8px;
  width: 100%;
  box-sizing: border-box;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.swiper-pagination {
  position: absolute;
  bottom: 20px;
  left: 0;
  width: 100%;
}

.swiper-button-prev,
.swiper-button-next {
  position: relative;
  top: auto;
  width: 40px;
  height: 40px;
  margin-top: 10px;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
</style>
