<script setup lang="ts">
import { onMounted, computed, ref, watchEffect } from 'vue';
import { useProducts } from './functions/useProducts';
import type { ProductI } from './interfaces/Products';
import Swal from 'sweetalert2';
import Product from './Product/Product.vue';
import MyBtn from '../UI/MyBtn.vue';
import MyTransition from '../UI/MyTransition.vue';
// handleToggle from useProducts removed — parent controls open state via openProductId

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

// Отдельное хранилище для путей к иконкам
// Ключ: productId, значение: Map<"tabIndex_itemIndex", serverPath>
const tabIconPaths = ref<Map<string, Map<string, string>>>(new Map());

const openAccardions = ref<Record<string, boolean>>({});
const openProductId = ref<string | null>(null);
const dirtyMap = ref<Record<string, boolean>>({});
const productRefs = ref<Record<string, any>>({});

function registerProductRef(el: any, id: string) {
  if (el) {
    productRefs.value[id] = el;
  } else {
    delete productRefs.value[id];
  }
}

function handleDirtyState(productId: string, state: boolean) {
  dirtyMap.value[productId] = !!state;
  if (state) {
    // non-blocking toast to inform user that editing started
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'info',
      title: 'Есть несохранённые изменения',
      showConfirmButton: false,
      timer: 1500,
      background: '#333',
      color: '#fff',
    });
  }
}

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

// Проверка и подтверждение переключения между товарами при наличии несохранённых изменений
async function handleToggleWithCheck(_: Event, product: ProductI) {
  // find the product component that was open
  const openId = openProductId.value;
  if (!openId) {
    // no open product -> open requested
    openProductId.value = product.id;
    return;
  }

  if (openId === product.id) {
    // same product clicked -> close it
    openProductId.value = null;
    return;
  }
  // If open product is not dirty — just switch
  if (!dirtyMap.value[openId]) {
    openProductId.value = product.id;
    return;
  }

  // ask the corresponding Product component whether it is dirty
  const result = await Swal.fire({
    title: 'Есть несохранённые изменения?',
    text: 'У текущего открытого товара есть несохранённые изменения. Сохранить перед переключением?',
    icon: 'warning',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: 'Сохранить',
    denyButtonText: 'Не сохранять',
    cancelButtonText: 'Отмена',
    background: '#333',
    color: '#fff',
  });

  if (result.isConfirmed) {
    // Save current open product: call saveChanges and await result
    const current = products.value.find((p) => p.id === openId);
    if (current) {
      // Try to get editing payload from child component instance
      const child = productRefs.value[openId];
      let payload = current;
      try {
        if (child && typeof child.getEditingProduct === 'function') {
          const editing = child.getEditingProduct();
          if (editing) payload = editing;
        }
      } catch (e) {
        console.error('Failed to get editing payload from child:', e);
      }
      const updated = await saveChanges(payload);
      if (updated) {
        dirtyMap.value[current.id] = false;
        openProductId.value = product.id;
      } else {
        Swal.fire(
          'Ошибка',
          'Не удалось сохранить товар. Оставляюсь на текущем товаре.',
          'error'
        );
      }
    } else {
      openProductId.value = product.id;
    }
  } else if (result.isDenied) {
    // Discard changes: we simply close the current and open new
    openProductId.value = product.id;
  } else {
    // Cancel - do nothing
  }
}

async function saveChanges(product: ProductI) {
  console.log('\n\n');
  console.log(
    '████████████████████████████████████████████████████████████████████████'
  );
  console.log('💾💾💾 [PRODUCTS.VUE] saveChanges - НАЧАЛО СОХРАНЕНИЯ 💾💾💾');
  console.log(
    '████████████████████████████████████████████████████████████████████████'
  );
  console.log('📦 [PRODUCTS.VUE] Получен product от Product.vue:', {
    id: product.id,
    title: product.title,
    tabsCount: product.tabs?.length || 0,
    is_new: product.is_new,
  });

  if (product.tabs) {
    console.log('📊 [PRODUCTS.VUE] product.tabs ПОЛУЧЕНЫ от Product.vue:');
    product.tabs.forEach((tab: any, tIdx: number) => {
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
  console.log('🔍 [PRODUCTS.VUE] productRef создан/найден:', {
    id: productRef.id,
    'productRef === product': productRef === product,
  });
  if (
    product.tabs &&
    product.tabs[0] &&
    product.tabs[0].content &&
    product.tabs[0].content[0]
  ) {
    console.log(
      '🔍 [SAVE] product.tabs[0].content[0]:',
      product.tabs[0].content[0]
    );
    console.log(
      '🔍 [SAVE] product.tabs[0].content[0][path-icon]:',
      product.tabs[0].content[0]['path-icon']
    );
  }

  // Important: merge updated tabs and price_list from the edited payload into the local productRef
  // editingProduct is a deep clone; ensure productRef contains the latest tabs and price_list before uploads
  if (product && productRef && product !== productRef) {
    // Merge primitive and structured fields from edited payload into productRef
    const simpleFields = [
      'model',
      'title',
      'description',
      'price',
      'is_published',
      'is_popular',
      'is_special',
    ];
    for (const f of simpleFields) {
      if (Object.prototype.hasOwnProperty.call(product, f)) {
        // @ts-ignore
        productRef[f] = (product as any)[f];
      }
    }

    const arrayFields = [
      'functions',
      'options',
      'options-filters',
      'autosygnals',
      // НЕ включаем 'gallery' - она обрабатывается отдельно через filesToUpload
    ];
    for (const af of arrayFields) {
      if (Object.prototype.hasOwnProperty.call(product, af)) {
        try {
          // deep clone to avoid retaining references to child component reactive objects
          // @ts-ignore
          productRef[af] = JSON.parse(
            JSON.stringify((product as any)[af] || [])
          );
        } catch (e) {
          // fallback shallow copy
          // @ts-ignore
          productRef[af] = (product as any)[af] || [];
        }
      }
    }
    
    // Галерея: НЕ копируем из product, оставляем productRef.gallery как есть
    // Она будет обновлена ниже только если есть файлы в filesToUpload

    if (product.tabs) {
      try {
        const newTabs = JSON.parse(JSON.stringify(product.tabs));

        // УМНОЕ СЛИЯНИЕ: сохраняем валидные серверные пути из productRef
        if (productRef.tabs && Array.isArray(productRef.tabs)) {
          newTabs.forEach((tab: any, tIdx: number) => {
            if (
              tab?.content &&
              productRef.tabs &&
              productRef.tabs[tIdx]?.content
            ) {
              tab.content.forEach((item: any, iIdx: number) => {
                const oldPath =
                  productRef.tabs &&
                  productRef.tabs[tIdx]?.content[iIdx]?.['path-icon'];
                const newPath = item['path-icon'];

                // Если в productRef валидный серверный путь, а в editingProduct blob - сохраняем старый
                if (
                  oldPath &&
                  !oldPath.startsWith('blob:') &&
                  oldPath.startsWith('/') &&
                  (!newPath || newPath.startsWith('blob:'))
                ) {
                  console.log(
                    `🔍 [SAVE] Сохраняем серверный путь для [${tIdx}][${iIdx}]:`,
                    oldPath
                  );
                  item['path-icon'] = oldPath;
                } else if (newPath && !newPath.startsWith('blob:')) {
                  console.log(
                    `🔍 [SAVE] Используем новый путь для [${tIdx}][${iIdx}]:`,
                    newPath
                  );
                } else {
                  console.log(
                    `🔍 [SAVE] Оставляем blob для последующей загрузки [${tIdx}][${iIdx}]:`,
                    newPath
                  );
                }
              });
            }
          });
        }

        productRef.tabs = newTabs;
      } catch (e) {
        console.error('❌ [PRODUCTS.VUE] Ошибка при умном копировании:', e);
        productRef.tabs = product.tabs;
      }
    }
    if (product.price_list) {
      try {
        productRef.price_list = JSON.parse(JSON.stringify(product.price_list));
      } catch (e) {
        productRef.price_list = product.price_list;
      }
    }
  }

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
  console.log('\n');
  console.log('░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░');
  console.log('📤 [PRODUCTS.VUE] ЗАГРУЗКА ИКОНОК - НАЧАЛО');
  console.log('░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░');

  const stagedIcons = tabIconsToUpload.value.get(product.id) || [];
  console.log(
    '📋 [PRODUCTS.VUE] Иконок в очереди (tabIconsToUpload):',
    stagedIcons.length
  );

  if (stagedIcons.length > 0) {
    console.log('📋 [PRODUCTS.VUE] Список иконок для загрузки:');
    stagedIcons.forEach((icon, idx) => {
      console.log(
        `  [${idx}] tab[${icon.tabIndex}][${icon.itemIndex}] - ${icon.file.name}`
      );
    });

    for (let i = 0; i < stagedIcons.length; i++) {
      const iconData = stagedIcons[i];
      console.log(
        `\n🔄 [PRODUCTS.VUE] Загружаем иконку ${i + 1}/${stagedIcons.length}:`,
        {
          tabIndex: iconData.tabIndex,
          itemIndex: iconData.itemIndex,
          fileName: iconData.file.name,
          blobUrl: iconData.blobUrl,
        }
      );

      const newPath = await uploadTabIcon(
        product.id,
        iconData.tabIndex,
        iconData.itemIndex,
        iconData.file
      );

      console.log('✅ [PRODUCTS.VUE] API вернул путь:', newPath);

      if (newPath && productRef.tabs) {
        productRef.tabs[iconData.tabIndex].content[iconData.itemIndex][
          'path-icon'
        ] = newPath;
        // @ts-ignore
        iconData.__uploadedPath = newPath;
        console.log(
          `✅ [PRODUCTS.VUE] Путь обновлен в productRef.tabs[${iconData.tabIndex}][${iconData.itemIndex}]:`,
          newPath
        );
      } else {
        console.error('❌ [PRODUCTS.VUE] НЕ удалось загрузить иконку!');
      }
    }

    console.log('\n📊 [PRODUCTS.VUE] productRef.tabs ПОСЛЕ загрузки иконок:');
    if (productRef.tabs) {
      productRef.tabs.forEach((tab: any, tIdx: number) => {
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
  } else {
    console.log('ℹ️ [PRODUCTS.VUE] Нет иконок для загрузки');
  }

  console.log('░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░');
  console.log('📤 [PRODUCTS.VUE] ЗАГРУЗКА ИКОНОК - КОНЕЦ');
  console.log('░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░\n');

  // Дополнительная проверка: если в tabs остались blob: ссылки (например, при сбое immediate upload),
  // попытаемся сопоставить их со staged файлами и загрузить.
  console.log(
    '🔍 [SAVE] Проверяем blob ссылки в productRef.tabs:',
    productRef.tabs
  );
  if (productRef.tabs && productRef.tabs.length > 0) {
    for (let t = 0; t < productRef.tabs.length; t++) {
      const tab = productRef.tabs[t];
      if (!tab || !Array.isArray(tab.content)) continue;
      for (let i = 0; i < tab.content.length; i++) {
        const url = tab.content[i]['path-icon'] || '';
        console.log('🔍 [SAVE] Проверяем URL:', t, i, url);
        if (typeof url === 'string' && url.startsWith('blob:')) {
          console.log('🔍 [SAVE] Найден blob URL, ищем сохраненный путь');
          // Проверяем, есть ли уже загруженный путь в нашем хранилище
          const iconKey = `${t}_${i}`;
          const storedPath = tabIconPaths.value.get(product.id)?.get(iconKey);
          console.log(
            '🔍 [SAVE] Ищем сохраненный путь для:',
            iconKey,
            'найден:',
            storedPath
          );
          if (storedPath) {
            console.log('🔍 [SAVE] Используем сохраненный путь:', storedPath);
            productRef.tabs[t].content[i]['path-icon'] = storedPath;
          } else {
            console.log(
              '🔍 [SAVE] НЕ найден сохраненный путь, ищем staged файл'
            );
            // Найти staged файл по blobUrl среди stagedIcons
            const staged =
              stagedIcons.find((s) => s.blobUrl === url) ||
              (tabIconsToUpload.value.get(product.id) || []).find(
                (s) => s.blobUrl === url
              );
            console.log('🔍 [SAVE] Найден staged:', staged);
            if (staged) {
              try {
                console.log('🔍 [SAVE] Загружаем staged файл');
                const uploaded = await uploadTabIcon(
                  product.id,
                  t,
                  i,
                  staged.file
                );
                if (uploaded) {
                  console.log('🔍 [SAVE] Загружен новый путь:', uploaded);
                  productRef.tabs[t].content[i]['path-icon'] = uploaded;
                  // Сохраняем путь в нашем хранилище
                  if (!tabIconPaths.value.has(product.id)) {
                    tabIconPaths.value.set(product.id, new Map());
                  }
                  tabIconPaths.value.get(product.id)!.set(iconKey, uploaded);
                }
                try {
                  URL.revokeObjectURL(url);
                } catch (e) {}
              } catch (e) {
                console.error(
                  'Failed to upload staged icon for',
                  product.id,
                  t,
                  i,
                  e
                );
              }
            }
          }
        }
      }
    }
  }

  // cleanup staged entries and revoke any remaining blob urls
  if (stagedIcons.length > 0) {
    for (const s of stagedIcons) {
      try {
        if (s.blobUrl) URL.revokeObjectURL(s.blobUrl);
      } catch (e) {}
    }
    tabIconsToUpload.value.delete(product.id);
  }

  // 3. Перед отправкой удалим любые оставшиеся локальные blob: ссылки из tabs,
  // чтобы они не попали в базу. Если для blob найдётся staged файл, выше он уже был загружен.
  console.log(
    '🔍 [SAVE] Финальная проверка blob ссылок в productRef.tabs:',
    productRef.tabs
  );
  if (productRef.tabs && productRef.tabs.length > 0) {
    for (let t = 0; t < productRef.tabs.length; t++) {
      const tab = productRef.tabs[t];
      if (!tab || !Array.isArray(tab.content)) continue;
      for (let i = 0; i < tab.content.length; i++) {
        const url = tab.content[i]['path-icon'] || '';
        console.log('🔍 [SAVE] Финальная проверка URL:', t, i, url);
        if (typeof url === 'string' && url.startsWith('blob:')) {
          console.log(
            '🔍 [SAVE] Найден blob URL в финальной проверке, ищем staged файл'
          );
          // Проверяем, есть ли уже загруженный путь в нашем хранилище
          const iconKey = `${t}_${i}`;
          const storedPath = tabIconPaths.value.get(product.id)?.get(iconKey);
          console.log(
            '🔍 [SAVE] Ищем сохраненный путь для:',
            iconKey,
            'найден:',
            storedPath
          );
          if (storedPath) {
            console.log(
              '🔍 [SAVE] Используем сохраненный путь в финальной проверке:',
              storedPath
            );
            // Используем сохраненный путь
            tab.content[i]['path-icon'] = storedPath;
          } else {
            console.log('🔍 [SAVE] Затираем blob URL на пустую строку');
            // replace with empty string to avoid storing blob: URL in DB
            tab.content[i]['path-icon'] = '';
          }
        }
      }
    }
  }
  console.log('\n');
  console.log('╔════════════════════════════════════════════════════════════╗');
  console.log('║  ФИНАЛЬНАЯ ПРОВЕРКА ПЕРЕД ОТПРАВКОЙ В БД                 ║');
  console.log('╚════════════════════════════════════════════════════════════╝');
  console.log('📊 [PRODUCTS.VUE] productRef.tabs ФИНАЛЬНОЕ СОСТОЯНИЕ:');
  if (productRef.tabs) {
    productRef.tabs.forEach((tab: any, tIdx: number) => {
      console.log(`  Вкладка [${tIdx}]: ${tab.title}`);
      if (tab.content) {
        tab.content.forEach((item: any, iIdx: number) => {
          const icon = item['path-icon'];
          const isBlob = icon && icon.startsWith('blob:');
          const isEmpty = !icon;
          const isServer = icon && icon.startsWith('/');
          console.log(
            `    Элемент [${tIdx}][${iIdx}]: "${item.title}" → ` +
              `${
                isEmpty
                  ? '❌ ПУСТО'
                  : isBlob
                  ? '⚠️ BLOB'
                  : isServer
                  ? '✅ СЕРВЕР'
                  : '❓ НЕИЗВЕСТНО'
              } → ` +
              `"${icon}"`
          );
        });
      }
    });
  }
  console.log('\n🚀 [PRODUCTS.VUE] Вызываем updateProduct(productRef)...\n');

  const updated: boolean = await updateProduct(productRef);

  console.log(
    '\n📥 [PRODUCTS.VUE] updateProduct ЗАВЕРШЕН, результат:',
    updated
  );

  if (updated) {
    console.log('✅ [PRODUCTS.VUE] Товар успешно обновлен в БД');
    Swal.fire('Сохранено!', 'Товар был успешно обновлен.', 'success');
    isAddingNewProduct.value = false;
    // clear dirty flag for this product
    dirtyMap.value[product.id] = false;
    // Ensure UI list is synchronized with the saved productRef
    const idx = products.value.findIndex((p) => p.id === productRef.id);
    if (idx !== -1) {
      try {
        products.value[idx] = JSON.parse(JSON.stringify(productRef));
      } catch (e) {
        products.value[idx] = { ...productRef } as ProductI;
      }
    }
  } else {
    console.log('❌ [PRODUCTS.VUE] НЕ удалось обновить товар');
    Swal.fire('Ошибка!', 'Не удалось сохранить товар.', 'error');
  }

  console.log(
    '████████████████████████████████████████████████████████████████████████'
  );
  console.log('💾💾💾 [PRODUCTS.VUE] saveChanges - КОНЕЦ СОХРАНЕНИЯ 💾💾💾');
  console.log(
    '████████████████████████████████████████████████████████████████████████\n\n'
  );

  return updated;
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
  console.log('\n');
  console.log('▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓');
  console.log('📥 [PRODUCTS.VUE] handleStageTabIcon - ПОЛУЧЕН ФАЙЛ');
  console.log('▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓');

  const productInState = products.value.find((p) => p.id === productId);
  if (!productInState) {
    console.log('❌ [PRODUCTS.VUE] Продукт не найден:', productId);
    return;
  }

  console.log('📦 [PRODUCTS.VUE] Параметры:', {
    productId,
    tabIndex,
    itemIndex,
    fileName: file.name,
    fileSize: file.size,
  });

  const blobUrl = URL.createObjectURL(file);
  console.log('🔗 [PRODUCTS.VUE] Создан blob URL:', blobUrl);

  // Всегда добавляем в staged очередь
  const productIcons = tabIconsToUpload.value.get(productId) || [];
  productIcons.push({ tabIndex, itemIndex, file, blobUrl });
  tabIconsToUpload.value.set(productId, productIcons);

  console.log('📋 [PRODUCTS.VUE] Добавлен в tabIconsToUpload:', {
    productId,
    totalIconsInQueue: productIcons.length,
  });

  // Показываем превью сразу - проверяем и создаем структуру если нужно
  if (!productInState.tabs) {
    productInState.tabs = [];
    console.log('📝 [PRODUCTS.VUE] Создана пустая структура tabs');
  }

  // Создаем вкладку, если не существует
  if (!productInState.tabs[tabIndex]) {
    productInState.tabs[tabIndex] = { title: 'Новая вкладка', content: [] };
    console.log(`📝 [PRODUCTS.VUE] Создана вкладка [${tabIndex}]`);
  }

  // Создаем content массив, если не существует
  if (!productInState.tabs[tabIndex].content) {
    productInState.tabs[tabIndex].content = [];
    console.log(`📝 [PRODUCTS.VUE] Создан content для вкладки [${tabIndex}]`);
  }

  // Создаем элемент, если не существует
  if (!productInState.tabs[tabIndex].content[itemIndex]) {
    productInState.tabs[tabIndex].content[itemIndex] = {
      title: 'Новый элемент',
      description: '',
      'path-icon': '',
    };
    console.log(`📝 [PRODUCTS.VUE] Создан элемент [${tabIndex}][${itemIndex}]`);
  }

  // Устанавливаем blob URL
  productInState.tabs[tabIndex].content[itemIndex]['path-icon'] = blobUrl;
  console.log('✅ [PRODUCTS.VUE] Установлен blob URL в productInState.tabs');

  console.log('📊 [PRODUCTS.VUE] Текущее состояние productInState.tabs:');
  productInState.tabs.forEach((tab: any, tIdx: number) => {
    console.log(`  Вкладка [${tIdx}]: ${tab.title}`);
    if (tab.content) {
      tab.content.forEach((item: any, iIdx: number) => {
        console.log(
          `    Элемент [${tIdx}][${iIdx}]: "${item.title}" → path-icon: "${item['path-icon']}"`
        );
      });
    }
  });

  console.log(
    '✅ [PRODUCTS.VUE] Иконка готова для превью, будет загружена при сохранении'
  );
  console.log('▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓\n');
}

function handleUpdateTabIconPath(
  productId: string,
  tabIndex: number,
  itemIndex: number,
  newPath: string
) {
  const productInState = products.value.find((p) => p.id === productId);
  if (!productInState || !productInState.tabs) return;

  // Обновляем путь в основном состоянии продукта
  if (
    productInState.tabs[tabIndex] &&
    productInState.tabs[tabIndex].content[itemIndex]
  ) {
    productInState.tabs[tabIndex].content[itemIndex]['path-icon'] = newPath;
  }
}

function handleDeleteTabIcon(
  productId: string,
  tabIndex: number,
  itemIndex: number
) {
  const productInState = products.value.find((p) => p.id === productId);
  if (!productInState) return;

  const iconUrl =
    productInState.tabs?.[tabIndex].content[itemIndex]['path-icon'] || '';

  if (iconUrl.startsWith('blob:')) {
    const icons = tabIconsToUpload.value.get(productId) || [];
    const iconIndex = icons.findIndex((i) => i.blobUrl === iconUrl);
    if (iconIndex !== -1) {
      URL.revokeObjectURL(iconUrl);
      icons.splice(iconIndex, 1);
      tabIconsToUpload.value.set(productId, icons);
    }
    if (productInState.tabs) {
      productInState.tabs[tabIndex].content[itemIndex]['path-icon'] = '';
    }
    Swal.fire('Удалено', 'Иконка была удалена.', 'success');
  } else {
    apiDeleteTabIcon(productId, tabIndex, itemIndex).then((success) => {
      if (success && productInState.tabs) {
        productInState.tabs[tabIndex].content[itemIndex]['path-icon'] = '';
        Swal.fire('Удалено', 'Иконка была удалена.', 'success');
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

<template>
  <div class="products-admin">
    <h1 class="my-title">Товары</h1>
    <div class="p-4 md:p-6 theme-dark">
      <input
        type="file"
        ref="fileInput"
        @change="handleFileSelected"
        style="display: none"
        accept="image/*"
      />
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
              <div
                class="space-y-2 product-list"
                v-if="openAccardions[category]"
              >
                <Product
                  v-for="product in group"
                  :key="product.id"
                  :product="product"
                  :all-categories="allCategories"
                  :is-image-uploading="isImageUploading"
                  :get-category-name="getCategoryName"
                  :is-adding-new-product="isAddingNewProduct"
                  @save-product="saveChanges"
                  @delete-product="deleteProductHandler"
                  @delete-image="handleDeleteImage"
                  @trigger-file-upload="triggerFileUpload"
                  @handle-toggle="handleToggleWithCheck"
                  @cancel-editing="handleCancelEditing"
                  @stage-tab-icon="handleStageTabIcon"
                  @delete-tab-icon="handleDeleteTabIcon"
                  @update-tab-icon-path="handleUpdateTabIconPath"
                  @dirty-state="handleDirtyState"
                  :current-open-id="openProductId"
                  :ref="(el) => registerProductRef(el, product.id)"
                />
                <MyBtn
                  variant="secondary"
                  @click="handleAddProduct(category)"
                  class="btn-add"
                  :disabled="isAddingNewProduct"
                >
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
