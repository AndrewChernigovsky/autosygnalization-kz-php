import { ref } from 'vue';
import type { Ref } from 'vue';
import type { Product } from '../interfaces/Products';

const API_URL = '/server/php/admin/api/products/';

async function apiCall(
  endpoint: string,
  method: string = 'POST',
  body: any = null
) {
  const options: RequestInit = {
    method,
    headers: {},
  };

  if (body) {
    if (body instanceof FormData) {
      options.body = body;
    } else {
      options.headers = { 'Content-Type': 'application/json' };
      options.body = JSON.stringify(body);
    }
  }

  const response = await fetch(API_URL + endpoint, options);

  if (!response.ok) {
    const errorData = await response
      .json()
      .catch(() => ({ message: 'An unknown error occurred' }));
    throw new Error(
      errorData.message || `HTTP error! status: ${response.status}`
    );
  }

  return response.json();
}

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

  async function updateProduct(product: Product): Promise<boolean> {
    console.log('[useProducts] updateProduct вызван. Товар:', product);
    try {
      const productData = {
        id: product.id,
        title: product.title,
        description: product.description,
        price: product.price,
        is_popular: product.is_popular,
        gallery: product.gallery,
        category_key: product.category_key,
        model: product.model,
      };

      if (product.is_new) {
        console.log(
          '[useProducts] Это НОВЫЙ товар. Выполняется API-запрос на создание...'
        );
        const createdProduct = await apiCall(
          'create_product.php',
          'POST',
          productData
        );
        const index = products.value.findIndex((p) => p.id === product.id);
        if (index !== -1) {
          products.value[index].id = createdProduct.id;
          products.value[index].link = createdProduct.link;
          products.value[index].is_new = false;
        }
      } else {
        console.log(
          '[useProducts] Это СУЩЕСТВУЮЩИЙ товар. Выполняется API-запрос на обновление...'
        );
        const updatedData = await apiCall(
          'update_product.php',
          'POST',
          productData
        );
        const index = products.value.findIndex((p) => p.id === product.id);
        if (index !== -1) {
          products.value[index].link = updatedData.link;
        }
      }
      return true;
    } catch (error) {
      console.error('Failed to update product:', error);
      return false;
    }
  }

  async function deleteProduct(productId: string): Promise<boolean> {
    try {
      await apiCall('delete_product.php', 'POST', { id: productId });
      products.value = products.value.filter((p) => p.id !== productId);
      return true;
    } catch (error) {
      console.error('Failed to delete product:', error);
      return false;
    }
  }

  async function togglePopular(product: Product) {
    const updatedProduct = { ...product, is_popular: !product.is_popular };
    await updateProduct(updatedProduct);
  }

  async function deleteImage(product: Product, imageIndex: number) {
    try {
      const data = await apiCall('delete_image.php', 'POST', {
        productId: product.id,
        imageIndex,
      });
      const index = products.value.findIndex((p) => p.id === product.id);
      if (index !== -1) {
        products.value[index].gallery = data.gallery;
      }
    } catch (error) {
      console.error('Failed to delete image:', error);
    }
  }

  async function addProduct(category_key: string) {
    console.log(
      '[useProducts] addProduct вызван. Только локальные изменения, без API-запроса.'
    );
    // Эта функция теперь работает только на клиенте
    const newProduct: Product = {
      id: `new_${Date.now()}`, // Временный ID
      is_new: true,
      title: 'Новый товар',
      description: 'Введите описание...',
      price: 0,
      is_popular: false,
      gallery: [],
      category_key: category_key,
      category: category_key,
      // Заполняем остальные поля значениями по умолчанию, чтобы избежать ошибок
      model: '',
      cart: false,
      popular: false,
      currency: '₸',
      quantity: 0,
      link: '#',
      functions: [],
      options: [],
      'options-filters': [],
      is_special: false,
      autosygnals: [],
    };
    products.value.unshift(newProduct);
    return newProduct;
  }

  async function uploadImage(
    product: Product,
    file: File,
    imageIndex: number | null = null
  ): Promise<string[] | null> {
    const formData = new FormData();
    formData.append('productId', product.id);
    formData.append('image', file);
    if (imageIndex !== null) {
      formData.append('imageIndex', String(imageIndex));
    }

    // Если это новый продукт, отправляем текущую галерею, чтобы сервер мог её дополнить
    if (product.is_new) {
      formData.append('gallery', JSON.stringify(product.gallery));
    }

    try {
      const data = await apiCall('upload_image.php', 'POST', formData);
      const index = products.value.findIndex((p) => p.id === product.id);
      if (index !== -1) {
        products.value[index].gallery = data.gallery;
      }
      return data.gallery;
    } catch (error) {
      console.error('Failed to upload image:', error);
      return null;
    }
  }

  return {
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
  };
}
