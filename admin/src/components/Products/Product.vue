<template>
  <div>
    <details
      class="product-item"
      :open="product.is_new"
      @toggle="(event) => handleToggle(event, product)"
    >
      <summary>
        <strong>{{ editorStore.displayProduct(product).title }}</strong>
      </summary>
      <div class="product-editor">
        <!-- ... existing fields ... -->
        <div
          class="form-group"
          @click="editorStore.startEditing(product, 'model')"
        >
          <label>Модель:</label>
          <input
            v-if="
              editorStore.isEditing(product.id) &&
              editorStore.fieldToEdit === 'model' &&
              editorStore.editingProduct
            "
            v-model="editorStore.editingProduct.model"
            type="text"
          />
          <span v-else>{{ editorStore.displayProduct(product).model }}</span>
        </div>

        <div
          class="form-group"
          @click="editorStore.startEditing(product, 'title')"
        >
          <label>Заголовок:</label>
          <input
            v-if="
              editorStore.isEditing(product.id) &&
              editorStore.fieldToEdit === 'title' &&
              editorStore.editingProduct
            "
            v-model="editorStore.editingProduct.title"
            type="text"
          />
          <span v-else>{{ editorStore.displayProduct(product).title }}</span>
        </div>

        <div
          class="form-group"
          @click="editorStore.startEditing(product, 'description')"
        >
          <label>Описание:</label>
          <textarea
            v-if="
              editorStore.isEditing(product.id) &&
              editorStore.fieldToEdit === 'description' &&
              editorStore.editingProduct
            "
            v-model="editorStore.editingProduct.description"
          ></textarea>
          <span v-else>{{
            editorStore.displayProduct(product).description
          }}</span>
        </div>

        <div
          class="form-group"
          @click="editorStore.startEditing(product, 'price')"
        >
          <label>Цена:</label>
          <input
            v-if="
              editorStore.isEditing(product.id) &&
              editorStore.fieldToEdit === 'price' &&
              editorStore.editingProduct
            "
            v-model="editorStore.editingProduct.price"
            type="number"
          />
          <span v-else>{{ editorStore.displayProduct(product).price }}</span>
        </div>

        <div class="form-group-checkbox">
          <label :for="'popular-' + product.id">Популярный:</label>
          <input
            type="checkbox"
            :id="'popular-' + product.id"
            :checked="editorStore.displayProduct(product).is_popular"
            @change="
              () => {
                editorStore.startEditing(product, 'is_popular');
                editorStore.handleCheckboxChange('is_popular');
              }
            "
          />
        </div>

        <div class="form-group-checkbox">
          <label>Специальный:</label>
          <input
            type="checkbox"
            :checked="editorStore.displayProduct(product).is_special"
            @change="
              () => {
                editorStore.startEditing(product, 'is_special');
                editorStore.handleCheckboxChange('is_special');
              }
            "
          />
        </div>
        <Gallery
          :product="editorStore.displayProduct(product)"
          :is-image-uploading="isImageUploading"
          @delete-image="(p, i) => emit('delete-image', p, i)"
          @trigger-file-upload="(p, i) => emit('trigger-file-upload', p, i)"
        />
        <Prices ref="pricesRef" :product="product" />
        <div
          class="form-group"
          @click="editorStore.startEditing(product, 'category')"
        >
          <label>Категория:</label>
          <select
            v-if="
              editorStore.isEditing(product.id) &&
              editorStore.fieldToEdit === 'category' &&
              editorStore.editingProduct
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
                  :checked="hasFunction(product, 'БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN')"
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
                  @change="toggleFunction('УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ')"
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
                    hasFunction(product, 'УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART')
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
                  @change="toggleFunction('ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА')"
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
                <label :for="'autosygnals-gsm-' + product.id">GSM модуль</label>
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
          <button @click="saveChanges" class="btn-save">Сохранить</button>
          <button
            @click="emit('delete-product', product.id)"
            class="btn-delete"
          >
            Удалить
          </button>
        </div>
      </div>
    </details>
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
import { ref, watch } from 'vue';
import type { ProductI } from './interfaces/Products';
import Gallery from './Gallery.vue';
import Tabs from './Tabs.vue';
import { useProductEditorStore } from '../../stores/productEditorStore';
import Prices from './Prices.vue';

const editorStore = useProductEditorStore();

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

const handleToggle = (event: Event, product: ProductI) => {
  const detailsElement = event.target as HTMLDetailsElement;
  if (detailsElement.open) {
    editorStore.startEditing(product, '');
  } else {
    editorStore.cancelEditing();
    emit('cancel-editing', product);
  }
  emit('handle-toggle', event, product);
};

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
  border: 1px solid #444;
  border-radius: 5px;
  margin-bottom: 20px;
  background-color: #333;
}
.product-item > summary {
  padding: 15px;
  cursor: pointer;
  background-color: #3a3a3a;
  color: #eee;
  border-radius: 5px 5px 0 0;
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
  background-color: #444;
  border: 1px solid #555;
  color: #fff;
  border-radius: 4px;
}
.form-group span {
  flex-grow: 1;
  padding: 10px;
  background-color: #2c2c2c;
  border-radius: 4px;
  min-height: 40px;
}
.form-group-checkbox input[type='checkbox'] {
  width: 20px;
  height: 20px;
}

.form-group-functions {
  display: flex;
  flex-direction: column;
  gap: 10px;
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
  background-color: #2c2c2c;
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
  background-color: #2c2c2c;
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
  background-color: #2c2c2c;
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
.btn-save,
.btn-delete {
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  color: white;
  cursor: pointer;
  transition: background-color 0.2s;
}
.btn-save {
  background-color: #28a745;
}
.btn-save:hover {
  background-color: #218838;
}
.btn-delete {
  background-color: #dc3545;
}
.btn-delete:hover {
  background-color: #c82333;
}
</style>
