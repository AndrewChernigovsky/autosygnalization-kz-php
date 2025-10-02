<template>
  <div class="products-admin">
    <h1 class="my-title">Товары</h1>
    <div class="p-4 md:p-6 theme-dark">
      <input type="file" ref="fileInput" @change="handleFileSelected" style="display: none" accept="image/*" />
      <div v-if="error" class="text-red-500 text-center">
        Ошибка при загрузке данных: {{ error }}
      </div>
      <div v-if="!loading && !error" class="space-y-2">
        <div v-for="(group, category) in groupedProducts" :key="category">
          <div class="category-header">
            <div class="category-header-content">
              <h2 class="text-2xl font-bold my-4">
                {{ getCategoryName(category) }}
              </h2>
              <MyBtn variant="primary" @click="toggleAccardion(category)">
                {{ openAccardions[category] ? 'Закрыть' : 'Открыть' }}
              </MyBtn>
            </div>
            <MyTransition>
              <div class="space-y-2 product-list" v-if="openAccardions[category]">
                <Product v-for="product in group" :key="product.id" :product="product" :all-categories="allCategories"
                  :is-image-uploading="isImageUploading" :get-category-name="getCategoryName"
                  :is-adding-new-product="isAddingNewProduct" @save-product="saveChanges"
                  @delete-product="deleteProductHandler" @delete-image="handleDeleteImage"
                  @trigger-file-upload="triggerFileUpload" @handle-toggle="handleToggle"
                  @cancel-editing="handleCancelEditing" @stage-tab-icon="handleStageTabIcon"
                  @delete-tab-icon="handleDeleteTabIcon" />
                <MyBtn variant="secondary" @click="handleAddProduct(category)" class="btn-add"
                  :disabled="isAddingNewProduct">
                  Добавить товар
                </MyBtn>
              </div>
            </MyTransition>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed, ref, watchEffect } from 'vue';
import { useProducts } from './functions/useProducts';
import type { ProductI } from './interfaces/Products';
import Swal from 'sweetalert2';
import Product from './Product.vue';
import MyBtn from '../UI/MyBtn.vue';
import MyTransition from '../UI/MyTransition.vue';

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

const fileInput = ref<HTMLInputElement | null>(null);
const isAddingNewProduct = ref(false);
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

const openAccardions = ref<Record<string, boolean>>({});

const toggleAccardion = (productId: string) => {
  openAccardions.value[productId] = !openAccardions.value[productId];
};

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
        product.tabs[iconData.tabIndex].content[iconData.itemIndex]["path-icon"] =
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
    isAddingNewProduct.value = false;
  } else {
    Swal.fire('Ошибка!', 'Не удалось сохранить товар.', 'error');
  }
}
async function deleteProductHandler(productId: string) {
  const productToDelete = products.value.find((p) => p.id === productId);
  if (productToDelete?.is_new) {
    products.value = products.value.filter((p) => p.id !== productId);
    isAddingNewProduct.value = false;
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
  const productInState = product;
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
      isAddingNewProduct.value = false;
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

  const productInState = product;

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
  const productInState = products.value.find((p) => p.id === productId);
  if (!productInState) return;

  const blobUrl = URL.createObjectURL(file);
  const productIcons = tabIconsToUpload.value.get(productId) || [];
  productIcons.push({ tabIndex, itemIndex, file, blobUrl });
  tabIconsToUpload.value.set(productId, productIcons);

  if (productInState.tabs) {
    productInState.tabs[tabIndex].content[itemIndex]["path-icon"] = blobUrl;
  }
}

function handleDeleteTabIcon(
  productId: string,
  tabIndex: number,
  itemIndex: number
) {
  const productInState = products.value.find((p) => p.id === productId);
  if (!productInState) return;

  const iconUrl = productInState.tabs?.[tabIndex].content[itemIndex]["path-icon"] || '';

  if (iconUrl.startsWith('blob:')) {
    const icons = tabIconsToUpload.value.get(productId) || [];
    const iconIndex = icons.findIndex((i) => i.blobUrl === iconUrl);
    if (iconIndex !== -1) {
      URL.revokeObjectURL(iconUrl);
      icons.splice(iconIndex, 1);
      tabIconsToUpload.value.set(productId, icons);
    }
    if (productInState.tabs) {
      productInState.tabs[tabIndex].content[itemIndex]["path-icon"] = '';
    }
  } else {
    apiDeleteTabIcon(productId, tabIndex, itemIndex).then((success) => {
      if (success && productInState.tabs) {
        productInState.tabs[tabIndex].content[itemIndex]["path-icon"] = '';
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

const handleAddProduct = (category: string) => {
  addProduct(category);
  isAddingNewProduct.value = true;
  Swal.fire({
    title: 'Добавлена форма для нового товара',
    text: 'Заполните данные и нажмите "Сохранить изменения".',
    icon: 'info',
    background: 'white',
    color: 'black',
    timer: 3000,
    showConfirmButton: false,
  });
};

watchEffect(() => {
  if (loading.value) {
    Swal.fire({
      title: 'Загрузка товаров...',
      text: 'Пожалуйста, подождите',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      background: 'white',
      color: 'black',
    });
  } else {
    Swal.close();
  }
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

.product-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.category-header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.category-header {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 20px;
  border-radius: 5px;
  position: sticky;
  top: -20px;
  z-index: 100;
  gap: 20px;
  border: 1px solid white;
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
  max-width: 100%;
  width: 100%;
  min-height: 60px;
  align-self: flex-end;
  flex-grow: 1;
}

.btn-add:hover {
  background-color: #0069d9;
}

.my-title {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  font-size: 32px;
  font-weight: bold;
  padding-left: 20px;
  margin: 0;
}
</style>
