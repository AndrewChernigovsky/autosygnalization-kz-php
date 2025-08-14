<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { storeToRefs } from 'pinia';
import MyBtn from '../UI/MyBtn.vue';
import useIntroSlideStore from '../../stores/introSlideStore';
import Swal from 'sweetalert2';
import type { IntroSlideData } from './interfaces/introSlideData';

const openAccardion = ref<Record<number, boolean>>({});
const openAdvantageAccardion = ref<Record<number, boolean>>({});
const videoPreviews = ref<Record<number, { video?: string; poster?: string }>>(
  {}
);
const filesToUpload = ref<Record<number, { video?: File; poster?: File }>>({});
const fileInputRefs = ref<Record<string, HTMLInputElement | null>>({});
const filesToDelete = ref<
  Record<number, { video?: boolean; poster?: boolean }>
>({});

const setFileInputRef = (el: any, itemId: number, type: 'video' | 'poster') => {
  if (el) {
    fileInputRefs.value[`${type}-${itemId}`] = el;
  }
};

const triggerFileInput = (itemId: number, type: 'video' | 'poster') => {
  fileInputRefs.value[`${type}-${itemId}`]?.click();
};

const togleAccardion = (id: number) => {
  openAccardion.value[id] = !openAccardion.value[id];
};

const togleAdvantageAccardion = (id: number) => {
  openAdvantageAccardion.value[id] = !openAdvantageAccardion.value[id];
};

const onFileChange = (
  event: Event,
  itemId: number,
  type: 'video' | 'poster'
) => {
  const input = event.target as HTMLInputElement;
  const file = input.files?.[0];

  if (!videoPreviews.value[itemId]) {
    videoPreviews.value[itemId] = {};
  }
  if (!filesToUpload.value[itemId]) {
    filesToUpload.value[itemId] = {};
  }

  if (!file) {
    videoPreviews.value[itemId][type] = undefined;
    filesToUpload.value[itemId][type] = undefined;
    return;
  }

  // Client-side validation
  if (type === 'video') {
    const max_video_size = 50 * 1024 * 1024; // 50 MB
    if (file.size > max_video_size) {
      Swal.fire(
        'Ошибка',
        'Размер видеофайла не должен превышать 50 МБ.',
        'error'
      );
      clearFile(itemId, type);
      return;
    }
  }
  if (type === 'poster') {
    const max_poster_size = 5 * 1024 * 1024; // 5 MB
    if (file.size > max_poster_size) {
      Swal.fire(
        'Ошибка',
        'Размер файла постера не должен превышать 5 МБ.',
        'error'
      );
      clearFile(itemId, type);
      return;
    }
  }

  filesToUpload.value[itemId][type] = file;
  videoPreviews.value[itemId][type] = URL.createObjectURL(file);
};

const clearFile = (itemId: number, type: 'video' | 'poster') => {
  const item = introSlideData.value.find((i) => i.id === itemId);
  if (!item) return;

  // Clear local preview and file selection
  if (videoPreviews.value[itemId]) {
    videoPreviews.value[itemId][type] = undefined;
  }
  if (filesToUpload.value[itemId]) {
    filesToUpload.value[itemId][type] = undefined;
  }

  // Clear existing path to hide preview
  if (type === 'video') {
    item.video_path = '';
    item.poster_path = ''; // Also clear poster if video is removed
  } else {
    item.poster_path = '';
  }

  // Mark for deletion on server
  if (!filesToDelete.value[itemId]) {
    filesToDelete.value[itemId] = {};
  }
  filesToDelete.value[itemId][type] = true;

  // Reset the file input
  const inputId = `${type}-input-${itemId}`;
  const input = document.getElementById(inputId) as HTMLInputElement;
  if (input) {
    input.value = '';
  }
};

const introSlideStore = useIntroSlideStore();
const { introSlideData, isLoading } = storeToRefs(introSlideStore);

const addAdvantage = (item: IntroSlideData) => {
  if (!Array.isArray(item.advantages)) {
    item.advantages = [];
  }
  item.advantages.push('');
};

const removeAdvantage = (item: IntroSlideData, index: number) => {
  item.advantages.splice(index, 1);
};

const handleSave = async (item: IntroSlideData) => {
  try {
    // Filter out empty advantages before saving
    const cleanedAdvantages = item.advantages.filter(
      (adv) => adv && adv.trim() !== ''
    );
    const itemToSave = { ...item, advantages: cleanedAdvantages };

    const files = filesToUpload.value[item.id] || {};
    const toDelete = filesToDelete.value[item.id] || {};

    await introSlideStore.updateIntroSlide(itemToSave, files, toDelete);

    // After successful save, clear temporary states and refresh data
    delete videoPreviews.value[item.id];
    delete filesToUpload.value[item.id];
    delete filesToDelete.value[item.id];
    await introSlideStore.getIntroSlideData();

    Swal.fire({
      toast: true,
      position: 'top',
      icon: 'success',
      title: 'Слайд успешно сохранен!',
      showConfirmButton: false,
      timer: 2000,
    });
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Ошибка сохранения',
      text:
        error instanceof Error ? error.message : 'Произошла неизвестная ошибка',
    });
  }
};

onMounted(() => {
  introSlideStore.getIntroSlideData();
});

// --- Transition Hooks for Accordion ---
const beforeEnter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = '0';
  htmlEl.style.opacity = '0';
  htmlEl.style.paddingTop = '0';
  htmlEl.style.paddingBottom = '0';
  htmlEl.style.marginTop = '0';
};
const enter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = `${htmlEl.scrollHeight}px`;
  htmlEl.style.opacity = '1';
  htmlEl.style.paddingTop = ''; // Reset to CSS value
  htmlEl.style.paddingBottom = ''; // Reset to CSS value
  htmlEl.style.marginTop = ''; // Reset to CSS value
};
const afterEnter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = '';
};
const beforeLeave = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = `${htmlEl.scrollHeight}px`;
};
const leave = (el: Element) => {
  const htmlEl = el as HTMLElement;
  // This forces the browser to recognize the height before setting it to 0
  getComputedStyle(htmlEl).height;
  requestAnimationFrame(() => {
    htmlEl.style.height = '0';
    htmlEl.style.opacity = '0';
    htmlEl.style.paddingTop = '0';
    htmlEl.style.paddingBottom = '0';
    htmlEl.style.marginTop = '0';
  });
};
</script>
<template>
  <div>
    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner"></div>
      <p>Сохранение данных, пожалуйста, подождите...</p>
      <p>Это может занять некоторое время при загрузке видео.</p>
    </div>
    <h1 class="main-title">Главный слайдер на главной странице</h1>
    <div v-if="introSlideData && introSlideData.length > 0">
      <form
        v-for="item in introSlideData"
        :key="item.id"
        class="main-item"
        :name="`intro-slide-${item.id}`"
      >
        <label class="main-item-label" :for="`intro-slide-${item.id}`"
          >{{ item.title }}
          <MyBtn variant="primary" @click="togleAccardion(item.id)">{{
            openAccardion[item.id] ? 'Скрыть' : 'Редактировать'
          }}</MyBtn>
        </label>
        <Transition
          name="accordion"
          @before-enter="beforeEnter"
          @enter="enter"
          @after-enter="afterEnter"
          @before-leave="beforeLeave"
          @leave="leave"
        >
          <div v-if="openAccardion[item.id]" class="main-item-content">
            <div class="main-item-inputs">
              <div class="main-item-input">
                <label class="main-item-input-label">Заголовок</label>
                <input type="text" v-model="item.title" />
              </div>
              <div class="main-item-input">
                <label class="main-item-input-label">Текст кнопки</label>
                <input type="text" v-model="item.button_text" />
              </div>
              <div class="main-item-input">
                <label class="main-item-input-label">Ссылка кнопки</label>
                <input type="text" v-model="item.button_link" />
              </div>
            </div>
            <div class="main-item-advantages">
              <div class="main-item-advantages-header">
                <label class="main-item-input-label">Преимущества</label>
                <MyBtn
                  variant="primary"
                  @click.prevent="togleAdvantageAccardion(item.id)"
                  >{{
                    openAdvantageAccardion[item.id] ? 'Скрыть' : 'Показать'
                  }}
                  преимущества</MyBtn
                >
              </div>
              <Transition
                name="accordion"
                @before-enter="beforeEnter"
                @enter="enter"
                @after-enter="afterEnter"
                @before-leave="beforeLeave"
                @leave="leave"
              >
                <div
                  class="advantages-content"
                  v-if="openAdvantageAccardion[item.id]"
                >
                  <div>
                    <div
                      v-if="item.advantages.length > 0"
                      class="advantages-list"
                    >
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
                        <MyBtn
                          variant="primary"
                          @click.prevent="removeAdvantage(item, advIndex)"
                          class="btn-remove"
                          >Удалить</MyBtn
                        >
                      </div>
                    </div>
                    <p v-else class="no-advantages">Сейчас нет преимуществ</p>
                  </div>

                  <div class="advantage-actions">
                    <MyBtn
                      variant="secondary"
                      @click.prevent="addAdvantage(item)"
                      class="btn-add"
                      >Добавить преимущество</MyBtn
                    >
                  </div>
                </div>
              </Transition>
            </div>

            <div class="media-uploads">
              <!-- Video Upload -->
              <div class="upload-group">
                <label>Видео для слайда:</label>
                <input
                  type="file"
                  class="hidden-file-input"
                  :ref="(el) => setFileInputRef(el, item.id, 'video')"
                  accept="video/mp4"
                  @change="onFileChange($event, item.id, 'video')"
                />
                <div class="uploader-preview video-uploader">
                  <video
                    v-if="videoPreviews[item.id]?.video || item.video_path"
                    :src="videoPreviews[item.id]?.video || item.video_path"
                    class="video-preview"
                    controls
                    muted
                  ></video>
                  <div v-else class="uploader-placeholder">
                    <span>Нет видео</span>
                  </div>
                </div>
                <div class="upload-actions">
                  <MyBtn
                    variant="secondary"
                    type="button"
                    @click="triggerFileInput(item.id, 'video')"
                    >Выбрать видео</MyBtn
                  >
                  <MyBtn
                    variant="primary"
                    type="button"
                    @click="clearFile(item.id, 'video')"
                    >Удалить</MyBtn
                  >
                </div>
              </div>

              <!-- Poster Upload -->
              <div class="upload-group">
                <label>Постер (если не выбрать, создастся из видео):</label>
                <input
                  type="file"
                  class="hidden-file-input"
                  :ref="(el) => setFileInputRef(el, item.id, 'poster')"
                  accept="image/*"
                  @change="onFileChange($event, item.id, 'poster')"
                />
                <div class="uploader-preview image-uploader">
                  <img
                    v-if="videoPreviews[item.id]?.poster || item.poster_path"
                    :src="videoPreviews[item.id]?.poster || item.poster_path"
                    alt="Постер"
                    class="image-preview"
                  />
                  <div v-else class="uploader-placeholder">
                    <span>Нет постера</span>
                  </div>
                </div>
                <div class="upload-actions">
                  <MyBtn
                    variant="secondary"
                    type="button"
                    @click="triggerFileInput(item.id, 'poster')"
                    >Выбрать постер</MyBtn
                  >
                  <MyBtn
                    variant="primary"
                    type="button"
                    @click="clearFile(item.id, 'poster')"
                    >Удалить</MyBtn
                  >
                </div>
              </div>
            </div>

            <MyBtn
              variant="secondary"
              @click.prevent="handleSave(item)"
              :disabled="isLoading"
              class="save-all-btn"
            >
              {{ isLoading ? 'Сохранение...' : 'Сохранить' }}
            </MyBtn>
            <!-- <p>Это position: {{ item.position }}</p> -->
            <!-- <p>Это created_at: {{ item.created_at }}</p> -->
          </div>
        </Transition>
      </form>
    </div>
  </div>
</template>

<style scoped>
.main-title {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 48px;
  font-weight: bold;
  margin-bottom: 20px;
}
.main-loading {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
}
.main-item {
  display: flex;
  flex-direction: column;
  border: 1px solid white;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 10px;
  gap: 20px;
}
.main-item-label {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 24px;
  font-weight: bold;
  padding: 3px;
}
.advantages {
  margin-top: 15px;
  margin-bottom: 15px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.advantage-item {
  display: flex;
  gap: 10px;
  align-items: center;
}
.btn-add {
  align-self: flex-start;
}
.btn-remove {
  padding: 8px 12px;
  font-size: 14px;
}
.media-uploads {
  display: flex;
  gap: 20px;
  margin: 20px 0;
}
.upload-group {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.upload-group > label {
  font-weight: bold;
}
.hidden-file-input {
  display: none;
}
.uploader-preview {
  width: 100%;
  aspect-ratio: 16 / 9;
  border: 2px dashed #555;
  border-radius: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #333;
  overflow: hidden;
}
.uploader-placeholder {
  color: #777;
}
.image-preview,
.video-preview {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.upload-actions {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.main-item-input {
  display: flex;
  gap: 10px;
  font-size: 24px;
}

.main-item-input > input {
  flex: 1;
  padding: 5px;
  border-radius: 5px;
  border: 1px solid #ccc;
  background-color: #333;
  color: white;
}
.main-item-input-label {
  min-width: 200px;
  font-weight: bold;
}
.main-item-inputs {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.main-item-advantages {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.main-item-content {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.main-item-advantages-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 24px;
  padding: 10px;
  padding-left: 0;
  padding-right: 0;
  border-top: 1px solid #444;
}
.advantages-content {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 15px;
  border: 1px solid #444;
  border-radius: 8px;
  background-color: black;
}
.advantages-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 15px;
}
.no-advantages {
  color: #888;
  font-style: italic;
  margin-bottom: 15px;
  text-align: center;
}
.advantage-actions {
  display: flex;
  justify-content: center;
  gap: 10px;
}
.advantage-item input {
  font-size: 24px;
  flex-grow: 1;
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ccc;
  background-color: #333;
  color: white;
}
.save-all-btn {
  align-self: center;
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  color: white;
  text-align: center;
}

.spinner {
  border: 8px solid #f3f3f3;
  border-top: 8px solid #3498db;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
  margin-bottom: 20px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.accordion-enter-active,
.accordion-leave-active {
  transition: all 0.4s ease-out;
  overflow: hidden;
}

.main-item-content {
  /* Add this for a smooth transition */
  transition: all 0.4s ease-out;
}
</style>
