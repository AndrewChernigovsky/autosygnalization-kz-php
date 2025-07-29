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
        <div
          class="form-group"
          @click="editorStore.startEditing(product, 'model')"
        >
          <label>Модель:</label>
          <input
            v-if="
              editorStore.isEditing(product.id) &&
              editorStore.fieldToEdit === 'model'
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
              editorStore.fieldToEdit === 'title'
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
              editorStore.fieldToEdit === 'description'
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
              editorStore.fieldToEdit === 'price'
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

        <div
          class="form-group"
          @click="editorStore.startEditing(product, 'category')"
        >
          <label>Категория:</label>
          <select
            v-if="
              editorStore.isEditing(product.id) &&
              editorStore.fieldToEdit === 'category'
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
          <div
            class="form-group"
            @click="editorStore.startEditing(product, 'functions')"
          >
            <label>Функции (через запятую):</label>
            <textarea
              v-if="
                editorStore.isEditing(product.id) &&
                editorStore.fieldToEdit === 'functions'
              "
              :value="
                editorStore.getArrayAsCST(editorStore.editingProduct.functions)
              "
              @input="updateArrayField($event, 'functions')"
            ></textarea>
            <span v-else>{{
              editorStore.getArrayAsCST(
                editorStore.displayProduct(product).functions
              )
            }}</span>
          </div>
          <div
            class="form-group"
            @click="editorStore.startEditing(product, 'options')"
          >
            <label>Опции (через запятую):</label>
            <textarea
              v-if="
                editorStore.isEditing(product.id) &&
                editorStore.fieldToEdit === 'options'
              "
              :value="
                editorStore.getArrayAsCST(editorStore.editingProduct.options)
              "
              @input="updateArrayField($event, 'options')"
            ></textarea>
            <span v-else>{{
              editorStore.getArrayAsCST(
                editorStore.displayProduct(product).options
              )
            }}</span>
          </div>
          <div
            class="form-group"
            @click="editorStore.startEditing(product, 'options-filters')"
          >
            <label>Опции-фильтры (через запятую):</label>
            <textarea
              v-if="
                editorStore.isEditing(product.id) &&
                editorStore.fieldToEdit === 'options-filters'
              "
              :value="
                editorStore.getArrayAsCST(
                  editorStore.editingProduct['options-filters']
                )
              "
              @input="updateArrayField($event, 'options-filters')"
            ></textarea>
            <span v-else>{{
              editorStore.getArrayAsCST(
                editorStore.displayProduct(product)['options-filters']
              )
            }}</span>
          </div>
          <div
            class="form-group"
            @click="editorStore.startEditing(product, 'autosygnals')"
          >
            <label>Раздел для автосигнализаций (через запятую):</label>
            <textarea
              v-if="
                editorStore.isEditing(product.id) &&
                editorStore.fieldToEdit === 'autosygnals'
              "
              :value="
                editorStore.getArrayAsCST(
                  editorStore.editingProduct.autosygnals
                )
              "
              @input="updateArrayField($event, 'autosygnals')"
            ></textarea>
            <span v-else>{{
              editorStore.getArrayAsCST(
                editorStore.displayProduct(product).autosygnals
              )
            }}</span>
          </div>
        </div>

        <Tabs />

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

const updateArrayField = (
  event: Event,
  fieldName: 'functions' | 'options' | 'options-filters' | 'autosygnals'
) => {
  const target = event.target as HTMLTextAreaElement;
  editorStore.updateArrayField(fieldName, target.value);
};

watch(
  () => props.product.gallery,
  (newGallery) => {
    if (editorStore.isEditing(props.product.id)) {
      editorStore.editingProduct.gallery = newGallery;
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

const handleToggle = (event: Event, product: ProductI) => {
  const detailsElement = event.target as HTMLDetailsElement;
  if (detailsElement.open) {
    editorStore.startEditing(product, '');
  } else {
    editorStore.cancelEditing();
  }
  emit('handle-toggle', event, product);
};

const iconUploader = ref<HTMLInputElement | null>(null);
const currentIconTarget = ref<{ tabIndex: number; itemIndex: number } | null>(
  null
);

const isUploading = ref<Record<string, boolean>>({});

async function onIconFileSelected(event: Event) {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  if (!file || !currentIconTarget.value) return;

  const { tabIndex, itemIndex } = currentIconTarget.value;
  if (!editorStore.editingProduct || !editorStore.editingProduct.tabs) return;

  const previewUrl = URL.createObjectURL(file);

  const newTabs = JSON.parse(JSON.stringify(editorStore.editingProduct.tabs));
  newTabs[tabIndex].description[itemIndex]['path-icon'] = previewUrl;
  editorStore.setTabs(newTabs);

  const key = `tab-${tabIndex}-${itemIndex}`;
  isUploading.value[key] = true;

  const formData = new FormData();
  formData.append('icon', file);
  formData.append('productId', editorStore.editingProduct.id);
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
    if (result.filePath && editorStore.editingProduct.tabs) {
      const finalTabs = JSON.parse(
        JSON.stringify(editorStore.editingProduct.tabs)
      );
      finalTabs[tabIndex].description[itemIndex]['path-icon'] = result.filePath;
      editorStore.setTabs(finalTabs);
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

function saveChanges() {
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
