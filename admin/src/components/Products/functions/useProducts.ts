import { ref } from 'vue';
import type { Ref } from 'vue';
import type { Product } from '../interfaces/Products';

export function useProducts() {
  const products: Ref<Product[]> = ref([]);
  const loading = ref(false);
  const error = ref<string | null>(null);

  async function fetchProducts() {
    loading.value = true;
    error.value = null;
    try {
      const response = await fetch(
        '/server/php/api/products/get_all_products.php'
      );
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();

      const allProducts: Product[] = [];
      if (data && data.category) {
        for (const categoryKey in data.category) {
          if (
            Object.prototype.hasOwnProperty.call(data.category, categoryKey)
          ) {
            const categoryProducts = data.category[categoryKey];
            const productsWithCategory = categoryProducts.map((p: any) => ({
              ...p,
              category_key: categoryKey,
              is_popular: p.popular ?? false,
            }));
            allProducts.push(...productsWithCategory);
          }
        }
      }
      products.value = allProducts;
    } catch (e: any) {
      console.error('Ошибка при получении или обработке продуктов:', e);
      error.value = e.message;
    } finally {
      loading.value = false;
    }
  }

  async function updateProduct(product: Product) {
    console.log('Updating product:', product);
    // TODO: Implement server-side update logic
  }

  async function togglePopular(product: Product) {
    console.log('Toggling popular for product:', product);
    // TODO: Implement server-side toggle logic
  }

  async function deleteImage(product: Product, imageIndex: number) {
    console.log(
      'Deleting image for product:',
      product,
      'at index:',
      imageIndex
    );
    // TODO: Implement server-side delete logic
  }

  return {
    products,
    loading,
    error,
    fetchProducts,
    updateProduct,
    togglePopular,
    deleteImage,
  };
}
