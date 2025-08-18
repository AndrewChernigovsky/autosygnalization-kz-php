<template>
  <div>
    <div class="product-item">
      <div class="product-summary" @click.prevent>
        <strong>{{ editorStore.displayProduct(product).title }}</strong
        ><MyBtn variant="primary" @click="handleButtonClick">
          Редактировать</MyBtn
        >
      </div>
      <MyTransition>
        <div v-if="isOpen" class="product-editor">
          <!-- ... existing fields ... -->
          <div class="form-group">
            <label>Модель:</label>
            <input
              v-if="
                editorStore.isEditing(product.id) && editorStore.editingProduct
              "
              v-model="editorStore.editingProduct.model"
              type="text"
            />
            <span v-else>{{ editorStore.displayProduct(product).model }}</span>
          </div>

          <div class="form-group">
            <label>Заголовок:</label>
            <input
              v-if="
                editorStore.isEditing(product.id) && editorStore.editingProduct
              "
              v-model="editorStore.editingProduct.title"
              type="text"
            />
            <span v-else>{{ editorStore.displayProduct(product).title }}</span>
          </div>

          <div class="form-group">
            <label>Описание:</label>
            <textarea
              ref="descriptionTextarea"
              v-if="
                editorStore.isEditing(product.id) && editorStore.editingProduct
              "
              v-model="editorStore.editingProduct.description"
              @input="handleDescriptionInput"
            ></textarea>
            <span v-else>{{
              editorStore.displayProduct(product).description
            }}</span>
          </div>

          <div class="form-group">
            <label>Цена:</label>
            <input
              v-if="
                editorStore.isEditing(product.id) && editorStore.editingProduct
              "
              v-model="editorStore.editingProduct.price"
              type="number"
            />
            <span v-else>{{ editorStore.displayProduct(product).price }}</span>
          </div>

          <div class="form-group">
            <label :for="'popular-' + product.id">Популярный:</label>
            <input
              v-if="
                editorStore.isEditing(product.id) && editorStore.editingProduct
              "
              type="checkbox"
              :id="'popular-' + product.id"
              v-model="editorStore.editingProduct.is_popular"
            />
            <span v-else>{{
              editorStore.displayProduct(product).is_popular ? 'Да' : 'Нет'
            }}</span>
          </div>

          <div class="form-group">
            <label :for="'special-' + product.id">Специальный:</label>
            <input
              v-if="
                editorStore.isEditing(product.id) && editorStore.editingProduct
              "
              type="checkbox"
              :id="'special-' + product.id"
              v-model="editorStore.editingProduct.is_special"
            />
            <span v-else>{{
              editorStore.displayProduct(product).is_special ? 'Да' : 'Нет'
            }}</span>
          </div>
          <Gallery
            :product="editorStore.displayProduct(product)"
            :is-image-uploading="isImageUploading"
            @delete-image="(p, i) => emit('delete-image', p, i)"
            @trigger-file-upload="(p, i) => emit('trigger-file-upload', p, i)"
          />
          <Prices ref="pricesRef" :product="product" />
          <div class="form-group">
            <label>Категория:</label>
            <select
              v-if="
                editorStore.isEditing(product.id) && editorStore.editingProduct
              "
              v-model="editorStore.editingProduct.category"
              @click.stop
            >
              <option
                v-for="category in allCategories"
                :value="category.key"
                :key="category.key"
              >
                {{ category.name }}
              </option>
            </select>
            <span v-else>{{
              getCategoryName(editorStore.displayProduct(product).category)
            }}</span>
          </div>

          <div class="array-fields-editor">
            <div class="form-group-functions">
              <label>Функции:</label>
              <div class="functions-checkboxes">
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'autostart-' + product.id"
                    :checked="hasFunction(product, 'Автозапуск')"
                    @change="toggleFunction('Автозапуск')"
                  />
                  <label :for="'autostart-' + product.id">Автозапуск</label>
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'engine-block-' + product.id"
                    :checked="
                      hasFunction(product, 'БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN')
                    "
                    @change="toggleFunction('БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN')"
                  />
                  <label :for="'engine-block-' + product.id"
                    >БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'preheater-' + product.id"
                    :checked="
                      hasFunction(product, 'УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ')
                    "
                    @change="
                      toggleFunction('УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ')
                    "
                  />
                  <label :for="'preheater-' + product.id"
                    >УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'phone-control-' + product.id"
                    :checked="hasFunction(product, 'УПРАВЛЕНИЕ С ТЕЛЕФОНА')"
                    @change="toggleFunction('УПРАВЛЕНИЕ С ТЕЛЕФОНА')"
                  />
                  <label :for="'phone-control-' + product.id"
                    >УПРАВЛЕНИЕ С ТЕЛЕФОНА</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'free-monitoring-' + product.id"
                    :checked="hasFunction(product, 'БЕСПЛАТНЫЙ МОНИТОРИНГ')"
                    @change="toggleFunction('БЕСПЛАТНЫЙ МОНИТОРИНГ')"
                  />
                  <label :for="'free-monitoring-' + product.id"
                    >БЕСПЛАТНЫЙ МОНИТОРИНГ</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'bluetooth-auth-' + product.id"
                    :checked="
                      hasFunction(
                        product,
                        'УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART'
                      )
                    "
                    @change="
                      toggleFunction('УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART')
                    "
                  />
                  <label :for="'bluetooth-auth-' + product.id"
                    >УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'smart-diagnostic-' + product.id"
                    :checked="hasFunction(product, 'УМНАЯ АВТОДИАГНОСТИКА')"
                    @change="toggleFunction('УМНАЯ АВТОДИАГНОСТИКА')"
                  />
                  <label :for="'smart-diagnostic-' + product.id"
                    >УМНАЯ АВТОДИАГНОСТИКА</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'data-fuel-' + product.id"
                    :checked="
                      hasFunction(product, 'ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА')
                    "
                    @change="
                      toggleFunction('ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА')
                    "
                  />
                  <label :for="'data-fuel-' + product.id"
                    >ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА</label
                  >
                </div>
              </div>
            </div>
            <div class="form-group-options">
              <label>Опции:</label>
              <div class="options-checkboxes">
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'suv-' + product.id"
                    :checked="hasOption(product, 'Для внедорожника')"
                    @change="toggleOption('Для внедорожника', 'vnedorojnik')"
                  />
                  <label :for="'suv-' + product.id">Для внедорожника</label>
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'car-' + product.id"
                    :checked="hasOption(product, 'Для легкового авто')"
                    @change="toggleOption('Для легкового авто', 'legkoe-avto')"
                  />
                  <label :for="'car-' + product.id">Для легкового авто</label>
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'option-autosetup-' + product.id"
                    :checked="hasOptionFilter(product, 'autosetup')"
                    @change="toggleOptionFilter('autosetup')"
                  />
                  <label :for="'option-autosetup-' + product.id"
                    >Автозапуск</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'option-block-engine-can-' + product.id"
                    :checked="hasOptionFilter(product, 'block-engine-can')"
                    @change="toggleOptionFilter('block-engine-can')"
                  />
                  <label :for="'option-block-engine-can-' + product.id"
                    >Блокировка двигателя по CAN</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'option-control-before-start-' + product.id"
                    :checked="hasOptionFilter(product, 'control-before-start')"
                    @change="toggleOptionFilter('control-before-start')"
                  />
                  <label :for="'option-control-before-start-' + product.id"
                    >Управление предпусковым подогревом</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'option-control-phone-' + product.id"
                    :checked="hasOptionFilter(product, 'control-phone')"
                    @change="toggleOptionFilter('control-phone')"
                  />
                  <label :for="'option-control-phone-' + product.id"
                    >Управление с телефона</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'option-free-monitoring-' + product.id"
                    :checked="hasOptionFilter(product, 'free-monitoring')"
                    @change="toggleOptionFilter('free-monitoring')"
                  />
                  <label :for="'option-free-monitoring-' + product.id"
                    >Бесплатный мониторинг</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'option-bluetooth-smart-' + product.id"
                    :checked="hasOptionFilter(product, 'bluetooth-smart')"
                    @change="toggleOptionFilter('bluetooth-smart')"
                  />
                  <label :for="'option-bluetooth-smart-' + product.id"
                    >Умная авторизация по Bluetooth Smart</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'option-smart-diagnostic-' + product.id"
                    :checked="hasOptionFilter(product, 'smart-diagnostic')"
                    @change="toggleOptionFilter('smart-diagnostic')"
                  />
                  <label :for="'option-smart-diagnostic-' + product.id"
                    >Умная автодиагностика</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'option-data-level-bensin-' + product.id"
                    :checked="hasOptionFilter(product, 'data-level-bensin')"
                    @change="toggleOptionFilter('data-level-bensin')"
                  />
                  <label :for="'option-data-level-bensin-' + product.id"
                    >Данные о пробеге и уровне топлива</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'option-for-park-systems-' + product.id"
                    :checked="hasOptionFilter(product, 'for-park-systems')"
                    @change="toggleOptionFilter('for-park-systems')"
                  />
                  <label :for="'option-for-park-systems-' + product.id"
                    >Для парктроников</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'option-remote-controls-' + product.id"
                    :checked="hasOptionFilter(product, 'remote-controls')"
                    @change="toggleOptionFilter('remote-controls')"
                  />
                  <label :for="'option-remote-controls-' + product.id"
                    >Пульты управления</label
                  >
                </div>
              </div>
            </div>
            <div class="form-group-autosygnals">
              <label>Раздел для автосигнализаций:</label>
              <div class="autosygnals-checkboxes">
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'autosygnals-without-auto-' + product.id"
                    :checked="hasAutosygnals(product, 'without-auto')"
                    @change="toggleAutosygnals('without-auto')"
                  />
                  <label :for="'autosygnals-without-auto-' + product.id"
                    >Без автозапуска</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'autosygnals-starline-' + product.id"
                    :checked="hasAutosygnals(product, 'starline')"
                    @change="toggleAutosygnals('starline')"
                  />
                  <label :for="'autosygnals-starline-' + product.id"
                    >Starline</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'autosygnals-auto-' + product.id"
                    :checked="hasAutosygnals(product, 'auto')"
                    @change="toggleAutosygnals('auto')"
                  />
                  <label :for="'autosygnals-auto-' + product.id"
                    >С автозапуском</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'autosygnals-gsm-' + product.id"
                    :checked="hasAutosygnals(product, 'gsm')"
                    @change="toggleAutosygnals('gsm')"
                  />
                  <label :for="'autosygnals-gsm-' + product.id"
                    >GSM модуль</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'autosygnals-for-park-systems-' + product.id"
                    :checked="hasAutosygnals(product, 'for-park-systems')"
                    @change="toggleAutosygnals('for-park-systems')"
                  />
                  <label :for="'autosygnals-for-park-systems-' + product.id"
                    >Для парктроников</label
                  >
                </div>
                <div class="form-group-checkbox">
                  <input
                    type="checkbox"
                    :id="'autosygnals-remote-controls-' + product.id"
                    :checked="hasAutosygnals(product, 'remote-controls')"
                    @change="toggleAutosygnals('remote-controls')"
                  />
                  <label :for="'autosygnals-remote-controls-' + product.id"
                    >Пульты управления</label
                  >
                </div>
              </div>
            </div>
          </div>
          <Tabs @upload-icon="onUploadTabIcon" @delete-icon="onDeleteTabIcon" />
          <div class="product-actions">
            <MyBtn variant="secondary" @click="saveChanges" class="btn-save">
              Сохранить
            </MyBtn>
            <MyBtn
              variant="primary"
              @click="emit('delete-product', product.id)"
              class="btn-delete"
            >
              Удалить
            </MyBtn>
          </div>
        </div>
      </MyTransition>
    </div>
    <input
      type="file"
      ref="iconUploader"
      @change="onIconFileSelected"
      style="display: none"
      accept="image/*"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, watch, nextTick } from 'vue';
import type { ProductI } from './interfaces/Products';
import Gallery from './Gallery.vue';
import Tabs from './Tabs.vue';
import { useProductEditorStore } from '../../stores/productEditorStore';
import Prices from './Prices.vue';
import MyBtn from '../UI/MyBtn.vue';
import MyTransition from '../UI/MyTransition.vue';

const editorStore = useProductEditorStore();

const isOpen = ref(false);
const descriptionTextarea = ref<HTMLTextAreaElement | null>(null);

const handleButtonClick = () => {
  isOpen.value = !isOpen.value;
};

watch(isOpen, (newValue) => {
  if (newValue) {
    editorStore.startEditing(props.product, '');
  } else {
    editorStore.cancelEditing();
    emit('cancel-editing', props.product);
  }
});

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

defineOptions({
  name: 'Product',
});

const props = defineProps<{
  product: ProductI;
  allCategories: Array<{ key: string; name: string }>;
  isImageUploading: (productId: string, index: number | null) => boolean;
  getCategoryName: (key: string) => string;
}>();

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

const updateArrayField = (
  event: Event,
  fieldName: 'functions' | 'options' | 'options-filters' | 'autosygnals'
) => {
  const target = event.target as HTMLTextAreaElement;
  editorStore.updateArrayField(fieldName, target.value);
};

const hasFunction = (product: ProductI, functionName: string): boolean => {
  const currentProduct = editorStore.displayProduct(product);
  return currentProduct.functions?.includes(functionName) || false;
};

const toggleFunction = (functionName: string) => {
  if (!editorStore.editingProduct) {
    editorStore.startEditing(props.product, 'functions');
  }

  if (editorStore.editingProduct) {
    if (!editorStore.editingProduct.functions) {
      editorStore.editingProduct.functions = [];
    }

    const index = editorStore.editingProduct.functions.indexOf(functionName);
    if (index > -1) {
      // Удаляем функцию если она уже есть
      editorStore.editingProduct.functions.splice(index, 1);
    } else {
      // Добавляем функцию если её нет
      editorStore.editingProduct.functions.push(functionName);
    }
  }
};

const hasOption = (product: ProductI, optionName: string): boolean => {
  const currentProduct = editorStore.displayProduct(product);
  return currentProduct.options?.includes(optionName) || false;
};

const toggleOption = (optionName: string, filterValue: string) => {
  if (!editorStore.editingProduct) {
    editorStore.startEditing(props.product, 'options');
  }

  if (editorStore.editingProduct) {
    // Инициализируем массивы если их нет
    if (!editorStore.editingProduct.options) {
      editorStore.editingProduct.options = [];
    }
    if (!editorStore.editingProduct['options-filters']) {
      editorStore.editingProduct['options-filters'] = [];
    }

    const optionIndex = editorStore.editingProduct.options.indexOf(optionName);
    const filterIndex =
      editorStore.editingProduct['options-filters'].indexOf(filterValue);

    if (optionIndex > -1) {
      // Удаляем опцию и фильтр если они уже есть
      editorStore.editingProduct.options.splice(optionIndex, 1);
      if (filterIndex > -1) {
        editorStore.editingProduct['options-filters'].splice(filterIndex, 1);
      }
    } else {
      // Добавляем опцию и фильтр если их нет
      editorStore.editingProduct.options.push(optionName);
      editorStore.editingProduct['options-filters'].push(filterValue);
    }
  }
};

const hasOptionFilter = (product: ProductI, filterValue: string): boolean => {
  const currentProduct = editorStore.displayProduct(product);
  return currentProduct['options-filters']?.includes(filterValue) || false;
};

const toggleOptionFilter = (filterValue: string) => {
  if (!editorStore.editingProduct) {
    editorStore.startEditing(props.product, 'options-filters');
  }

  if (editorStore.editingProduct) {
    // Инициализируем массив если его нет
    if (!editorStore.editingProduct['options-filters']) {
      editorStore.editingProduct['options-filters'] = [];
    }

    const filterIndex =
      editorStore.editingProduct['options-filters'].indexOf(filterValue);

    if (filterIndex > -1) {
      // Удаляем фильтр если он уже есть
      editorStore.editingProduct['options-filters'].splice(filterIndex, 1);
    } else {
      // Добавляем фильтр если его нет
      editorStore.editingProduct['options-filters'].push(filterValue);
    }
  }
};

const hasAutosygnals = (
  product: ProductI,
  autosygnalsValue: string
): boolean => {
  const currentProduct = editorStore.displayProduct(product);
  return currentProduct.autosygnals?.includes(autosygnalsValue) || false;
};

const toggleAutosygnals = (autosygnalsValue: string) => {
  if (!editorStore.editingProduct) {
    editorStore.startEditing(props.product, 'autosygnals');
  }

  if (editorStore.editingProduct) {
    // Инициализируем массив если его нет
    if (!editorStore.editingProduct.autosygnals) {
      editorStore.editingProduct.autosygnals = [];
    }

    const autosygnalsIndex =
      editorStore.editingProduct.autosygnals.indexOf(autosygnalsValue);

    if (autosygnalsIndex > -1) {
      // Удаляем значение если оно уже есть
      editorStore.editingProduct.autosygnals.splice(autosygnalsIndex, 1);
    } else {
      // Добавляем значение если его нет
      editorStore.editingProduct.autosygnals.push(autosygnalsValue);
    }
  }
};

watch(
  () => props.product.gallery,
  (newGallery) => {
    if (editorStore.isEditing(props.product.id) && editorStore.editingProduct) {
      editorStore.editingProduct.gallery = newGallery;
    }
  },
  { deep: true }
);

const iconUploader = ref<HTMLInputElement | null>(null);
const currentIconTarget = ref<{ tabIndex: number; itemIndex: number } | null>(
  null
);

const onUploadTabIcon = (tabIndex: number, itemIndex: number) => {
  currentIconTarget.value = { tabIndex, itemIndex };
  iconUploader.value?.click();
};

const onDeleteTabIcon = (tabIndex: number, itemIndex: number) => {
  if (!editorStore.editingProduct) return;
  emit('delete-tab-icon', editorStore.editingProduct.id, tabIndex, itemIndex);
};

const onIconFileSelected = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  if (!file || !currentIconTarget.value || !editorStore.editingProduct) {
    if (target) target.value = '';
    return;
  }
  const { tabIndex, itemIndex } = currentIconTarget.value;
  emit(
    'stage-tab-icon',
    editorStore.editingProduct.id,
    tabIndex,
    itemIndex,
    file
  );
  target.value = '';
  currentIconTarget.value = null;
};

const pricesRef = ref();

function saveChanges() {
  if (pricesRef.value && pricesRef.value.syncPricesToProduct) {
    pricesRef.value.syncPricesToProduct();
  }
  if (editorStore.editingProduct) {
    emit('save-product', editorStore.editingProduct);
  }
}
</script>

<style scoped>
.product-item {
  appearance: none;
  border: 1px solid white;
  border-radius: 10px;
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

.form-group-options label {
  font-weight: bold;
  color: #ccc;
  margin-bottom: 5px;
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
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
  border-top: 1px solid #444;
  padding-top: 20px;
}
</style>
