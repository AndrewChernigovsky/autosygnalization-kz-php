import { defineStore } from 'pinia';
import type { ProductI, Tab } from '../components/Products/interfaces/Products';

interface ProductEditorState {
  editingProduct: ProductI | null;
  fieldToEdit: string | null;
  isUploading: Record<string, boolean>;
}

export const useProductEditorStore = defineStore('productEditor', {
  state: (): ProductEditorState => ({
    editingProduct: null,
    fieldToEdit: null,
    isUploading: {},
  }),
  getters: {
    displayProduct(state) {
      return (originalProduct: ProductI) => {
        if (
          state.editingProduct &&
          state.editingProduct.id === originalProduct.id
        ) {
          return state.editingProduct;
        }
        return originalProduct;
      };
    },
    isEditing(state) {
      return (productId: string) => state.editingProduct?.id === productId;
    },
    getArrayAsCST() {
      return (arr: string[] | undefined): string => {
        if (!arr || arr.length === 0) return 'Нет';
        return arr.join(', ');
      };
    },
  },
  actions: {
    startEditing(product: ProductI, field: string) {
      if (!this.editingProduct || this.editingProduct.id !== product.id) {
        const productCopy = JSON.parse(JSON.stringify(product));
        if (productCopy.tabs) {
          if (Array.isArray(productCopy.tabs)) {
            // Если это массив, просто нормализуем поле description
            productCopy.tabs.forEach((tab: any) => {
              if (tab.description) {
                tab.content = tab.description;
                delete tab.description;
              }
            });
          } else if (typeof productCopy.tabs === 'object') {
            // Если это объект, трансформируем его в массив вкладок
            productCopy.tabs = Object.entries(productCopy.tabs).map(
              ([title, content]) => ({ title, content })
            );
          }
        }
        this.editingProduct = productCopy;
      }
      this.fieldToEdit = field;
    },
    cancelEditing() {
      this.editingProduct = null;
      this.fieldToEdit = null;
    },
    updateArrayField(
      fieldName: 'functions' | 'options' | 'options-filters' | 'autosygnals',
      value: string
    ) {
      if (this.editingProduct) {
        const values = value
          .split(',')
          .map((s) => s.trim())
          .filter(Boolean);
        if (fieldName === 'options-filters') {
          (this.editingProduct as any)['options-filters'] = values;
        } else {
          (this.editingProduct as any)[fieldName] = values;
        }
      }
    },
    handleCheckboxChange(field: 'is_popular' | 'is_special') {
      if (this.editingProduct) {
        this.editingProduct[field] = !this.editingProduct[field];
        this.fieldToEdit = field;
      }
    },
    setTabs(tabs: Tab[] | undefined | null) {
      if (this.editingProduct) {
        this.editingProduct.tabs = JSON.parse(JSON.stringify(tabs || []));
      }
    },
    addTab() {
      if (this.editingProduct) {
        if (!this.editingProduct.tabs) {
          this.editingProduct.tabs = [];
        }
        this.editingProduct.tabs.push({
          title: 'Новая вкладка',
          content: [
            {
              title: 'Новый пункт',
              'path-icon': '',
              description: 'Описание нового пункта',
            },
          ],
        });
      }
    },
    removeTab(tabIndex: number) {
      if (this.editingProduct && this.editingProduct.tabs) {
        this.editingProduct.tabs.splice(tabIndex, 1);
      }
    },
    addDescriptionItem(tabIndex: number) {
      if (
        this.editingProduct &&
        this.editingProduct.tabs &&
        this.editingProduct.tabs[tabIndex]
      ) {
        if (!this.editingProduct.tabs[tabIndex].content) {
          this.editingProduct.tabs[tabIndex].content = [];
        }
        this.editingProduct.tabs[tabIndex].content.push({
          title: 'Новый пункт',
          description: 'Описание нового пункта',
          'path-icon': '',
        });
      }
    },
    removeDescriptionItem(tabIndex: number, itemIndex: number) {
      if (
        this.editingProduct &&
        this.editingProduct.tabs &&
        this.editingProduct.tabs[tabIndex]?.content
      ) {
        this.editingProduct.tabs[tabIndex].content.splice(itemIndex, 1);
      }
    },
  },
});
