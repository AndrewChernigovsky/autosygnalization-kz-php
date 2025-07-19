<template>
  <div class="products-admin">
    <h1>Товары</h1>
    <div class="p-4 md:p-6 theme-dark">
      <input
        type="file"
        ref="fileInput"
        @change="handleFileSelected"
        style="display: none"
        accept="image/*"
      />
      <div
        v-if="loading"
        class="flex justify-center items-center h-64 flex-col"
      >
        <div class="loader"></div>
        <p class="mt-4">Идет загрузка товаров...</p>
      </div>
      <div v-if="error" class="text-red-500 text-center">
        Ошибка при загрузке данных: {{ error }}
      </div>
      <div v-if="!loading && !error" class="space-y-2">
        <div v-for="(group, category) in groupedProducts" :key="category">
          <div class="category-header">
            <h2 class="text-2xl font-bold my-4">
              {{ getCategoryName(category) }}
            </h2>
            <button @click="addNewProduct(category)" class="btn-add">
              Добавить товар
            </button>
          </div>
          <div class="space-y-2">
            <details
              v-for="product in group"
              :key="product.id"
              class="product-item"
              :open="product.is_new"
              @toggle="(event) => handleToggle(event, product)"
            >
              <summary>
                <strong>{{ product.title }}</strong>
              </summary>
              <div class="product-editor">
                <div class="form-group" @click="startEditing(product, 'title')">
                  <label>Заголовок:</label>
                  <input
                    v-if="
                      editingProduct?.id === product.id &&
                      fieldToEdit === 'title'
                    "
                    v-model="editingProduct.title"
                    type="text"
                  />
                  <span v-else>{{ product.title }}</span>
                </div>

                <div
                  class="form-group"
                  @click="startEditing(product, 'description')"
                >
                  <label>Описание:</label>
                  <textarea
                    v-if="
                      editingProduct?.id === product.id &&
                      fieldToEdit === 'description'
                    "
                    v-model="editingProduct.description"
                  ></textarea>
                  <span v-else>{{ product.description }}</span>
                </div>

                <div class="form-group" @click="startEditing(product, 'price')">
                  <label>Цена:</label>
                  <input
                    v-if="
                      editingProduct?.id === product.id &&
                      fieldToEdit === 'price'
                    "
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
                    :checked="product.is_popular"
                    @change="togglePopular(product)"
                  />
                </div>

                <div class="gallery-manager">
                  <h4>Галерея:</h4>
                  <div class="gallery-images">
                    <div
                      v-for="(image, index) in product.gallery"
                      :key="index"
                      class="gallery-image"
                      @click="triggerFileUpload(product, index)"
                    >
                      <img :src="image" alt="Product image" />
                      <button
                        class="btn-delete-img"
                        @click.stop="deleteImage(product, index)"
                      ></button>
                    </div>
                    <div
                      class="gallery-upload-placeholder"
                      @click="triggerFileUpload(product, null)"
                    >
                      <span class="plus-icon">+</span>
                    </div>
                  </div>
                </div>
                <div class="product-actions">
                  <button @click="saveChanges" class="btn-save">
                    Сохранить
                  </button>
                  <button
                    @click="deleteProductHandler(product.id)"
                    class="btn-delete"
                  >
                    Удалить
                  </button>
                </div>
              </div>
            </details>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed, ref } from 'vue';
import { useProducts } from './functions/useProducts';
import type { Product } from './interfaces/Products';
import Swal from 'sweetalert2';

const {
  products,
  loading,
  error,
  fetchProducts,
  updateProduct,
  deleteProduct,
  togglePopular,
  deleteImage,
  uploadImage,
  addProduct,
} = useProducts();

const editingProduct = ref<Product | null>(null);
const fieldToEdit = ref<string | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);
const uploadContext = ref<{ product: Product; index: number | null } | null>(
  null
);
const isCreatingNewProduct = ref(false);

function startEditing(product: Product, field: string) {
  if (!editingProduct.value || editingProduct.value.id !== product.id) {
    editingProduct.value = { ...product };
  }
  fieldToEdit.value = field;
}

async function saveChanges() {
  if (editingProduct.value) {
    Swal.fire({
      title: 'Сохранение...',
      text: 'Пожалуйста, подождите',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      background: '#333',
      color: '#fff',
    });

    const updatedProduct = { ...editingProduct.value };
    const updated: boolean = await updateProduct(updatedProduct);

    if (updated) {
      if (updatedProduct.is_new) {
        isCreatingNewProduct.value = false;
      }
      editingProduct.value = null;
      fieldToEdit.value = null;

      // Manually update the product in the list to avoid full refresh
      const index = products.value.findIndex((p) => p.id === updatedProduct.id);
      if (index !== -1) {
        products.value[index] = { ...products.value[index], ...updatedProduct };
      }

      Swal.fire({
        title: 'Сохранено!',
        text: 'Товар был успешно обновлен.',
        icon: 'success',
        background: '#333',
        color: '#fff',
      });
    } else {
      Swal.fire({
        title: 'Ошибка!',
        text: 'Не удалось сохранить товар.',
        icon: 'error',
        background: '#333',
        color: '#fff',
      });
    }
  }
}

async function addNewProduct(categoryKey: string) {
  if (isCreatingNewProduct.value) {
    Swal.fire({
      title: 'Внимание!',
      text: 'Сначала сохраните или удалите предыдущий новый товар.',
      icon: 'warning',
      background: '#333',
      color: '#fff',
    });
    return;
  }

  Swal.fire({
    title: 'Добавление товара...',
    text: 'Пожалуйста, подождите',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
    background: '#333',
    color: '#fff',
  });

  addProduct(categoryKey).then((newProduct) => {
    if (newProduct) {
      isCreatingNewProduct.value = true;
      editingProduct.value = { ...newProduct };
      fieldToEdit.value = 'title';
      Swal.fire({
        title: 'Товар добавлен!',
        text: 'Теперь вы можете заполнить детали и сохранить.',
        icon: 'success',
        background: '#333',
        color: '#fff',
        timer: 1000,
        showConfirmButton: false,
      });
    } else {
      Swal.fire({
        title: 'Ошибка!',
        text: 'Не удалось добавить новый товар.',
        icon: 'error',
        background: '#333',
        color: '#fff',
      });
    }
  });
}

async function deleteProductHandler(productId: string) {
  const productToDelete = products.value.find((p) => p.id === productId);

  const result = await Swal.fire({
    title: 'Вы уверены?',
    text: 'Вы не сможете восстановить этот товар!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
    background: '#333',
    color: '#fff',
  });

  if (result.isConfirmed) {
    Swal.fire({
      title: 'Удаление...',
      text: 'Пожалуйста, подождите',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      background: '#333',
      color: '#fff',
    });

    const deleted = await deleteProduct(productId);

    if (deleted) {
      if (productToDelete?.is_new) {
        isCreatingNewProduct.value = false;
      }
      Swal.fire({
        title: 'Удалено!',
        text: 'Товар был успешно удален.',
        icon: 'success',
        background: '#333',
        color: '#fff',
      });
    } else {
      Swal.fire({
        title: 'Ошибка!',
        text: 'Не удалось удалить товар.',
        icon: 'error',
        background: '#333',
        color: '#fff',
      });
    }
  }
}

async function handleToggle(event: Event, product: Product) {
  const detailsElement = event.target as HTMLDetailsElement;
  if (!detailsElement.open && product.is_new) {
    event.preventDefault(); // Предотвращаем закрытие, пока пользователь не решит
    const result = await Swal.fire({
      title: 'Отменить создание?',
      text: 'Новый товар не был сохранен и будет удален.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Да, удалить',
      cancelButtonText: 'Нет, оставить',
      background: '#333',
      color: '#fff',
    });

    if (result.isConfirmed) {
      await deleteProduct(product.id);
      isCreatingNewProduct.value = false;
    } else {
      detailsElement.open = true; // Если отменили, оставляем открытым
    }
  }
}

function triggerFileUpload(product: Product, index: number | null) {
  uploadContext.value = { product, index };
  fileInput.value?.click();
}

async function handleFileSelected(event: Event) {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0] && uploadContext.value) {
    const { product, index } = uploadContext.value;

    Swal.fire({
      title: 'Загрузка изображения...',
      text: 'Пожалуйста, подождите',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      background: '#333',
      color: '#fff',
    });

    const newGallery = await uploadImage(product, target.files[0], index);

    if (newGallery) {
      if (editingProduct.value && editingProduct.value.id === product.id) {
        editingProduct.value.gallery = newGallery;
      }
      Swal.fire({
        title: 'Успешно!',
        text: 'Изображение загружено.',
        icon: 'success',
        background: '#333',
        color: '#fff',
        timer: 1500,
        showConfirmButton: false,
      });
    } else {
      Swal.fire({
        title: 'Ошибка!',
        text: 'Не удалось загрузить изображение.',
        icon: 'error',
        background: '#333',
        color: '#fff',
      });
    }
  }
  // Reset file input
  if (target) target.value = '';
}

const categoryTranslations: Record<string, string> = {
  keychain: 'Брелоки',
  'park-systems': 'Парковочные системы',
  'remote-controls': 'Пульты управления',
  starline_a60: 'StarLine A60',
  starline_a63: 'StarLine A63',
  starline_a90: 'StarLine A90',
  starline_a91: 'StarLine A91',
  starline_a93: 'StarLine A93',
  starline_a96: 'StarLine A96',
  starline_b96: 'StarLine B96',
  starline_b97: 'StarLine B97',
  starline_d96: 'StarLine D96',
  starline_e96: 'StarLine E96',
  starline_s96: 'StarLine S96',
  starline_t94: 'StarLine T94',
  uncategorized: 'Без категории',
};

const getCategoryName = (categoryKey: string) => {
  return categoryTranslations[categoryKey] || categoryKey;
};

const groupedProducts = computed(() => {
  return products.value.reduce((acc, product) => {
    const category = product.category_key || 'uncategorized';
    if (!acc[category]) {
      acc[category] = [];
    }
    acc[category].push(product);
    return acc;
  }, {} as Record<string, Product[]>);
});

onMounted(() => {
  fetchProducts();
});
</script>

<style scoped lang="scss">
.products-admin {
  h1 {
    text-align: center;
  }
}
.theme-dark {
  background-color: #333;
  color: #f1f1f1;
  min-height: 100vh;
  padding: 20px;
  max-width: 1440px;
  border-radius: 10px;
  margin: 0 auto;
}

.space-y-2 {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.category-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.theme-dark h2 {
  color: #fff;
  margin: 0; /* Reset margin */
}

.loader {
  border: 4px solid #555;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin: auto auto;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.product-item {
  border: 1px solid #555;
  border-radius: 8px;
  background-color: #444;
}

.product-item summary {
  padding: 15px;
  cursor: pointer;
  background-color: #3a3a3a;
  color: #fff;
  border-radius: 8px;
  transition: background-color 0.2s;
}

.product-item summary:hover {
  background-color: #4f4f4f;
}

.product-item[open] > summary {
  border-bottom: 1px solid #555;
  border-radius: 8px 8px 0 0;
}

.product-editor {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group,
.form-group-checkbox {
  display: flex;
  flex-direction: column;
  gap: 5px;
}
.form-group-checkbox {
  flex-direction: row;
  align-items: center;
}

.form-group label {
  font-weight: bold;
  font-size: 0.9rem;
}

.form-group input[type='text'],
.form-group input[type='number'],
.form-group textarea,
.form-group span {
  width: 100%;
  padding: 10px;
  border: 1px solid #666;
  background-color: #555;
  color: #fff;
  border-radius: 4px;
  box-sizing: border-box;
  font-size: 1rem;
  min-height: 40px; /* Для span */
}

.form-group textarea {
  min-height: 120px;
  resize: vertical;
}

.gallery-manager {
  border-top: 1px solid #555;
  padding-top: 15px;
}

.mt-4 {
  margin: 0 auto;
  text-align: center;
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

.product-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  border-top: 1px solid #555;
  padding-top: 15px;
  margin-top: 10px;
}

.btn-save,
.btn-delete {
  padding: 8px 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  color: white;
  font-size: 0.9rem;
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

.btn-add {
  background-color: #007bff;
  padding: 8px 12px;
  border-radius: 5px;
  color: white;
  border: none;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-add:hover {
  background-color: #0069d9;
}
</style>
