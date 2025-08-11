<script setup lang="ts">
import { watch, onMounted, defineExpose } from 'vue';
import { QuillEditor } from '@rafaeljunioxavier/vue-quill-fix';
import '@rafaeljunioxavier/vue-quill-fix/dist/vue-quill.snow.css';
import type { ProductI } from './interfaces/Products';
import { useProductEditorStore } from '../../stores/productEditorStore';
import { useProductPricesStore } from '../../stores/productPricesStore';

const props = defineProps<{
  product: ProductI;
}>();

const editorStore = useProductEditorStore();
const pricesStore = useProductPricesStore();

const toolbarOptions = [
  ['bold', 'italic', 'underline'],
  [{ list: 'ordered' }, { list: 'bullet' }],
  [{ header: [1, 2, 3, false] }],
  ['link'],
  ['clean'],
];

// Инициализация при монтировании
onMounted(async () => {
  if (editorStore.isEditing(props.product.id)) {
    loadPricesFromProduct();
    try {
      await pricesStore.getAllProductsItems();
    } catch (error) {
      console.error('Error in onMounted:', error);
    }
  }
});

// Отслеживание изменений в редактируемом продукте
watch(
  () => editorStore.editingProduct,
  (newEditingProduct, oldEditingProduct) => {
    if (newEditingProduct?.id === props.product.id) {
      loadPricesFromProduct();
      pricesStore.setEditingProductId(props.product.id);
    } else if (
      newEditingProduct === null &&
      oldEditingProduct?.id === props.product.id
    ) {
      // Сохраняем цены обратно в editingProduct перед очисткой
      syncPricesToProduct();
      pricesStore.clearPrices();
    }
  },
  { deep: true }
);

const loadPricesFromProduct = () => {
  let productPrices =
    editorStore.editingProduct?.prices || props.product.prices;

  // Преобразуем массив объектов с content в массив с description и автозаполнением остальных полей
  if (Array.isArray(productPrices) && productPrices.length > 0) {
    if (typeof productPrices[0] === 'object' && 'content' in productPrices[0]) {
      productPrices = productPrices.map((item: any) => ({
        id: item.id || null,
        title: props.product.title || '',
        productPrice: props.product.price ? String(props.product.price) : '',
        currency: '₸',
        installationPrice: 'price' in item ? item.price : '',
        description: item.content || '',
      }));
    } else {
      // Если уже массив объектов с description, но нет других полей — дополняем
      productPrices = productPrices.map((item: any) => ({
        id: 'id' in item ? item.id : null,
        title: 'title' in item ? item.title : props.product.title || '',
        productPrice:
          'productPrice' in item
            ? item.productPrice
            : props.product.price
            ? String(props.product.price)
            : '',
        currency: 'currency' in item ? item.currency : '₸',
        installationPrice:
          'installationPrice' in item ? item.installationPrice : '',
        description: item.description || '',
      }));
    }
  }
  pricesStore.loadPricesFromProduct(productPrices);
};

const syncPricesToProduct = () => {
  if (
    editorStore.editingProduct &&
    pricesStore.editingProductId === props.product.id
  ) {
    const prices = pricesStore.getSerializedPrices();
    console.log('[Prices.vue] syncPricesToProduct', prices);
    editorStore.editingProduct.prices = prices;
  }
};

// Обработчики input событий
// const handleTitleInput = (index: number, event: Event) => {
//   const target = event.target as HTMLInputElement;
//   pricesStore.updatePriceItem(index, 'title', target.value);
// };

// const handleProductPriceInput = (index: number, event: Event) => {
//   const target = event.target as HTMLInputElement;
//   pricesStore.updatePriceItem(index, 'productPrice', target.value);
// };

// const handleCurrencyInput = (index: number, event: Event) => {
//   const target = event.target as HTMLInputElement;
//   pricesStore.updatePriceItem(index, 'currency', target.value);
// };

const handleInstallationPriceInput = (index: number, event: Event) => {
  const target = event.target as HTMLInputElement;
  pricesStore.updatePriceItem(index, 'installationPrice', target.value);
};

defineOptions({
  name: 'Prices',
});

defineExpose({ syncPricesToProduct });
</script>

<template>
  <div class="prices-editor">
    <h1>Цены на установку автосигнализаций</h1>

    <div v-if="editorStore.isEditing(product.id)" class="editing-mode">
      <div class="form-group">
        <label>Список цен:</label>
        <div class="price-items">
          <div
            v-for="(priceItem, index) in pricesStore.prices"
            :key="index"
            class="price-item"
          >
            <div class="price-item-header">
              <button
                type="button"
                class="remove-item-btn"
                @click="pricesStore.removePriceItem(index)"
                title="Удалить элемент"
              >
                ✕
              </button>
              <span class="item-number">{{ index + 1 }}</span>
            </div>

            <!-- <div class="price-item-fields">
              <div class="field-group">
                <label>Название товара:</label>
                <input
                  :value="priceItem.title"
                  @input="(e) => handleTitleInput(index, e)"
                  type="text"
                  placeholder="Установка автосигнализации"
                />
              </div>

              <div class="field-group">
                <label>Цена товара:</label>
                <input
                  :value="priceItem.productPrice"
                  @input="(e) => handleProductPriceInput(index, e)"
                  type="text"
                  placeholder="259 600"
                />
              </div> -->

            <!-- <div class="field-group">
                <label>Валюта:</label>
                <input
                  :value="priceItem.currency"
                  @input="(e) => handleCurrencyInput(index, e)"
                  type="text"
                  placeholder="₸"
                />
              </div> -->

            <div class="field-group">
              <label>Цена установки:</label>
              <input
                :value="priceItem.installationPrice"
                @input="(e: Event) => handleInstallationPriceInput(index, e)"
                type="text"
                placeholder="60 000"
              />
            </div>

            <div class="field-group">
              <label>Описание (список преимуществ):</label>
              <QuillEditor
                :key="'price-' + index"
                theme="snow"
                :toolbar="toolbarOptions"
                contentType="html"
                :content="priceItem.description"
                @update:content="
                  (val: string) => pricesStore.updatePriceDescription(index, val)
                "
              />
            </div>
          </div>
        </div>

        <button
          type="button"
          class="add-item-btn"
          @click="pricesStore.addPriceItem"
        >
          + Добавить элемент цены
        </button>
      </div>
    </div>

    <div v-else class="display-mode">
      <div class="price-display">
        <div v-if="!pricesStore.hasValidData" class="no-prices">
          Список цен пуст. Нажмите для редактирования.
        </div>
        <div v-else>
          <div
            v-for="(priceItem, index) in pricesStore.prices"
            :key="index"
            class="price-display-item"
          >
            <div class="price-header">
              <h3>{{ priceItem.title || 'Без названия' }}</h3>
              <div class="price-info">
                <span class="product-price"
                  >{{ priceItem.productPrice }} {{ priceItem.currency }}</span
                >
                <span class="installation-price"
                  >установка от {{ priceItem.installationPrice }}
                  {{ priceItem.currency }}</span
                >
              </div>
            </div>
            <div class="price-description" v-html="priceItem.description"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.prices-editor {
  margin-top: 20px;
  padding: 20px;
  border: 1px solid #444;
  border-radius: 8px;
  background-color: #2a2a2a;
}

.prices-editor h1 {
  color: #fff;
  margin-bottom: 20px;
  font-size: 1.5em;
}

.editing-mode .form-group {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.editing-mode .form-group > label {
  font-weight: bold;
  color: #ccc;
  font-size: 1.1em;
}

.price-items {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.price-item {
  padding: 15px;
  border: 1px solid #555;
  border-radius: 6px;
  background-color: #333;
  position: relative;
}

.price-item-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 15px;
  padding-bottom: 10px;
  border-bottom: 1px solid #444;
}

.item-number {
  font-weight: bold;
  color: #4caf50;
  font-size: 1.1em;
}

.remove-item-btn {
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 50%;
  width: 25px;
  height: 25px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  transition: background-color 0.2s;
}

.remove-item-btn:hover {
  background: #c82333;
}

.price-item-fields {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.price-item-fields .field-group:last-child {
  grid-column: 1 / -1;
}

.field-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.field-group label {
  font-size: 0.9em;
  color: #bbb;
  font-weight: 500;
}

.field-group input {
  padding: 8px 12px;
  background-color: #444;
  border: 1px solid #555;
  color: #fff;
  border-radius: 4px;
  font-size: 14px;
}

.field-group input:focus {
  outline: none;
  border-color: #4caf50;
}

.add-item-btn {
  padding: 12px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: background-color 0.2s;
}

.add-item-btn:hover {
  background-color: #218838;
}

/* Display Mode Styles */
.display-mode {
  color: #ccc;
}

.no-prices {
  padding: 40px;
  text-align: center;
  color: #777;
  font-style: italic;
}

.price-display-item {
  margin-bottom: 20px;
  padding: 15px;
  border: 1px solid #444;
  border-radius: 6px;
  background-color: #333;
}

.price-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  flex-wrap: wrap;
  gap: 10px;
}

.price-header h3 {
  color: #4caf50;
  margin: 0;
  font-size: 1.2em;
}

.price-info {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 5px;
}

.product-price {
  font-size: 1.1em;
  font-weight: bold;
  color: #fff;
}

.installation-price {
  font-size: 0.9em;
  color: #aaa;
}

.price-description {
  color: #ccc;
  line-height: 1.6;
}

.price-description :deep(ul) {
  margin: 0;
  padding-left: 20px;
}

.price-description :deep(li) {
  margin-bottom: 5px;
}

/* Quill Editor Customization */
:deep(.ql-toolbar) {
  border-top: 1px solid #555;
  border-left: 1px solid #555;
  border-right: 1px solid #555;
  background-color: #444;
}

:deep(.ql-container) {
  border-bottom: 1px solid #555;
  border-left: 1px solid #555;
  border-right: 1px solid #555;
  background-color: #333;
  color: #fff;
}

:deep(.ql-editor) {
  color: #fff;
  min-height: 120px;
}

@media (max-width: 768px) {
  .price-item-fields {
    grid-template-columns: 1fr;
  }

  .price-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .price-info {
    align-items: flex-start;
  }
}
</style>
