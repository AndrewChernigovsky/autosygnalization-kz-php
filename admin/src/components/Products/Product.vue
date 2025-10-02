<template>
  <div>
    <div class="product-item">
      <div class="product-summary" @click.prevent>
        <strong class="product-title">{{ displayProduct.title }}</strong>
        <MyBtn variant="primary" @click="handleButtonClick">
          {{ isOpen ? 'Закрыть' : 'Редактировать' }}</MyBtn>
      </div>
      <MyTransition>
        <div v-if="isOpen" class="product-editor">
          <!-- ... existing fields ... -->
          <div class="form-group">
            <label>Модель:</label>
            <input v-if="editingProduct && editingProduct.id" v-model="editingProduct.model" type="text" />
            <span v-else>{{ displayProduct.model }}</span>
          </div>

          <div class="form-group">
            <label>Заголовок:</label>
            <input v-if="editingProduct && editingProduct.id" v-model="editingProduct.title" type="text" />
            <span v-else>{{ displayProduct.title }}</span>
          </div>

          <div class="form-group">
            <label>Описание:</label>
            <textarea ref="descriptionTextarea" v-if="editingProduct && editingProduct.id"
              v-model="editingProduct.description" @input="handleDescriptionInput"></textarea>
            <span v-else>{{ displayProduct.description }}</span>
          </div>

          <div class="form-group">
            <label>Цена:</label>
            <input v-if="editingProduct && editingProduct.id" v-model="editingProduct.price" type="number" />
            <span v-else>{{ displayProduct.price }}</span>
          </div>

          <div class="form-group">
            <label :for="'popular-' + displayProduct.id">Популярный:</label>
            <input v-if="editingProduct && editingProduct.id" type="checkbox" :id="'popular-' + displayProduct.id"
              v-model="editingProduct.is_popular" />
            <span v-else>{{ displayProduct.is_popular ? 'Да' : 'Нет' }}</span>
          </div>

          <div class="form-group">
            <label :for="'special-' + displayProduct.id">Специальный:</label>
            <input v-if="editingProduct && editingProduct.id" type="checkbox" :id="'special-' + displayProduct.id"
              v-model="editingProduct.is_special" />
            <span v-else>{{ displayProduct.is_special ? 'Да' : 'Нет' }}</span>
          </div>
          <Gallery :product="displayProduct" :is-image-uploading="isImageUploading"
            @delete-image="(p, i) => emit('delete-image', p, i)"
            @trigger-file-upload="(p, i) => emit('trigger-file-upload', p, i)" />
          <Prices ref="pricesRef" :product="displayProduct" :is-editing="!!editingProduct" />
          <div class="form-group">
            <label>Категория:</label>
            <select v-if="editingProduct && editingProduct.id" v-model="editingProduct.category" @click.stop>
              <option v-for="category in allCategories" :value="category.key" :key="category.key">
                {{ category.name }}
              </option>
            </select>
            <span v-else>{{ getCategoryName(displayProduct.category) }}</span>
          </div>

          <div class="array-fields-editor">
            <div class="form-group-functions">
              <label class="form-group-checkbox-label">
                Функции
                <MyBtn variant="primary" @click="toggleCheckbox('functions')" class="btn-toggle-checkbox">{{
                  openCheckbox.functions ? 'Свернуть' : 'Развернуть'
                }}</MyBtn>
              </label>
              <MyTransition>
                <div class="functions-checkboxes accardion-checkboxes" v-if="openCheckbox.functions">
                  <div class="form-group-checkbox" v-if="openCheckbox.functions">
                    <input type="checkbox" :id="'autostart-' + displayProduct.id" :checked="hasFunction('Автозапуск')"
                      @change="toggleFunction('Автозапуск')" />
                    <label :for="'autostart-' + displayProduct.id">Автозапуск</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'engine-block-' + displayProduct.id"
                      :checked="hasFunction('БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN')"
                      @change="toggleFunction('БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN')" />
                    <label :for="'engine-block-' + displayProduct.id">БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'preheater-' + displayProduct.id" :checked="hasFunction('УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ')
                      " @change="
                        toggleFunction('УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ')
                        " />
                    <label :for="'preheater-' + displayProduct.id">УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'phone-control-' + displayProduct.id"
                      :checked="hasFunction('УПРАВЛЕНИЕ С ТЕЛЕФОНА')"
                      @change="toggleFunction('УПРАВЛЕНИЕ С ТЕЛЕФОНА')" />
                    <label :for="'phone-control-' + displayProduct.id">УПРАВЛЕНИЕ С ТЕЛЕФОНА</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'free-monitoring-' + displayProduct.id"
                      :checked="hasFunction('БЕСПЛАТНЫЙ МОНИТОРИНГ')"
                      @change="toggleFunction('БЕСПЛАТНЫЙ МОНИТОРИНГ')" />
                    <label :for="'free-monitoring-' + displayProduct.id">БЕСПЛАТНЫЙ МОНИТОРИНГ</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'bluetooth-auth-' + displayProduct.id" :checked="hasFunction('УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART')
                      " @change="
                        toggleFunction('УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART')
                        " />
                    <label :for="'bluetooth-auth-' + displayProduct.id">УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'smart-diagnostic-' + displayProduct.id"
                      :checked="hasFunction('УМНАЯ АВТОДИАГНОСТИКА')"
                      @change="toggleFunction('УМНАЯ АВТОДИАГНОСТИКА')" />
                    <label :for="'smart-diagnostic-' + displayProduct.id">УМНАЯ АВТОДИАГНОСТИКА</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'data-fuel-' + displayProduct.id" :checked="hasFunction('ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА')
                      " @change="
                        toggleFunction('ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА')
                        " />
                    <label :for="'data-fuel-' + displayProduct.id">ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА</label>
                  </div>
                </div>
              </MyTransition>
            </div>
            <div class="form-group-options">
              <label class="form-group-checkbox-label">
                Опции
                <MyBtn variant="primary" @click="toggleCheckbox('options')" class="btn-toggle-checkbox">{{
                  openCheckbox.options ? 'Свернуть' : 'Развернуть' }}</MyBtn>
              </label>
              <MyTransition>
                <div class="options-checkboxes" v-if="openCheckbox.options">
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'suv-' + displayProduct.id" :checked="hasOption('Для внедорожника')"
                      @change="toggleOption('Для внедорожника', 'vnedorojnik')" />
                    <label :for="'suv-' + displayProduct.id">Для внедорожника</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'car-' + displayProduct.id" :checked="hasOption('Для легкового авто')"
                      @change="
                        toggleOption('Для легкового авто', 'legkoe-avto')
                        " />
                    <label :for="'car-' + displayProduct.id">Для легкового авто</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'option-autosetup-' + displayProduct.id"
                      :checked="hasOptionFilter('autosetup')" @change="toggleOptionFilter('autosetup')" />
                    <label :for="'option-autosetup-' + displayProduct.id">Автозапуск</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'option-block-engine-can-' + displayProduct.id"
                      :checked="hasOptionFilter('block-engine-can')" @change="toggleOptionFilter('block-engine-can')" />
                    <label :for="'option-block-engine-can-' + displayProduct.id">Блокировка двигателя по CAN</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'option-control-before-start-' + displayProduct.id"
                      :checked="hasOptionFilter('control-before-start')"
                      @change="toggleOptionFilter('control-before-start')" />
                    <label :for="'option-control-before-start-' + displayProduct.id">Управление предпусковым
                      подогревом</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'option-control-phone-' + displayProduct.id"
                      :checked="hasOptionFilter('control-phone')" @change="toggleOptionFilter('control-phone')" />
                    <label :for="'option-control-phone-' + displayProduct.id">Управление с телефона</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'option-free-monitoring-' + displayProduct.id"
                      :checked="hasOptionFilter('free-monitoring')" @change="toggleOptionFilter('free-monitoring')" />
                    <label :for="'option-free-monitoring-' + displayProduct.id">Бесплатный мониторинг</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'option-bluetooth-smart-' + displayProduct.id"
                      :checked="hasOptionFilter('bluetooth-smart')" @change="toggleOptionFilter('bluetooth-smart')" />
                    <label :for="'option-bluetooth-smart-' + displayProduct.id">Умная авторизация по Bluetooth
                      Smart</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'option-smart-diagnostic-' + displayProduct.id"
                      :checked="hasOptionFilter('smart-diagnostic')" @change="toggleOptionFilter('smart-diagnostic')" />
                    <label :for="'option-smart-diagnostic-' + displayProduct.id">Умная автодиагностика</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'option-data-level-bensin-' + displayProduct.id"
                      :checked="hasOptionFilter('data-level-bensin')"
                      @change="toggleOptionFilter('data-level-bensin')" />
                    <label :for="'option-data-level-bensin-' + displayProduct.id">Данные о пробеге и уровне
                      топлива</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'option-for-park-systems-' + displayProduct.id"
                      :checked="hasOptionFilter('for-park-systems')" @change="toggleOptionFilter('for-park-systems')" />
                    <label :for="'option-for-park-systems-' + displayProduct.id">Для парктроников</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'option-remote-controls-' + displayProduct.id"
                      :checked="hasOptionFilter('remote-controls')" @change="toggleOptionFilter('remote-controls')" />
                    <label :for="'option-remote-controls-' + displayProduct.id">Пульты управления</label>
                  </div>
                </div>
              </MyTransition>
            </div>
            <div class="form-group-autosygnals">
              <label class="form-group-checkbox-label">
                Раздел для автосигнализаций
                <MyBtn variant="primary" @click="toggleCheckbox('autosygnals')" class="btn-toggle-checkbox">{{
                  openCheckbox.autosygnals ? 'Свернуть' : 'Развернуть'
                }}</MyBtn>
              </label>
              <MyTransition>
                <div class="autosygnals-checkboxes" v-if="openCheckbox.autosygnals">
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'autosygnals-without-auto-' + displayProduct.id"
                      :checked="hasAutosygnals('without-auto')" @change="toggleAutosygnals('without-auto')" />
                    <label :for="'autosygnals-without-auto-' + displayProduct.id">Без автозапуска</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'autosygnals-starline-' + displayProduct.id"
                      :checked="hasAutosygnals('starline')" @change="toggleAutosygnals('starline')" />
                    <label :for="'autosygnals-starline-' + displayProduct.id">Starline</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'autosygnals-auto-' + displayProduct.id"
                      :checked="hasAutosygnals('auto')" @change="toggleAutosygnals('auto')" />
                    <label :for="'autosygnals-auto-' + displayProduct.id">С автозапуском</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'autosygnals-gsm-' + displayProduct.id" :checked="hasAutosygnals('gsm')"
                      @change="toggleAutosygnals('gsm')" />
                    <label :for="'autosygnals-gsm-' + displayProduct.id">GSM модуль</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'autosygnals-for-park-systems-' + displayProduct.id"
                      :checked="hasAutosygnals('for-park-systems')" @change="toggleAutosygnals('for-park-systems')" />
                    <label :for="'autosygnals-for-park-systems-' + displayProduct.id">Для парктроников</label>
                  </div>
                  <div class="form-group-checkbox">
                    <input type="checkbox" :id="'autosygnals-remote-controls-' + displayProduct.id"
                      :checked="hasAutosygnals('remote-controls')" @change="toggleAutosygnals('remote-controls')" />
                    <label :for="'autosygnals-remote-controls-' + displayProduct.id">Пульты управления</label>
                  </div>
                </div>
              </MyTransition>
            </div>
            <div class="form-group-autosygnals">
              <label class="form-group-checkbox-label">
                Раздел для прайс-листа
                <MyBtn variant="primary" @click="toggleCheckbox('price_list')" class="btn-toggle-checkbox">{{
                  openCheckbox.price_list ? 'Свернуть' : 'Развернуть'
                }}</MyBtn>
              </label>
              <MyTransition>
                <div class="prices-checkboxes" v-if="openCheckbox.price_list">
                  <!-- <div class="form-group-checkbox">
                    <label>Название</label>
                    <p>{{ priceListRef.title }}</p>
                    <input type="text" v-model="priceListRef.title" />
                  </div> -->
                  <div class="form-group-checkbox">
                    <label>Стоимость установки</label>
                    <input type="text" v-model="priceListRef.price" />
                  </div>
                  <div class="form-group-checkbox">
                    <label>Описание</label>
                    <MyQuill v-if="editingProduct" v-model:content="priceListRef.content"
                      :value="priceListRef.content" />
                  </div>
                  <!-- <MyBtn variant="primary" @click="editPriceList(priceListRef)">Добавить</MyBtn>
                  <MyBtn variant="secondary" @click="editPriceList(priceListRef)">Обновить</MyBtn> -->
                </div>
              </MyTransition>
            </div>
          </div>
          <Tabs v-if="editingProduct" :product="editingProduct" @upload-icon="onUploadTabIcon"
            @delete-icon="onDeleteTabIcon" />

          <div class="product-actions">
            <MyBtn variant="secondary" @click="saveChanges" class="btn-save">
              Сохранить изменения
            </MyBtn>
            <MyBtn variant="primary" @click="emit('delete-product', product.id)" class="btn-delete">
              Удалить товар
            </MyBtn>
          </div>
        </div>
      </MyTransition>
    </div>
    <input type="file" ref="iconUploader" @change="onIconFileSelected" style="display: none" accept="image/*" />
  </div>
</template>

<script setup lang="ts">
import { ref, watch, nextTick, computed } from 'vue';
import type { ProductI } from './interfaces/Products';
import Gallery from './Gallery.vue';
import Tabs from './Tabs.vue';
import Prices from './Prices.vue';
import MyBtn from '../UI/MyBtn.vue';
import MyTransition from '../UI/MyTransition.vue';
import MyQuill from '../UI/MyQuill.vue';

defineOptions({
  name: 'Product',
});

const props = defineProps<{
  product: ProductI;
  allCategories: Array<{ key: string; name: string }>;
  isImageUploading: (productId: string, index: number | null) => boolean;
  getCategoryName: (key: string) => string;
  isAddingNewProduct: boolean;
}>();

const editingProduct = ref<ProductI | null>(null);

const openCheckbox = ref<Record<string, boolean>>({});

const isOpen = ref(false);
const descriptionTextarea = ref<HTMLTextAreaElement | null>(null);

const priceListRef = ref<{
  title: string;
  price: string;
  currency: string;
  content: string;
}>({
  title: props.product.price_list?.[0]?.title || '',
  price: props.product.price_list?.[0]?.price || '',
  currency: props.product.price_list?.[0]?.currency || '',
  content: props.product.price_list?.[0]?.content || '',
});

const displayProduct = computed(() => {
  return editingProduct.value ?? props.product;
});

watch(() => props.product.price_list, (newVal) => {
  priceListRef.value = {
    title: newVal?.[0]?.title || '',
    price: newVal?.[0]?.price || '',
    currency: newVal?.[0]?.currency || '',
    content: newVal?.[0]?.content || '',
  };
  editPriceList(priceListRef.value);
}, { deep: true, immediate: true });

const handleButtonClick = () => {
  isOpen.value = !isOpen.value;
};

const toggleCheckbox = (key: string) => {
  openCheckbox.value[key] = !openCheckbox.value[key];
};

watch(isOpen, (newValue) => {
  if (newValue) {
    editingProduct.value = JSON.parse(JSON.stringify(props.product));
  } else {
    editingProduct.value = null;
    emit('cancel-editing', props.product);
  }
});

watch(
  () => props.product.is_new,
  (isNew) => {
    if (isNew && props.isAddingNewProduct) {
      isOpen.value = true;
    }
  },
  { immediate: true }
);

const handleDescriptionInput = () => {
  const textarea = descriptionTextarea.value;
  if (textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = `${textarea.scrollHeight}px`;
  }
};

watch(descriptionTextarea, (newVal) => {
  if (newVal) {
    nextTick(() => {
      handleDescriptionInput();
    });
  }
});

const emit = defineEmits<{
  (e: 'save-product', product: ProductI): void;
  (e: 'delete-product', productId: string): void;
  (e: 'delete-image', product: ProductI, imageIndex: number): void;
  (
    e: 'trigger-file-upload',
    product: ProductI,
    imageIndex: number | null
  ): void;
  (e: 'handle-toggle', event: Event, product: ProductI): void;
  (e: 'cancel-editing', product: ProductI): void;
  (
    e: 'stage-tab-icon',
    productId: string,
    tabIndex: number,
    itemIndex: number,
    file: File
  ): void;
  (
    e: 'delete-tab-icon',
    productId: string,
    tabIndex: number,
    itemIndex: number
  ): void;
}>();

const hasFunction = (functionName: string): boolean => {
  return displayProduct.value.functions?.includes(functionName) || false;
};

const toggleFunction = (functionName: string) => {
  if (editingProduct.value) {
    if (!editingProduct.value.functions) {
      editingProduct.value.functions = [];
    }
    const index = editingProduct.value.functions.indexOf(functionName);
    if (index > -1) {
      editingProduct.value.functions.splice(index, 1);
    } else {
      editingProduct.value.functions.push(functionName);
    }
  }
};

const hasOption = (optionName: string): boolean => {
  return displayProduct.value.options?.includes(optionName) || false;
};

const toggleOption = (optionName: string, filterValue: string) => {
  if (editingProduct.value) {
    if (!editingProduct.value.options) {
      editingProduct.value.options = [];
    }
    if (!editingProduct.value['options-filters']) {
      editingProduct.value['options-filters'] = [];
    }
    const optionIndex = editingProduct.value.options.indexOf(optionName);
    const filterIndex =
      editingProduct.value['options-filters'].indexOf(filterValue);
    if (optionIndex > -1) {
      editingProduct.value.options.splice(optionIndex, 1);
      if (filterIndex > -1) {
        editingProduct.value['options-filters'].splice(filterIndex, 1);
      }
    } else {
      editingProduct.value.options.push(optionName);
      editingProduct.value['options-filters'].push(filterValue);
    }
  }
};

const hasOptionFilter = (filterValue: string): boolean => {
  return (
    displayProduct.value['options-filters']?.includes(filterValue) || false
  );
};

const toggleOptionFilter = (filterValue: string) => {
  if (editingProduct.value) {
    if (!editingProduct.value['options-filters']) {
      editingProduct.value['options-filters'] = [];
    }
    const filterIndex =
      editingProduct.value['options-filters'].indexOf(filterValue);
    if (filterIndex > -1) {
      editingProduct.value['options-filters'].splice(filterIndex, 1);
    } else {
      editingProduct.value['options-filters'].push(filterValue);
    }
  }
};

const hasAutosygnals = (autosygnalsValue: string): boolean => {
  return displayProduct.value.autosygnals?.includes(autosygnalsValue) || false;
};

const toggleAutosygnals = (autosygnalsValue: string) => {
  if (editingProduct.value) {
    if (!editingProduct.value.autosygnals) {
      editingProduct.value.autosygnals = [];
    }
    const autosygnalsIndex =
      editingProduct.value.autosygnals.indexOf(autosygnalsValue);
    if (autosygnalsIndex > -1) {
      editingProduct.value.autosygnals.splice(autosygnalsIndex, 1);
    } else {
      editingProduct.value.autosygnals.push(autosygnalsValue);
    }
  }
};

function editPriceList(priceList: {
  title: string;
  price: string;
  currency: string;
  content: string;
}) {
  if (editingProduct.value) {
    editingProduct.value.price_list = [priceList];
  }
}

watch(
  () => props.product.gallery,
  (newGallery) => {
    if (editingProduct.value) {
      editingProduct.value.gallery = newGallery;
    }
  },
  { deep: true }
);

const currentIconTarget = ref<{ tabIndex: number; itemIndex: number } | null>(null);
const iconUploader = ref<HTMLInputElement | null>(null);

const onUploadTabIcon = (tabIndex: number, itemIndex: number) => {
  if (!editingProduct.value) return;
  currentIconTarget.value = { tabIndex, itemIndex };
  iconUploader.value?.click();
};

const onDeleteTabIcon = (tabIndex: number, itemIndex: number) => {
  if (!editingProduct.value) return;
  emit('delete-tab-icon', editingProduct.value.id, tabIndex, itemIndex);
};

const onIconFileSelected = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  if (!file || !currentIconTarget.value || !editingProduct.value) {
    if (target) target.value = '';
    return;
  }
  const { tabIndex, itemIndex } = currentIconTarget.value;
  emit('stage-tab-icon', editingProduct.value.id, tabIndex, itemIndex, file);
  target.value = '';
  currentIconTarget.value = null;
};

const pricesRef = ref();

function saveChanges() {
  if (pricesRef.value && pricesRef.value.syncPricesToProduct) {
    pricesRef.value.syncPricesToProduct();
  }
  if (editingProduct.value) {
    editingProduct.value.price_list = [priceListRef.value];
    emit('save-product', editingProduct.value);
  }
}
</script>

<style scoped>
.product-item {
  appearance: none;
  border: 1px solid white;
  border-radius: 10px;
}

.product-title {
  font-size: 28px;
}

.product-summary {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  color: #eee;
  border-radius: 20px;
}

.product-editor {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.form-group,
.form-group-checkbox {
  display: flex;
  align-items: center;
  gap: 15px;
}

.form-group label,
.form-group-checkbox label {
  font-size: 20px;
  font-weight: bold;
  color: #ccc;
  min-width: 150px;
}

.form-group input[type='text'],
.form-group input[type='number'],
.form-group textarea,
.form-group select {
  flex-grow: 1;
  padding: 10px;
  background-color: white;
  border: 1px solid #555;
  color: black;
  border-radius: 4px;
  font-size: 18px;
}

.form-group textarea {
  resize: none;
  overflow-y: hidden;
}

.form-group span {
  flex-grow: 1;
  padding: 10px;
  background-color: #2c2c2c;
  border-radius: 4px;
  min-height: 40px;
}

.form-group input[type='checkbox'],
.form-group-checkbox input[type='checkbox'] {
  appearance: none;
  -webkit-appearance: none;
  background-color: transparent;
  margin: 0;
  font: inherit;
  color: currentColor;
  width: 25px;
  height: 25px;
  border: 2px solid white;
  display: grid;
  place-content: center;
  cursor: pointer;
}

.form-group input[type='checkbox']::before,
.form-group-checkbox input[type='checkbox']::before {
  content: '';
  width: 0.9em;
  height: 0.9em;
  transform: scale(0);
  transition: 120ms transform ease-in-out;
  box-shadow: inset 1em 1em white;
  clip-path: polygon(28% 44%, 0 57%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
}

.form-group input[type='checkbox']:checked::before,
.form-group-checkbox input[type='checkbox']:checked::before {
  transform: scale(1);
}

.form-group-functions {
  display: flex;
  flex-direction: column;
  gap: 10px;
  font-size: 28px;
}

.form-group-functions label {
  font-weight: bold;
  color: #ccc;
  margin-bottom: 5px;
}

.functions-checkboxes {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 10px;
  background-color: inherit;
  border-radius: 4px;
}

.functions-checkboxes .form-group-checkbox {
  display: flex;
  align-items: center;
  gap: 10px;
}

.functions-checkboxes .form-group-checkbox label {
  font-weight: normal;
  color: #eee;
  margin: 0;
  cursor: pointer;
}

.form-group-options {
  display: flex;
  flex-direction: column;
  gap: 10px;
  font-size: 28px;
}

.form-group-checkbox-label,
.form-group-options label {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  font-weight: bold;
  color: #ccc;
  margin-bottom: 5px;
  border-bottom: 1px solid #444;
  padding-bottom: 10px;
}

.btn-toggle-checkbox {
  transform: scale(0.9);
}

.options-checkboxes {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 10px;
  background-color: inherit;
  border-radius: 4px;
}

.options-checkboxes .form-group-checkbox {
  display: flex;
  align-items: center;
  gap: 10px;
}

.options-checkboxes .form-group-checkbox label {
  font-weight: normal;
  color: #eee;
  margin: 0;
  cursor: pointer;
}

.form-group-autosygnals {
  display: flex;
  flex-direction: column;
  gap: 10px;
  font-size: 28px;
}

.form-group-autosygnals label {
  font-weight: bold;
  color: #ccc;
  margin-bottom: 5px;
}

.autosygnals-checkboxes {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 10px;
  background-color: inherit;
  border-radius: 4px;
}

.autosygnals-checkboxes .form-group-checkbox {
  display: flex;
  align-items: center;
  gap: 10px;
}

.autosygnals-checkboxes .form-group-checkbox label {
  font-weight: normal;
  color: #eee;
  margin: 0;
  cursor: pointer;
}

.array-fields-editor {
  border-top: 1px solid #444;
  padding-top: 15px;
  margin-top: 15px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.product-actions {
  display: flex;
  justify-content: space-between;
  gap: 10px;
  margin-top: 20px;
  border-top: 1px solid #444;
  padding-top: 20px;
}

.btn-save,
.btn-delete {
  flex-grow: 1;
  width: 100%;
  max-width: 100%;
}

.accardion-checkboxes {
  border-bottom: 1px solid #444;
}
</style>
