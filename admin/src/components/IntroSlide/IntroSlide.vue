<script setup lang="ts">
import { onMounted, ref, watchEffect } from 'vue';
import { storeToRefs } from 'pinia';
import MyBtn from '../UI/MyBtn.vue';
import DraggableList from '../UI/DraggableList.vue';
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
const editableItemData = ref<Record<number, IntroSlideData>>({});
const newSlide = ref<IntroSlideData | null>(null);
const isAddingNewSlide = ref(false);
const loadingAction = ref<'loading' | 'saving' | 'deleting' | 'reordering'>(
  'loading'
);

const setFileInputRef = (el: any, itemId: number, type: 'video' | 'poster') => {
  if (el) {
    fileInputRefs.value[`${type}-${itemId}`] = el;
  }
};

const triggerFileInput = (itemId: number, type: 'video' | 'poster') => {
  fileInputRefs.value[`${type}-${itemId}`]?.click();
};

const resetFileInput = (itemId: number, type: 'video' | 'poster') => {
  const inputRef = fileInputRefs.value[`${type}-${itemId}`];
  if (inputRef) {
    inputRef.value = '';
  }
};

const togleAccardion = (id: number) => {
  openAccardion.value[id] = !openAccardion.value[id];

  if (openAccardion.value[id]) {
    // When opening, create a deep copy for editing.
    const originalItem = introSlideData.value.find((i) => i.id === id);
    if (originalItem) {
      editableItemData.value[id] = JSON.parse(JSON.stringify(originalItem));
    }
  } else {
    // When closing, remove the copy to discard changes.
    delete editableItemData.value[id];
  }
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
      resetFileInput(itemId, type);
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
      resetFileInput(itemId, type);
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
  } else {
    item.poster_path = '';
  }

  // Mark for deletion on server
  if (!filesToDelete.value[itemId]) {
    filesToDelete.value[itemId] = {};
  }
  filesToDelete.value[itemId][type] = true;

  // Reset the file input
  const inputRef = fileInputRefs.value[`${type}-${itemId}`];
  if (inputRef) {
    inputRef.value = '';
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
    if (
      !item.title ||
      !item.button_text ||
      !item.button_link ||
      item.title.trim() === '' ||
      item.button_text.trim() === '' ||
      item.button_link.trim() === ''
    ) {
      Swal.fire({
        icon: 'error',
        title: 'Ошибка валидации',
        text: 'Поля "Заголовок", "Текст кнопки" и "Ссылка кнопки" не могут быть пустыми.',
      });
      return;
    }
    // Filter out empty advantages before saving
    const cleanedAdvantages = item.advantages.filter(
      (adv) => adv && adv.trim() !== ''
    );
    const itemToSave = { ...item, advantages: cleanedAdvantages };

    const files = filesToUpload.value[item.id] || {};
    const toDelete = filesToDelete.value[item.id] || {};

    loadingAction.value = 'saving';
    await introSlideStore.updateIntroSlide(itemToSave, files, toDelete);

    // After successful save, clear temporary states and refresh data
    openAccardion.value[item.id] = false;
    delete editableItemData.value[item.id];
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

const prepareNewSlide = () => {
  newSlide.value = {
    id: 0, // Temporary ID for a new slide
    title: 'Новый слайд',
    button_text: 'Кнопка',
    button_link: '#',
    advantages: ['Преимущество 1'],
    video_path: '',
    poster_path: '',
    video_path_mob: '',
    video_filename: '',
    position: 0,
    created_at: '',
    updated_at: '',
  };
  isAddingNewSlide.value = true;
};

const cancelNewSlide = () => {
  newSlide.value = null;
  isAddingNewSlide.value = false;
  delete filesToUpload.value[0];
  delete videoPreviews.value[0];
};

const handleSaveNewSlide = async () => {
  if (!newSlide.value) return;

  if (
    !newSlide.value.title ||
    !newSlide.value.button_text ||
    !newSlide.value.button_link ||
    newSlide.value.title.trim() === '' ||
    newSlide.value.button_text.trim() === '' ||
    newSlide.value.button_link.trim() === ''
  ) {
    Swal.fire({
      icon: 'error',
      title: 'Ошибка валидации',
      text: 'Поля "Заголовок", "Текст кнопки" и "Ссылка кнопки" не могут быть пустыми.',
    });
    return;
  }

  const cleanedAdvantages = newSlide.value.advantages.filter(
    (adv) => adv && adv.trim() !== ''
  );
  const slideToCreate = { ...newSlide.value, advantages: cleanedAdvantages };
  const files = filesToUpload.value[0] || {};

  try {
    loadingAction.value = 'saving';
    await introSlideStore.createIntroSlide(slideToCreate, files);
    Swal.fire({
      toast: true,
      position: 'top',
      icon: 'success',
      title: 'Новый слайд успешно сохранен!',
      showConfirmButton: false,
      timer: 2000,
    });
    cancelNewSlide();
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Ошибка сохранения нового слайда',
      text:
        error instanceof Error ? error.message : 'Произошла неизвестная ошибка',
    });
  }
};

const handleDeleteSlide = async (slideId: number) => {
  Swal.fire({
    title: 'Вы уверены?',
    text: 'Вы не сможете восстановить этот слайд после удаления!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        loadingAction.value = 'deleting';
        await introSlideStore.deleteIntroSlide(slideId);
        Swal.fire('Удалено!', 'Слайд был успешно удален.', 'success');
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Ошибка удаления',
          text:
            error instanceof Error
              ? error.message
              : 'Произошла неизвестная ошибка',
        });
      }
    }
  });
};

const reorderSlides = async (reorderedSlides: IntroSlideData[]) => {
  try {
    const updateData = reorderedSlides.map((slide, index) => ({
      id: slide.id,
      position: index + 1,
    }));

    loadingAction.value = 'reordering';
    await introSlideStore.updateSlideOrder(updateData);

    Swal.fire({
      toast: true,
      position: 'top',
      icon: 'success',
      title: 'Порядок слайдов обновлен!',
      showConfirmButton: false,
      timer: 1500,
    });
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Ошибка обновления порядка',
      text:
        error instanceof Error ? error.message : 'Произошла неизвестная ошибка',
    });
    // Optional: revert local changes on error
    await introSlideStore.getIntroSlideData();
  }
};

onMounted(() => {
  loadingAction.value = 'loading';
  introSlideStore.getIntroSlideData();
});

watchEffect(() => {
  if (isLoading.value) {
    let title = '';
    let html = '';
    switch (loadingAction.value) {
      case 'saving':
        title = 'Сохранение данных';
        html =
          'Пожалуйста, подождите...<br>Это может занять некоторое время при загрузке видео.';
        break;
      case 'deleting':
        title = 'Удаление слайда';
        html = 'Пожалуйста, подождите...';
        break;
      case 'reordering':
        title = 'Обновление порядка';
        html = 'Пожалуйста, подождите...';
        break;
      default:
        title = 'Загрузка данных';
        html = 'Пожалуйста, подождите...';
    }
    Swal.fire({
      title: title,
      html: html,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  } else {
    Swal.close();
  }
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
    <h1 class="main-title">Главный слайдер на главной странице</h1>
    <div class="add-new-item-bar">
      <span>Добавить новый слайд</span>
      <MyBtn
        variant="primary"
        @click="prepareNewSlide"
        :disabled="isAddingNewSlide"
        >Добавить</MyBtn
      >
    </div>

    <!-- Form for new slide -->
    <form
      v-if="newSlide"
      class="main-item new-slide-form"
      name="intro-slide-new"
    >
      <div class="main-item-content">
        <div class="main-item-inputs">
          <div class="main-item-input">
            <label class="main-item-input-label">Заголовок</label>
            <input
              class="main-item-input-field"
              type="text"
              v-model="newSlide.title"
            />
          </div>
          <div class="main-item-input">
            <label class="main-item-input-label">Текст кнопки</label>
            <input
              class="main-item-input-field"
              type="text"
              v-model="newSlide.button_text"
            />
          </div>
          <div class="main-item-input">
            <label class="main-item-input-label">Ссылка кнопки</label>
            <input
              class="main-item-input-field"
              type="text"
              v-model="newSlide.button_link"
            />
          </div>
        </div>

        <div class="main-item-advantages">
          <div class="main-item-advantages-header">
            <label class="main-item-input-label">Преимущества</label>
          </div>
          <div class="advantages-content">
            <div>
              <div
                v-if="newSlide.advantages.length > 0"
                class="advantages-list"
              >
                <div
                  v-for="(advantage, advIndex) in newSlide.advantages"
                  :key="advIndex"
                  class="advantage-item"
                >
                  <input
                    class="input-advantage"
                    type="text"
                    v-model="newSlide.advantages[advIndex]"
                    placeholder="Введите преимущество"
                  />
                  <MyBtn
                    variant="primary"
                    @click.prevent="removeAdvantage(newSlide, advIndex)"
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
                @click.prevent="addAdvantage(newSlide)"
                class="btn-add"
                >Добавить преимущество</MyBtn
              >
            </div>
          </div>
        </div>

        <div class="media-uploads">
          <!-- Video Upload for new slide -->
          <div class="upload-group">
            <label>Видео для слайда (не более ~50 МБ, формат mp4):</label>
            <input
              type="file"
              class="hidden-file-input"
              :ref="(el) => setFileInputRef(el, 0, 'video')"
              accept="video/mp4"
              @change="onFileChange($event, 0, 'video')"
            />
            <div class="uploader-preview video-uploader">
              <video
                v-if="videoPreviews[0]?.video"
                :src="videoPreviews[0]?.video"
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
                @click="triggerFileInput(0, 'video')"
                >Выбрать видео</MyBtn
              >
              <MyBtn
                variant="primary"
                type="button"
                @click="clearFile(0, 'video')"
                >Удалить видео</MyBtn
              >
            </div>
          </div>

          <!-- Poster Upload for new slide -->
          <div class="upload-group">
            <label>
              Постер (не более ~5 МБ, формат jpg, если не выбрать, создастся из
              видео):
            </label>
            <input
              type="file"
              class="hidden-file-input"
              :ref="(el) => setFileInputRef(el, 0, 'poster')"
              accept="image/*"
              @change="onFileChange($event, 0, 'poster')"
            />
            <div class="uploader-preview image-uploader">
              <img
                v-if="videoPreviews[0]?.poster"
                :src="videoPreviews[0]?.poster"
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
                @click="triggerFileInput(0, 'poster')"
                >Выбрать постер</MyBtn
              >
              <MyBtn
                variant="primary"
                type="button"
                @click="clearFile(0, 'poster')"
                >Удалить постер</MyBtn
              >
            </div>
          </div>
        </div>

        <div class="form-actions">
          <MyBtn
            variant="secondary"
            @click.prevent="handleSaveNewSlide"
            :disabled="isLoading"
            class="save-all-btn"
          >
            {{ isLoading ? 'Сохранение...' : 'Сохранить новый слайд' }}
          </MyBtn>
          <MyBtn
            variant="primary"
            @click.prevent="cancelNewSlide"
            :disabled="isLoading"
            class="delete-slide-btn"
            >Отменить создание слайда</MyBtn
          >
        </div>
      </div>
    </form>

    <DraggableList
      v-if="introSlideData && introSlideData.length > 0"
      v-model="introSlideData"
      item-key="id"
      class="slides-list"
      tag="div"
      @reorder="reorderSlides"
    >
      <template #item="{ item, dragHandleProps, isDragOver }">
        <form
          class="main-item"
          :name="`intro-slide-${item.id}`"
          :class="{ 'drag-over': isDragOver }"
        >
          <label class="main-item-label" :for="`intro-slide-${item.id}`">
            <div class="main-item-label-content">
              <div class="drag-handle" v-bind="dragHandleProps">
                <img
                  style="display: block"
                  src="./../../assets/d-and-d.svg"
                  alt="Перетащить"
                  width="40"
                  height="40"
                />
              </div>
              {{ item.title }}
            </div>

            <MyBtn variant="primary" @click.prevent="togleAccardion(item.id)">{{
              openAccardion[item.id] ? 'Закрыть' : 'Редактировать'
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
            <div
              v-if="openAccardion[item.id] && editableItemData[item.id]"
              class="main-item-content"
              data-drag-preview-hide="true"
            >
              <div class="main-item-inputs">
                <div class="main-item-input">
                  <label class="main-item-input-label">Заголовок</label>
                  <input
                    class="main-item-input-field"
                    type="text"
                    v-model="editableItemData[item.id].title"
                  />
                </div>
                <div class="main-item-input">
                  <label class="main-item-input-label">Текст кнопки</label>
                  <input
                    class="main-item-input-field"
                    type="text"
                    v-model="editableItemData[item.id].button_text"
                  />
                </div>
                <div class="main-item-input">
                  <label class="main-item-input-label">Ссылка кнопки</label>
                  <input
                    class="main-item-input-field"
                    type="text"
                    v-model="editableItemData[item.id].button_link"
                  />
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
                        v-if="editableItemData[item.id].advantages.length > 0"
                        class="advantages-list"
                      >
                        <div
                          v-for="(advantage, advIndex) in editableItemData[
                            item.id
                          ].advantages"
                          :key="advIndex"
                          class="advantage-item"
                        >
                          <input
                            class="input-advantage"
                            type="text"
                            v-model="
                              editableItemData[item.id].advantages[advIndex]
                            "
                            placeholder="Введите преимущество"
                          />
                          <MyBtn
                            variant="primary"
                            @click.prevent="
                              removeAdvantage(
                                editableItemData[item.id],
                                advIndex
                              )
                            "
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
                        @click.prevent="addAdvantage(editableItemData[item.id])"
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
                  <label>Видео для слайда (не более ~50 МБ, формат mp4):</label>
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
                      >Удалить видео</MyBtn
                    >
                  </div>
                </div>

                <!-- Poster Upload -->
                <div class="upload-group">
                  <label>
                    Постер (не более ~5 МБ, формат jpg, если не выбрать,
                    создастся из видео):
                  </label>
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
                      >Удалить постер</MyBtn
                    >
                  </div>
                </div>
              </div>

              <div class="form-actions">
                <MyBtn
                  variant="secondary"
                  @click.prevent="handleSave(editableItemData[item.id])"
                  :disabled="isLoading"
                  class="save-all-btn"
                >
                  {{ isLoading ? 'Сохранение...' : 'Сохранить слайд' }}
                </MyBtn>
                <MyBtn
                  variant="primary"
                  @click.prevent="handleDeleteSlide(item.id)"
                  :disabled="isLoading"
                  class="delete-slide-btn"
                  >Удалить слайд</MyBtn
                >
              </div>
              <!-- <p>Это position: {{ item.position }}</p> -->
              <!-- <p>Это created_at: {{ item.created_at }}</p> -->
            </div>
          </Transition>
        </form>
      </template>
    </DraggableList>
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
.add-slide-actions {
  display: flex;
  justify-content: center;
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
  position: relative;
  display: flex;
  flex-direction: column;
  border: 1px solid white;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 10px;
  gap: 20px;
  transition: all 0.2s ease;
}
.drag-handle {
  cursor: grab;
  padding: 5px;
}
.drag-handle:active {
  cursor: grabbing;
}

.draggable:active {
  cursor: grabbing;
}
.main-item.dragging {
  opacity: 0.5;
  transform: scale(0.95);
}
.main-item.drag-over {
  border-color: #3498db;
}
.main-item-label-content {
  display: flex;
  align-items: center;
}
.main-item-label {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 24px;
  font-weight: bold;
  padding: 3px;
  padding-right: 10px;
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
  width: 100%;
  max-width: 100%;
  flex-grow: 1;
  padding: 20px 20px;
}
.btn-remove {
  padding: 8px 12px;
  font-size: 14px;
}
.media-uploads {
  display: flex;
  gap: 20px;
  margin: 20px 0;
  padding-bottom: 20px;
  border-bottom: 1px solid #444;
}
.upload-group {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 20px;
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
  padding-top: 20px;
  display: flex;
  justify-content: center;
  gap: 10px;
}

.main-item-input {
  display: flex;
  gap: 10px;
  font-size: 24px;
  padding: 15px;
}

.main-item-input-field {
  flex: 1;
  padding: 5px;
  border-radius: 5px;
  border: 1px solid #ccc;
  color: black;
  background-color: white;
  font-size: 24px;
  box-shadow: 0 0 0 5px #363535;
}

.main-item-input-label {
  min-width: 200px;
  font-weight: bold;
}
.main-item-inputs {
  display: flex;
  flex-direction: column;
  gap: 20px;
  padding-top: 10px;
  border-top: 1px solid #444;
}

.main-item-advantages {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding-bottom: 10px;
  border-bottom: 1px solid #444;
}

.main-item-content {
  display: flex;
  flex-direction: column;
  gap: 20px;
  padding: 15px;
  padding-top: 0;
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
  gap: 20px;
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
  padding: 5px;
  border-radius: 5px;
  border: 1px solid #ccc;
  color: black;
  background-color: white;
  box-shadow: 0 0 0 5px #363535;
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
.add-new-item-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border: 1px solid white;
  border-radius: 10px;
  margin-bottom: 20px;
  font-size: 26px;
  font-weight: bold;
}
.new-slide-form {
  border-color: #3498db;
}
.slides-list {
  /* Space for the drag handle */
  padding-top: 10px;
}

.form-actions {
  display: flex;
  justify-content: center;
  gap: 20px;
}

.delete-slide-btn,
.save-all-btn {
  align-self: center;
  width: 100%;
  max-width: 100%;
  flex-grow: 1;
  padding: 20px 20px;
}
</style>
