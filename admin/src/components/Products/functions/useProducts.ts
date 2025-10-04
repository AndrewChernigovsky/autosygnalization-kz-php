import { ref } from 'vue';
import type { Ref } from 'vue';
import type { ProductI } from '../interfaces/Products';
import Swal from 'sweetalert2';

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
  const products: Ref<ProductI[]> = ref([]);
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

      const allProducts: ProductI[] = [];
      if (data && data.category) {
        for (const categoryKey in data.category) {
          if (
            Object.prototype.hasOwnProperty.call(data.category, categoryKey)
          ) {
            const categoryProducts = data.category[categoryKey];
            const productsWithCategory = categoryProducts.map((p: any) => {
              // normalize JSON fields that may come as strings from the API
              const parsed: any = { ...p };
              try {
                if (parsed.gallery && typeof parsed.gallery === 'string') {
                  parsed.gallery = JSON.parse(parsed.gallery);
                }
              } catch (e) {
                parsed.gallery = parsed.gallery || [];
              }
              try {
                if (parsed.price_list && typeof parsed.price_list === 'string') {
                  parsed.price_list = JSON.parse(parsed.price_list);
                }
              } catch (e) {
                parsed.price_list = parsed.price_list || [];
              }
              try {
                if (parsed.functions && typeof parsed.functions === 'string') {
                  parsed.functions = JSON.parse(parsed.functions);
                }
              } catch (e) {
                parsed.functions = parsed.functions || [];
              }
              try {
                if (parsed.options && typeof parsed.options === 'string') {
                  parsed.options = JSON.parse(parsed.options);
                }
              } catch (e) {
                parsed.options = parsed.options || [];
              }
              try {
                if (parsed['options-filters'] && typeof parsed['options-filters'] === 'string') {
                  parsed['options-filters'] = JSON.parse(parsed['options-filters']);
                }
              } catch (e) {
                parsed['options-filters'] = parsed['options-filters'] || [];
              }
              try {
                if (parsed.autosygnals && typeof parsed.autosygnals === 'string') {
                  parsed.autosygnals = JSON.parse(parsed.autosygnals);
                }
              } catch (e) {
                parsed.autosygnals = parsed.autosygnals || [];
              }
              try {
                if (parsed.tabs && typeof parsed.tabs === 'string') {
                  parsed.tabs = JSON.parse(parsed.tabs);
                }
              } catch (e) {
                parsed.tabs = parsed.tabs || [];
              }

              return {
                ...parsed,
                category: categoryKey,
                is_popular: parsed.is_popular ?? false,
                is_special: parsed.is_special ?? false,
              };
            });
            allProducts.push(...productsWithCategory);
          }
        }
      }
      products.value = allProducts;
      console.log(products.value, 'PRODUCTS');
    } catch (e: any) {
      console.error('Ошибка при получении или обработке продуктов:', e);
      error.value = e.message;
    } finally {
      loading.value = false;
    }
  }

  async function updateProduct(product: ProductI): Promise<boolean> {
    try {
      const productData = {
        id: product.id,
        model: product.model,
        title: product.title,
        description: product.description,
        price: product.price,
        is_popular: product.is_popular,
        is_special: product.is_special,
        gallery: product.gallery,
        category: product.category,
        link: product.link,
        functions: product.functions,
        options: product.options,
        'options-filters': product['options-filters'],
        autosygnals: product.autosygnals,
        tabs: product.tabs,
        price_list: product.price_list,
      };
      console.log(productData, 'PRODUCT DATA');

      if (product.is_new) {
        console.log('Creating product payload:', productData);
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
          if (createdProduct.price_list) {
            products.value[index].price_list = createdProduct.price_list;
          }
        }
      } else {
        const updatedData = await apiCall(
          'update_product.php',
          'POST',
          productData
        );

        const index = products.value.findIndex((p) => p.id === product.id);
        if (index !== -1) {
          products.value[index].link = updatedData.link;
          if (updatedData.price_list) {
            products.value[index].price_list = updatedData.price_list;
          }
        }
      }

      // --- Новый вызов API для сохранения цен-услуг ---

      let prices = [];
      if (Array.isArray(product.prices)) {
        prices = product.prices
          .filter(
            (item) => item && typeof item === 'object' && 'description' in item
          )
          .map((item: any) => ({
            id: item.id || null,
            description: item.description,
            installationPrice: item.installationPrice || '',
          }));
        await apiCall('update_prices_products.php', 'POST', {
          id: product.id,
          prices,
        });
      }
      // --- конец нового блока ---

      return true;
    } catch (error) {
      console.error('[useProducts.ts] updateProduct error:', error);
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

  async function togglePopular(product: ProductI) {
    const updatedProduct = { ...product, is_popular: !product.is_popular };
    await updateProduct(updatedProduct);
  }

  async function deleteImage(product: ProductI, imageIndex: number) {
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

  async function addProduct(category: string) {
    // Эта функция теперь работает только на клиенте
    const newProduct: ProductI = {
      id: `new_${Date.now()}`, // Временный ID
      is_new: true,
      model: '',
      title: 'Новый товар',
      description: 'Введите описание...',
      price: 0,
      is_popular: false,
      is_special: false,
      gallery: [],
      category: category,
      link: '#',
      functions: [],
      options: [],
      'options-filters': [],
      autosygnals: [],
      price_list: [],
      tabs: [],
    };
    products.value.push(newProduct);
    console.log(products.value, 'PRODUCTS ADD PRODUCT');
    return newProduct;
  }

  async function uploadImage(
    product: ProductI,
    file: File,
    imageIndex: number | null = null
  ): Promise<string[] | null> {
    const formData = new FormData();
    formData.append('productId', product.id);
    formData.append('image', file);
    if (imageIndex !== null) {
      formData.append('imageIndex', String(imageIndex));
    }

    // В новой логике мы всегда передаем галерею, если она есть
    if (product.gallery && product.gallery.length > 0) {
      formData.append('gallery', JSON.stringify(product.gallery));
    }

    try {
      const data = await apiCall('upload_image.php', 'POST', formData);
      const index = products.value.findIndex((p) => p.id === product.id);
      if (index !== -1) {
        // Обновляем галерею в локальном состоянии, чтобы Vue среагировал
        products.value[index].gallery = data.gallery;
      }
      return data.gallery;
    } catch (error) {
      console.error('Failed to upload image:', error);
      return null;
    }
  }

  async function uploadTabIcon(
    productId: string,
    tabIndex: number,
    itemIndex: number,
    file: File
  ): Promise<string | null> {
    const formData = new FormData();
    formData.append('productId', productId);
    formData.append('tabIndex', String(tabIndex));
    formData.append('itemIndex', String(itemIndex));
    formData.append('path-icon', file);

    try {
      const data = await apiCall('upload_tab_icon.php', 'POST', formData);
      return data.filePath;
    } catch (error) {
      console.error('Failed to upload tab icon:', error);
      return null;
    }
  }

  async function deleteTabIcon(
    productId: string,
    tabIndex: number,
    itemIndex: number
  ) {
    try {
      await apiCall('delete_tab_icon.php', 'POST', {
        productId,
        tabIndex,
        itemIndex,
      });
      return true;
    } catch (error) {
      console.error('Failed to delete tab icon:', error);
      return false;
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
    uploadTabIcon,
    deleteTabIcon,
  };
}

export async function handleToggle(event: Event, product: ProductI): Promise<boolean> {
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
      // Caller should handle deletion when this function returns true
      return true;
    } else {
      detailsElement.open = true;
      return false;
    }
  }
  return false;
}
