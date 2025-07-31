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
      <div v-if="loading" class="flex justify-center items-center h-64">
        <Loader message="Идет загрузка товаров..." />
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
            <button @click="addProduct(category)" class="btn-add">
              Добавить товар
            </button>
          </div>
          <div class="space-y-2">
            <Product
              v-for="product in group"
              :key="product.id"
              :product="product"
              :all-categories="allCategories"
              :is-image-uploading="isImageUploading"
              :get-category-name="getCategoryName"
              @save-product="saveChanges"
              @delete-product="deleteProductHandler"
              @delete-image="deleteImage"
              @trigger-file-upload="triggerFileUpload"
              @handle-toggle="handleToggle"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed, ref } from 'vue';
import { useProducts } from './functions/useProducts';
import type { ProductI } from './interfaces/Products';
import Swal from 'sweetalert2';
import Loader from '../../UI/Loader.vue';
import Product from './Product.vue';

const {
  products,
  loading,
  error,
  fetchProducts,
  updateProduct,
  deleteProduct,
  deleteImage,
  uploadImage,
  addProduct,
} = useProducts();

const fileInput = ref<HTMLInputElement | null>(null);
const uploadContext = ref<{ product: ProductI; index: number | null } | null>(
  null
);
const isCreatingNewProduct = ref(false);
const imageUploadStatus = ref<{
  productId: string;
  index: number | null;
} | null>(null);

const isImageUploading = (productId: string, index: number | null) => {
  if (!imageUploadStatus.value) return false;
  return (
    imageUploadStatus.value.productId === productId &&
    imageUploadStatus.value.index === index
  );
};

async function saveChanges(product: ProductI) {
  console.log('[Products.vue] saveChanges called', product);
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

  console.log(product, 'product');

  const updated: boolean = await updateProduct(product);

  if (updated) {
    if (product.is_new) {
      isCreatingNewProduct.value = false;
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

async function handleToggle(event: Event, product: ProductI) {
  const detailsElement = event.target as HTMLDetailsElement;
  if (!detailsElement.open && product.is_new) {
    event.preventDefault();
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
      detailsElement.open = true;
    }
  }
}

function triggerFileUpload(product: ProductI, index: number | null) {
  uploadContext.value = { product, index };
  fileInput.value?.click();
}

async function handleFileSelected(event: Event) {
  const target = event.target as HTMLInputElement;
  if (!target.files || !target.files[0] || !uploadContext.value) {
    if (target) target.value = '';
    return;
  }

  const { product, index } = uploadContext.value;
  const file = target.files[0];

  imageUploadStatus.value = { productId: product.id, index };

  try {
    const newGallery = await uploadImage(product, file, index);
    if (newGallery) {
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
      throw new Error('Upload failed and returned no gallery.');
    }
  } catch (err) {
    console.error('Failed to upload image:', err);
    Swal.fire({
      title: 'Ошибка!',
      text: 'Не удалось загрузить изображение.',
      icon: 'error',
      background: '#333',
      color: '#fff',
    });
  } finally {
    imageUploadStatus.value = null;
    if (target) target.value = '';
  }
}

const categoryTranslations: Record<string, string> = {
  keychain: 'Брелоки',
  'park-systems': 'Парковочные системы',
  'remote-controls': 'Пульты управления',
};

const allCategories = computed(() => {
  const keysFromProducts = Object.keys(groupedProducts.value);
  const keysFromTranslations = Object.keys(categoryTranslations);
  const allKeys = [...new Set([...keysFromProducts, ...keysFromTranslations])];
  return allKeys
    .map((key) => ({
      key: key,
      name: getCategoryName(key),
    }))
    .sort((a, b) => a.name.localeCompare(b.name));
});

const getCategoryName = (categoryKey: string) => {
  return categoryTranslations[categoryKey] || categoryKey;
};

const groupedProducts = computed(() => {
  return products.value.reduce((acc, product) => {
    const category = product.category || 'uncategorized';
    if (!acc[category]) {
      acc[category] = [];
    }
    acc[category].push(product);
    return acc;
  }, {} as Record<string, ProductI[]>);
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
  padding: 15px;
  padding-right: 60px;
  background-color: #2a2a2a;
  border-radius: 5px;
  position: sticky;
  top: -20px;
  z-index: 100;
}

.theme-dark h2 {
  color: #fff;
  margin: 0;
}

.mt-4 {
  margin: 0 auto;
  text-align: center;
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
