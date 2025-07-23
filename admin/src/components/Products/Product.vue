<template>
  <div>
    <details
      class="product-item"
      :open="product.is_new"
      @toggle="(event) => emit('handle-toggle', event, product)"
    >
      <summary>
        <strong>{{ displayProduct.title }}</strong>
      </summary>
      <div class="product-editor">
        <div class="form-group" @click="startEditing(product, 'model')">
          <label>Модель:</label>
          <input
            v-if="editingProduct?.id === product.id && fieldToEdit === 'model'"
            v-model="editingProduct.model"
            type="text"
          />
          <span v-else>{{ displayProduct.model }}</span>
        </div>

        <div class="form-group" @click="startEditing(product, 'title')">
          <label>Заголовок:</label>
          <input
            v-if="editingProduct?.id === product.id && fieldToEdit === 'title'"
            v-model="editingProduct.title"
            type="text"
          />
          <span v-else>{{ displayProduct.title }}</span>
        </div>

        <div class="form-group" @click="startEditing(product, 'description')">
          <label>Описание:</label>
          <textarea
            v-if="
              editingProduct?.id === product.id && fieldToEdit === 'description'
            "
            v-model="editingProduct.description"
          ></textarea>
          <span v-else>{{ displayProduct.description }}</span>
        </div>

        <div class="form-group" @click="startEditing(product, 'price')">
          <label>Цена:</label>
          <input
            v-if="editingProduct?.id === product.id && fieldToEdit === 'price'"
            v-model="editingProduct.price"
            type="number"
          />
          <span v-else>{{ displayProduct.price }}</span>
        </div>

        <div class="form-group-checkbox">
          <label :for="'popular-' + product.id">Популярный:</label>
          <input
            type="checkbox"
            :id="'popular-' + product.id"
            :checked="displayProduct.is_popular"
            @change="handleCheckboxChange('is_popular')"
          />
        </div>

        <div class="form-group-checkbox">
          <label>Специальный:</label>
          <input
            type="checkbox"
            :checked="displayProduct.is_special"
            @change="handleCheckboxChange('is_special')"
          />
        </div>

        <Gallery
          :product="displayProduct"
          :is-image-uploading="isImageUploading"
          @delete-image="(p, i) => emit('delete-image', p, i)"
          @trigger-file-upload="(p, i) => emit('trigger-file-upload', p, i)"
        />

        <div class="form-group" @click="startEditing(product, 'category_key')">
          <label>Категория:</label>
          <select
            v-if="
              editingProduct?.id === product.id &&
              fieldToEdit === 'category_key'
            "
            v-model="editingCategoryKey"
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
          <span v-else>{{ getCategoryName(displayProduct.category_key) }}</span>
        </div>

        <div class="array-fields-editor">
          <div class="form-group" @click="startEditing(product, 'functions')">
            <label>Функции (через запятую):</label>
            <textarea
              v-if="
                editingProduct?.id === product.id && fieldToEdit === 'functions'
              "
              :value="getArrayAsCST(editingProduct.functions)"
              @input="updateArrayField($event, 'functions')"
            ></textarea>
            <span v-else>{{ getArrayAsCST(displayProduct.functions) }}</span>
          </div>
          <div class="form-group" @click="startEditing(product, 'options')">
            <label>Опции (через запятую):</label>
            <textarea
              v-if="
                editingProduct?.id === product.id && fieldToEdit === 'options'
              "
              :value="getArrayAsCST(editingProduct.options)"
              @input="updateArrayField($event, 'options')"
            ></textarea>
            <span v-else>{{ getArrayAsCST(displayProduct.options) }}</span>
          </div>
          <div
            class="form-group"
            @click="startEditing(product, 'options-filters')"
          >
            <label>Опции-фильтры (через запятую):</label>
            <textarea
              v-if="
                editingProduct?.id === product.id &&
                fieldToEdit === 'options-filters'
              "
              :value="getArrayAsCST(editingProduct['options-filters'])"
              @input="updateArrayField($event, 'options-filters')"
            ></textarea>
            <span v-else>{{
              getArrayAsCST(displayProduct['options-filters'])
            }}</span>
          </div>
          <div class="form-group" @click="startEditing(product, 'autosygnals')">
            <label>Раздел для автосигнализаций (через запятую):</label>
            <textarea
              v-if="
                editingProduct?.id === product.id &&
                fieldToEdit === 'autosygnals'
              "
              :value="getArrayAsCST(editingProduct.autosygnals)"
              @input="updateArrayField($event, 'autosygnals')"
            ></textarea>
            <span v-else>{{ getArrayAsCST(displayProduct.autosygnals) }}</span>
          </div>
        </div>

        <Tabs
          v-if="displayProduct.tabs"
          :tabs="displayProduct.tabs"
          :is-icon-uploading="
            (tabIndex, itemIndex) => isUploading[`tab-${tabIndex}-${itemIndex}`]
          "
          @update:tabs="updateTabs"
          @trigger-icon-upload="handleIconUpload"
          @delete-icon="handleIconDelete"
          :server-base-url="API_URL"
        />

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
import { ref, computed, watch } from 'vue';
import type { ProductI, Tab, DescriptionItem } from './interfaces/Products';
import Gallery from './Gallery.vue';
import Tabs from './Tabs.vue';
import { API_URL } from '../../../config';

const props = defineProps<{
  product: ProductI;
  allCategories: Array<{ key: string; name: string }>;
  isImageUploading: (productId: string, index: number | null) => boolean;
  getCategoryName: (key: string) => string;
}>();

watch(
  () => props.product.gallery,
  (newGallery) => {
    if (editingProduct.value && editingProduct.value.id === props.product.id) {
      editingProduct.value.gallery = newGallery;
    } else {
      startEditing(props.product, 'gallery');
    }
  },
  { deep: true }
);

const emit = defineEmits<{
  (e: 'save-product', product: ProductI): void;
  (e: 'delete-product', productId: string): void;
  (e: 'toggle-popular', product: ProductI): void;
  (e: 'delete-image', product: ProductI, imageIndex: number): void;
  (
    e: 'trigger-file-upload',
    product: ProductI,
    imageIndex: number | null
  ): void;
  (e: 'handle-toggle', event: Event, product: ProductI): void;
}>();

const displayProduct = computed(() => {
  if (editingProduct.value && editingProduct.value.id === props.product.id) {
    return editingProduct.value;
  }
  return props.product;
});

const editingProduct = ref<ProductI | null>(null);
const fieldToEdit = ref<string | null>(null);
const iconUploader = ref<HTMLInputElement | null>(null);
const currentIconTarget = ref<{ tabIndex: number; itemIndex: number } | null>(
  null
);

const isUploading = ref<Record<string, boolean>>({});

// const serverUrl = API_URL.replace('/src/server', '');

const editingCategoryKey = computed({
  get: () => editingProduct.value?.category_key || '',
  set: (value) => {
    if (editingProduct.value) {
      editingProduct.value.category_key = value;
    }
  },
});

function ensureEditing() {
  if (!editingProduct.value) {
    editingProduct.value = JSON.parse(JSON.stringify(props.product));
    fieldToEdit.value = 'title';
  }
  return editingProduct.value;
}

function handleCheckboxChange(field: 'is_popular' | 'is_special') {
  let productToEdit = editingProduct.value;

  if (!productToEdit || productToEdit.id !== props.product.id) {
    productToEdit = JSON.parse(JSON.stringify(props.product));
    editingProduct.value = productToEdit;
  }

  if (productToEdit) {
    productToEdit[field] = !productToEdit[field];
    fieldToEdit.value = field;
  }
}

function startEditing(product: ProductI, field: string) {
  if (!editingProduct.value || editingProduct.value.id !== product.id) {
    editingProduct.value = JSON.parse(JSON.stringify(product));
  }
  fieldToEdit.value = field;
}

function getArrayAsCST(arr: string[] | undefined): string {
  if (!arr || arr.length === 0) return 'Нет';
  return arr.join(', ');
}

function updateArrayField(
  event: Event,
  fieldName: 'functions' | 'options' | 'options-filters' | 'autosygnals'
) {
  if (editingProduct.value) {
    const target = event.target as HTMLTextAreaElement;
    const value = target.value
      .split(',')
      .map((s) => s.trim())
      .filter(Boolean);

    if (fieldName === 'options-filters') {
      (editingProduct.value as any)['options-filters'] = value;
    } else {
      (editingProduct.value as any)[fieldName] = value;
    }
  }
}

function updateTabs(newTabs: Tab[]) {
  const productToEdit = ensureEditing();
  if (!productToEdit) return;
  productToEdit.tabs = newTabs;
}

function handleIconUpload(tabIndex: number, itemIndex: number) {
  ensureEditing();
  currentIconTarget.value = { tabIndex, itemIndex };
  iconUploader.value?.click();
}

async function onIconFileSelected(event: Event) {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  if (!file || !currentIconTarget.value) return;

  const { tabIndex, itemIndex } = currentIconTarget.value;
  const productToEdit = ensureEditing();
  if (!productToEdit || !productToEdit.tabs) return;

  const previewUrl = URL.createObjectURL(file);

  // Re-construct tabs array for reactivity
  const newTabs = JSON.parse(JSON.stringify(productToEdit.tabs));
  newTabs[tabIndex].description[itemIndex]['path-icon'] = previewUrl;
  productToEdit.tabs = newTabs;

  const key = `tab-${tabIndex}-${itemIndex}`;
  isUploading.value[key] = true;

  const formData = new FormData();
  formData.append('icon', file);
  formData.append('productId', productToEdit.id);
  formData.append('tabIndex', String(tabIndex));
  formData.append('itemIndex', String(itemIndex));

  try {
    const response = await fetch(
      `server/php/admin/api/products/upload_tab_icon.php`,
      {
        method: 'POST',
        body: formData,
      }
    );

    if (!response.ok) {
      throw new Error('Server responded with an error');
    }

    const result = await response.json();
    if (result.filePath && productToEdit.tabs) {
      const finalTabs = JSON.parse(JSON.stringify(productToEdit.tabs));
      finalTabs[tabIndex].description[itemIndex]['path-icon'] = result.filePath;
      productToEdit.tabs = finalTabs;
    }
  } catch (error) {
    console.error('Failed to upload icon:', error);
  } finally {
    isUploading.value[key] = false;
    URL.revokeObjectURL(previewUrl);
    target.value = ''; // Reset input
    currentIconTarget.value = null;
  }
}

async function handleIconDelete(tabIndex: number, itemIndex: number) {
  const productToEdit = ensureEditing();
  if (!productToEdit) return;

  try {
    const response = await fetch(
      `server/php/admin/api/products/delete_tab_icon.php`,
      {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          productId: productToEdit.id,
          tabIndex,
          itemIndex,
        }),
      }
    );

    if (!response.ok) {
      throw new Error('Server responded with an error');
    }

    if (
      productToEdit.tabs &&
      Array.isArray(productToEdit.tabs[tabIndex].description)
    ) {
      const desc = productToEdit.tabs[tabIndex]
        .description as DescriptionItem[];
      if (desc[itemIndex]) {
        desc[itemIndex]['path-icon'] = '';
      }
    }
  } catch (error) {
    console.error('Failed to delete icon:', error);
  }
}

function saveChanges() {
  if (editingProduct.value) {
    emit('save-product', editingProduct.value);
    editingProduct.value = null;
    fieldToEdit.value = null;
  }
}
</script>

<script lang="ts">
export default {
  name: 'Product',
};
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
