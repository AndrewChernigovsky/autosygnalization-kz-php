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

  // Приведение значений, приходящих с сервера (1/0, '1'/'0', 'true'/'false', true/false) к строгому boolean
  function toBoolean(value: any): boolean {
    return value === true || value === 1 || value === '1' || value === 'true';
  }

  async function fetchProducts() {
    loading.value = true;
    error.value = null;
    try {
      const response = await fetch(
        '/server/php/api/products/get_all_products.php?is_published=true'
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
                if (
                  parsed.price_list &&
                  typeof parsed.price_list === 'string'
                ) {
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
                if (
                  parsed['options-filters'] &&
                  typeof parsed['options-filters'] === 'string'
                ) {
                  parsed['options-filters'] = JSON.parse(
                    parsed['options-filters']
                  );
                }
              } catch (e) {
                parsed['options-filters'] = parsed['options-filters'] || [];
              }
              try {
                if (
                  parsed.autosygnals &&
                  typeof parsed.autosygnals === 'string'
                ) {
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
                is_popular: toBoolean(parsed.is_popular),
                is_special: toBoolean(parsed.is_special),
                is_published: toBoolean(parsed.is_published),
              };
            });
            allProducts.push(...productsWithCategory);
          }
        }
      }
      products.value = allProducts;
      console.log(products.value, 'PRODUCTS');
      if (products.value.length > 0) {
        console.log(
          '[DIAG] is_published typeof/value:',
          typeof products.value[0].is_published,
          products.value[0].is_published
        );
      }
    } catch (e: any) {
      console.error('Ошибка при получении или обработке продуктов:', e);
      error.value = e.message;
    } finally {
      loading.value = false;
    }
  }

  async function updateProduct(product: ProductI): Promise<boolean> {
    console.log('\n');
    console.log(
      '▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒'
    );
    console.log('🔧 [useProducts.ts] updateProduct - НАЧАЛО');
    console.log(
      '▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒'
    );

    try {
      const productData = {
        id: product.id,
        model: product.model,
        title: product.title,
        description: product.description,
        price: product.price,
        is_published: product.is_published,
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


      console.log('📦 [useProducts.ts] productData ПЕРЕД санитизацией:', {
        id: productData.id,
        title: productData.title,
        tabsCount: productData.tabs?.length || 0,
      });

      if (productData.tabs) {
        console.log('📊 [useProducts.ts] productData.tabs ПЕРЕД санитизацией:');
        productData.tabs.forEach((tab: any, tIdx: number) => {
          console.log(`  Вкладка [${tIdx}]: ${tab.title}`);
          if (tab.content) {
            tab.content.forEach((item: any, iIdx: number) => {
              console.log(
                `    Элемент [${tIdx}][${iIdx}]: "${item.title}" → path-icon: "${item['path-icon']}"`
              );
            });
          }
        });
      }

      // Sanitize tabs: ensure preview blob: URLs are not sent to the server
      console.log('\n🧹 [useProducts.ts] Санитизация tabs...');
      if (productData.tabs && Array.isArray(productData.tabs)) {
        try {
          let blobCount = 0;
          for (let t = 0; t < productData.tabs.length; t++) {
            const tab = productData.tabs[t];
            if (!tab || !Array.isArray(tab.content)) continue;
            for (let i = 0; i < tab.content.length; i++) {
              const item = tab.content[i];
              if (
                item &&
                typeof item['path-icon'] === 'string' &&
                item['path-icon'].startsWith('blob:')
              ) {
                console.log(
                  `🧹 [useProducts.ts] Заменяем blob на пустую строку [${t}][${i}]:`,
                  item['path-icon']
                );
                item['path-icon'] = '';
                blobCount++;
              }
            }
          }
          console.log(
            `✅ [useProducts.ts] Санитизация завершена, заменено blob URLs: ${blobCount}`
          );
        } catch (e) {
          console.error('❌ [useProducts.ts] Ошибка санитизации:', e);
          productData.tabs = [];
        }
      }

      if (productData.tabs) {
        console.log('📊 [useProducts.ts] productData.tabs ПОСЛЕ санитизации:');
        productData.tabs.forEach((tab: any, tIdx: number) => {
          console.log(`  Вкладка [${tIdx}]: ${tab.title}`);
          if (tab.content) {
            tab.content.forEach((item: any, iIdx: number) => {
              const icon = item['path-icon'];
              console.log(
                `    Элемент [${tIdx}][${iIdx}]: "${item.title}" → ${icon ? '✅' : '❌'
                } path-icon: "${icon}"`
              );
            });
          }
        });
      }

      if (product.is_new) {
        const createdProduct = await apiCall(
          'create_product.php',
          'POST',
          productData
        );

        console.log('✅ [useProducts.ts] Товар создан, получен ответ:', {
          oldId: product.id,
          newId: createdProduct.id,
          hasTabs: !!createdProduct.tabs,
        });

        const index = products.value.findIndex((p) => p.id === product.id);
        if (index !== -1) {
          products.value[index].id = createdProduct.id;
          products.value[index].link = createdProduct.link;
          products.value[index].is_new = false;
          if (createdProduct.price_list) {
            products.value[index].price_list = createdProduct.price_list;
          }
          // ✅ ОБНОВЛЯЕМ TABS из ответа create_product.php (там пути переименованы!)
          if (createdProduct.tabs) {
            console.log(
              '✅ [useProducts.ts] Обновляем tabs из ответа create_product.php'
            );
            console.log('  Старые tabs:', products.value[index].tabs);
            console.log('  Новые tabs:', createdProduct.tabs);
            products.value[index].tabs = createdProduct.tabs;
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

      console.log('✅ [useProducts.ts] updateProduct УСПЕШНО');
      console.log(
        '▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒'
      );
      console.log('🔧 [useProducts.ts] updateProduct - КОНЕЦ');
      console.log(
        '▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒\n'
      );
      return true;
    } catch (error) {
      console.error('❌ [useProducts.ts] updateProduct ОШИБКА:', error);
      console.log(
        '▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒\n'
      );
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
      is_published: false,
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
    formData.append('productTitle', product.title || 'product'); // Передаем название товара
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

export async function handleToggle(
  event: Event,
  product: ProductI
): Promise<boolean> {
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
