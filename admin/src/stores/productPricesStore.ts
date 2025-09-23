import { defineStore } from 'pinia';
import type {
  PriceItem,
  ProductI,
} from '../components/Products/interfaces/Products';

export const useProductPricesStore = defineStore('productPrices', {
  state: () => ({
    prices: [] as PriceItem[],
    editingProductId: null as string | null,
    allProducts: [] as ProductI[],
  }),
  getters: {
    getPrices(state) {
      return state.prices;
    },
    getAllProducts(state) {
      return state.allProducts;
    },
    hasValidData(state) {
      return state.prices.length > 0;
    },
  },
  actions: {
    async getAllProductsItems() {
      try {
        // Your implementation to fetch products
        const response = await fetch(
          '/server/php/api/products/get_all_products.php'
        );
        this.allProducts = await response.json();
        return this.allProducts;
      } catch (error) {
        console.error('Error fetching products:', error);
        throw error;
      }
    },
    setPrices(prices: PriceItem[]) {
      this.prices = [...prices];
    },

    setEditingProductId(productId: string | null) {
      this.editingProductId = productId;
    },

    loadPricesFromProduct(productPrices: PriceItem[] | string | undefined) {
      if (!productPrices) {
        this.prices = [];
        return;
      }

      try {
        // Если prices уже массив объектов
        if (Array.isArray(productPrices)) {
          this.prices = [...productPrices];
          return;
        }

        // Если prices строка JSON
        if (typeof productPrices === 'string') {
          this.prices = JSON.parse(productPrices);
          return;
        }

        this.prices = [];
      } catch (e) {
        console.error('Ошибка парсинга цен:', e);
        this.prices = [];
      }
    },

    addPriceItem() {
      const newItem: PriceItem = {
        title: 'Новая услуга',
        productPrice: '0',
        currency: '₸',
        installationPrice: '0',
        description: '<ul><li>Описание услуги</li></ul>',
      };

      this.prices.push(newItem);
    },

    removePriceItem(index: number) {
      if (index >= 0 && index < this.prices.length) {
        this.prices.splice(index, 1);
      }
    },

    updatePriceItem(index: number, field: keyof PriceItem, value: string) {
      if (index >= 0 && index < this.prices.length) {
        this.prices[index][field] = value;
      }
    },

    updatePriceDescription(index: number, newDescription: string) {
      if (index >= 0 && index < this.prices.length) {
        this.prices[index].description = newDescription;
      }
    },

    clearPrices() {
      this.prices = [];
      this.editingProductId = null;
    },

    // Возвращает копию цен для сохранения в продукт
    getSerializedPrices(): PriceItem[] {
      return JSON.parse(JSON.stringify(this.prices));
    },
  },
});
