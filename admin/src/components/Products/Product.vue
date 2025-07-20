<template>
  <details
    class="product-item"
    :open="product.is_new"
    @toggle="(event) => emit('handle-toggle', event, product)"
  >
    <summary>
      <strong>{{ product.title }}</strong>
    </summary>
    <div class="product-editor">
      <div class="form-group" @click="startEditing(product, 'model')">
        <label>Модель:</label>
        <input
          v-if="editingProduct?.id === product.id && fieldToEdit === 'model'"
          v-model="editingProduct.model"
          type="text"
        />
        <span v-else>{{ product.model }}</span>
      </div>

      <div class="form-group" @click="startEditing(product, 'title')">
        <label>Заголовок:</label>
        <input
          v-if="editingProduct?.id === product.id && fieldToEdit === 'title'"
          v-model="editingProduct.title"
          type="text"
        />
        <span v-else>{{ product.title }}</span>
      </div>

      <div class="form-group" @click="startEditing(product, 'description')">
        <label>Описание:</label>
        <textarea
          v-if="
            editingProduct?.id === product.id && fieldToEdit === 'description'
          "
          v-model="editingProduct.description"
        ></textarea>
        <span v-else>{{ product.description }}</span>
      </div>

      <div class="form-group" @click="startEditing(product, 'price')">
        <label>Цена:</label>
        <input
          v-if="editingProduct?.id === product.id && fieldToEdit === 'price'"
          v-model="editingProduct.price"
          type="number"
        />
        <span v-else>{{ product.price }}</span>
      </div>

      <div class="form-group-checkbox">
        <label :for="'popular-' + product.id">Популярный:</label>
        <input
          type="checkbox"
          :id="'popular-' + product.id"
          :checked="
            editingProduct && editingProduct.id === product.id
              ? editingProduct.is_popular
              : product.is_popular
          "
          @change="handleCheckboxChange('is_popular')"
        />
      </div>

      <div class="form-group-checkbox">
        <label>Специальный:</label>
        <input
          type="checkbox"
          :checked="
            editingProduct && editingProduct.id === product.id
              ? editingProduct.is_special
              : product.is_special
          "
          @change="handleCheckboxChange('is_special')"
        />
      </div>

      <Gallery
        :product="product"
        :is-image-uploading="isImageUploading"
        @delete-image="(p, i) => emit('delete-image', p, i)"
        @trigger-file-upload="(p, i) => emit('trigger-file-upload', p, i)"
      />

      <div class="form-group" @click="startEditing(product, 'category_key')">
        <label>Категория:</label>
        <select
          v-if="
            editingProduct?.id === product.id && fieldToEdit === 'category_key'
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
        <span v-else>{{ getCategoryName(product.category_key) }}</span>
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
          <span v-else>{{ getArrayAsCST(product.functions) }}</span>
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
          <span v-else>{{ getArrayAsCST(product.options) }}</span>
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
          <span v-else>{{ getArrayAsCST(product['options-filters']) }}</span>
        </div>
        <div class="form-group" @click="startEditing(product, 'autosygnals')">
          <label>Раздел для автосигнализаций (через запятую):</label>
          <textarea
            v-if="
              editingProduct?.id === product.id && fieldToEdit === 'autosygnals'
            "
            :value="getArrayAsCST(editingProduct.autosygnals)"
            @input="updateArrayField($event, 'autosygnals')"
          ></textarea>
          <span v-else>{{ getArrayAsCST(product.autosygnals) }}</span>
        </div>
      </div>

      <Tabs
        v-if="product.tabs"
        :tabs="product.tabs"
        :is-icon-uploading="
          (tabIndex) => isUploading[`tab-${tabIndex}`]
        "
        @update:tabs="updateTabs"
        @trigger-icon-upload="handleIconUpload"
        @delete-icon="handleIconDelete"
      />

      <div class="product-actions">
        <button @click="saveChanges" class="btn-save">Сохранить</button>
        <button @click="emit('delete-product', product.id)" class="btn-delete">
          Удалить
        </button>
      </div>
    </div>
  </details>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import type { ProductI, Tab } from './interfaces/Products';
import Gallery from './Gallery.vue';
import Tabs from './Tabs.vue';

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

const editingProduct = ref<ProductI | null>(null);
const fieldToEdit = ref<string | null>(null);

const isUploading = ref<Record<string, boolean>>({});

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
  productToEdit.tabs = newTabs;
}

function handleIconUpload(tabIndex: number) {
  const productToEdit = ensureEditing();
  const key = `tab-${tabIndex}`;
  isUploading.value[key] = true;
  setTimeout(() => {
    if (productToEdit && productToEdit.tabs) {
      productToEdit.tabs[tabIndex]['path-icon'] =
        '/client/vectors/thermometer.svg'; // Placeholder path
    }
    isUploading.value[key] = false;
  }, 2000);
}

function handleIconDelete(tabIndex: number) {
  const productToEdit = ensureEditing();
  if (productToEdit && productToEdit.tabs) {
    productToEdit.tabs[tabIndex]['path-icon'] = '';
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
