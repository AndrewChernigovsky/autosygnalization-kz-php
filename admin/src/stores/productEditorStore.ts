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
        this.editingProduct = JSON.parse(JSON.stringify(product));
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
          description: [
            {
              title: 'Новый пункт',
              icon: '',
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
        if (!this.editingProduct.tabs[tabIndex].description) {
          this.editingProduct.tabs[tabIndex].description = [];
        }
        this.editingProduct.tabs[tabIndex].description.push({
          title: 'Новый пункт',
          description: 'Описание нового пункта',
          icon: '',
        });
      }
    },
    removeDescriptionItem(tabIndex: number, itemIndex: number) {
      if (
        this.editingProduct &&
        this.editingProduct.tabs &&
        this.editingProduct.tabs[tabIndex]?.description
      ) {
        this.editingProduct.tabs[tabIndex].description.splice(itemIndex, 1);
      }
    },
  },
});
