<template>
  <div class="p-4 md:p-6 theme-dark">
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="loader"></div>
    </div>
    <div v-if="error" class="text-red-500 text-center">
      Ошибка при загрузке данных: {{ error }}
    </div>
    <div v-if="!loading && !error" class="space-y-2">
      <div v-for="(group, category) in groupedProducts" :key="category">
        <h2 class="text-2xl font-bold my-4">
          {{ getCategoryName(category) }}
        </h2>
        <div class="space-y-2">
          <details
            v-for="product in group"
            :key="product.id"
            class="product-item"
          >
            <summary>
              <strong>{{ product.title }}</strong>
            </summary>
            <div class="product-editor">
              <div class="form-group" @click="startEditing(product, 'title')">
                <label>Заголовок:</label>
                <span
                  v-if="
                    editingProduct?.id !== product.id || fieldToEdit !== 'title'
                  "
                >
                  {{ product.title }}
                </span>
                <input
                  v-else
                  v-model="editingProduct.title"
                  type="text"
                  @blur="finishEditing"
                  @keyup.enter="finishEditing"
                />
              </div>

              <div
                class="form-group"
                @click="startEditing(product, 'description')"
              >
                <label>Описание:</label>
                <span
                  v-if="
                    editingProduct?.id !== product.id ||
                    fieldToEdit !== 'description'
                  "
                >
                  {{ product.description }}
                </span>
                <textarea
                  v-else
                  v-model="editingProduct.description"
                  @blur="finishEditing"
                  @keyup.enter="finishEditing"
                ></textarea>
              </div>

              <div class="form-group" @click="startEditing(product, 'price')">
                <label>Цена:</label>
                <span
                  v-if="
                    editingProduct?.id !== product.id || fieldToEdit !== 'price'
                  "
                >
                  {{ product.price }}
                </span>
                <input
                  v-else
                  v-model="editingProduct.price"
                  type="number"
                  @blur="finishEditing"
                  @keyup.enter="finishEditing"
                />
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
                  >
                    <img :src="image" alt="Product image" />
                    <button
                      class="btn-delete-img"
                      @click="deleteImage(product, index)"
                    >
                      X
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </details>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed, ref } from 'vue';
import { useProducts } from './functions/useProducts';
import type { Product } from './interfaces/Products';

const {
  products,
  loading,
  error,
  fetchProducts,
  updateProduct,
  togglePopular,
  deleteImage,
} = useProducts();

const editingProduct = ref<Product | null>(null);
const fieldToEdit = ref<string | null>(null);

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

function startEditing(product: Product, field: string) {
  editingProduct.value = { ...product };
  fieldToEdit.value = field;
}

function finishEditing() {
  if (editingProduct.value) {
    updateProduct(editingProduct.value);
    editingProduct.value = null;
    fieldToEdit.value = null;
  }
}

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

<style scoped>
.theme-dark {
  background-color: #333;
  color: #f1f1f1;
  min-height: 100vh;
}

.theme-dark h2 {
  color: #fff;
}

.loader {
  border: 4px solid #555;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
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
.form-group textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #666;
  background-color: #555;
  color: #fff;
  border-radius: 4px;
  box-sizing: border-box;
  font-size: 1rem;
}

.form-group textarea {
  min-height: 120px;
  resize: vertical;
}

.gallery-manager {
  border-top: 1px solid #555;
  padding-top: 15px;
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

.gallery-image {
  position: relative;
  width: 100px;
  height: 100px;
}

.gallery-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 4px;
  border: 1px solid #666;
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
  font-size: 12px;
  line-height: 24px;
  text-align: center;
  font-weight: bold;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
</style>
