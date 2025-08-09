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
              @delete-image="handleDeleteImage"
              @trigger-file-upload="triggerFileUpload"
              @handle-toggle="handleToggle"
              @cancel-editing="handleCancelEditing"
              @stage-tab-icon="handleStageTabIcon"
              @delete-tab-icon="handleDeleteTabIcon"
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
import { useProductEditorStore } from '../../stores/productEditorStore';

const {
  products,
  loading,
  error,
  fetchProducts,
  updateProduct,
  deleteProduct,
  uploadImage,
  addProduct,
  uploadTabIcon,
  deleteTabIcon: apiDeleteTabIcon,
} = useProducts();

const productEditorStore = useProductEditorStore();
const fileInput = ref<HTMLInputElement | null>(null);
const uploadContext = ref<{ product: ProductI; index: number | null } | null>(
  null
);
const imageUploadStatus = ref<{
  productId: string;
  index: number | null;
} | null>(null);

const filesToUpload = ref<
  Map<string, { file: File; blobUrl: string; originalIndex: number | null }[]>
>(new Map());

const tabIconsToUpload = ref<
  Map<
    string,
    {
      tabIndex: number;
      itemIndex: number;
      file: File;
      blobUrl: string;
    }[]
  >
>(new Map());

const isImageUploading = (productId: string, index: number | null) => {
  if (!imageUploadStatus.value) return false;
  return (
    imageUploadStatus.value.productId === productId &&
    imageUploadStatus.value.index === index
  );
};

const handleCancelEditing = (product: ProductI) => {
  const galleryFiles = filesToUpload.value.get(product.id);
  if (galleryFiles) {
    galleryFiles.forEach(({ blobUrl }) => URL.revokeObjectURL(blobUrl));
    filesToUpload.value.delete(product.id);
  }
  const iconFiles = tabIconsToUpload.value.get(product.id);
  if (iconFiles) {
    iconFiles.forEach(({ blobUrl }) => URL.revokeObjectURL(blobUrl));
    tabIconsToUpload.value.delete(product.id);
  }
};

async function saveChanges(product: ProductI) {
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

  const productRef = products.value.find((p) => p.id === product.id) || product;

  // 1. Загрузка изображений галереи
  const galleryFiles = filesToUpload.value.get(product.id) || [];
  if (galleryFiles.length > 0) {
    productRef.gallery = productRef.gallery.filter(
      (url) => !url.startsWith('blob:')
    );

    for (const fileData of galleryFiles) {
      const uploadedGallery = await uploadImage(
        productRef,
        fileData.file,
        fileData.originalIndex
      );
      if (uploadedGallery) {
        productRef.gallery = uploadedGallery;
      }
    }
    filesToUpload.value.delete(product.id);
    galleryFiles.forEach(({ blobUrl }) => URL.revokeObjectURL(blobUrl));
    product.gallery = productRef.gallery;
  }

  // 2. Загрузка иконок вкладок
  const iconFiles = tabIconsToUpload.value.get(product.id) || [];
  if (iconFiles.length > 0) {
    for (const iconData of iconFiles) {
      const newPath = await uploadTabIcon(
        product.id,
        iconData.tabIndex,
        iconData.itemIndex,
        iconData.file
      );
      if (newPath && product.tabs) {
        product.tabs[iconData.tabIndex].content[iconData.itemIndex].icon =
          newPath;
      }
    }
    tabIconsToUpload.value.delete(product.id);
    iconFiles.forEach(({ blobUrl }) => URL.revokeObjectURL(blobUrl));
  }

  // 3. Сохранение основного продукта
  const updated: boolean = await updateProduct(product);
  if (updated) {
    Swal.fire('Сохранено!', 'Товар был успешно обновлен.', 'success');
  } else {
    Swal.fire('Ошибка!', 'Не удалось сохранить товар.', 'error');
  }
}
async function deleteProductHandler(productId: string) {
  const productToDelete = products.value.find((p) => p.id === productId);
  if (productToDelete?.is_new) {
    products.value = products.value.filter((p) => p.id !== productId);
    handleCancelEditing(productToDelete); // Очистка всех временных файлов
    return;
  }

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
      Swal.fire('Удалено!', 'Товар был успешно удален.', 'success');
    } else {
      Swal.fire('Ошибка!', 'Не удалось удалить товар.', 'error');
    }
  }
}
async function handleDeleteImage(product: ProductI, imageIndex: number) {
  const productInState =
    productEditorStore.editingProduct?.id === product.id
      ? productEditorStore.editingProduct
      : product;
  if (!productInState) return;

  const imageUrl = productInState.gallery[imageIndex];

  if (imageUrl.startsWith('blob:')) {
    const files = filesToUpload.value.get(product.id) || [];
    const fileIndex = files.findIndex((f) => f.blobUrl === imageUrl);
    if (fileIndex !== -1) {
      URL.revokeObjectURL(imageUrl);
      files.splice(fileIndex, 1);
      filesToUpload.value.set(product.id, files);
    }
  }
  // Simply remove from the array in the local state.
  // The server will handle the actual file deletion on save.
  productInState.gallery.splice(imageIndex, 1);
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
      deleteProductHandler(product.id);
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

  const productInState =
    productEditorStore.editingProduct?.id === product.id
      ? productEditorStore.editingProduct
      : product;

  const blobUrl = URL.createObjectURL(file);
  const productFiles = filesToUpload.value.get(product.id) || [];
  // Save the original index for replacement purposes
  productFiles.push({ file, blobUrl, originalIndex: index });
  filesToUpload.value.set(product.id, productFiles);

  if (index !== null) {
    const oldUrl = productInState.gallery[index];
    if (oldUrl.startsWith('blob:')) {
      URL.revokeObjectURL(oldUrl);
      const files = filesToUpload.value.get(product.id) || [];
      const fileIndex = files.findIndex((f) => f.blobUrl === oldUrl);
      if (fileIndex !== -1) files.splice(fileIndex, 1);
    }
    productInState.gallery[index] = blobUrl;
  } else {
    productInState.gallery.push(blobUrl);
  }
  if (target) target.value = '';
}

function handleStageTabIcon(
  productId: string,
  tabIndex: number,
  itemIndex: number,
  file: File
) {
  const productInState = productEditorStore.editingProduct;
  if (!productInState || productInState.id !== productId) return;

  const blobUrl = URL.createObjectURL(file);
  const productIcons = tabIconsToUpload.value.get(productId) || [];
  productIcons.push({ tabIndex, itemIndex, file, blobUrl });
  tabIconsToUpload.value.set(productId, productIcons);

  if (productInState.tabs) {
    productInState.tabs[tabIndex].content[itemIndex].icon = blobUrl;
  }
}

function handleDeleteTabIcon(
  productId: string,
  tabIndex: number,
  itemIndex: number
) {
  const productInState = productEditorStore.editingProduct;
  if (!productInState || productInState.id !== productId) return;

  const iconUrl = productInState.tabs?.[tabIndex].content[itemIndex].icon || '';

  if (iconUrl.startsWith('blob:')) {
    const icons = tabIconsToUpload.value.get(productId) || [];
    const iconIndex = icons.findIndex((i) => i.blobUrl === iconUrl);
    if (iconIndex !== -1) {
      URL.revokeObjectURL(iconUrl);
      icons.splice(iconIndex, 1);
      tabIconsToUpload.value.set(productId, icons);
    }
    if (productInState.tabs) {
      productInState.tabs[tabIndex].content[itemIndex].icon = '';
    }
  } else {
    apiDeleteTabIcon(productId, tabIndex, itemIndex).then((success) => {
      if (success && productInState.tabs) {
        productInState.tabs[tabIndex].content[itemIndex].icon = '';
      }
    });
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
