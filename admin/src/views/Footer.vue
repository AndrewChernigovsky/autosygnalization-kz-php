<script setup lang="ts">
import { ref, onMounted, computed, nextTick, watch, watchEffect } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../utils/fetchWithCors';
import MySwitch from '../components/UI/MySwitch.vue';
import LinkSelector from '../components/UI/LinkSelector.vue';
import MyModal from '../components/UI/MyModal.vue';
import MyBtn from '../components/UI/MyBtn.vue';
import DraggableList from '../components/UI/DraggableList.vue';
import MyTransition from '../components/UI/MyTransition.vue';
import type { LinkData, IFooterLink, SectionKey } from '../types/FooterLinks';

const allLinksData = ref<LinkData[]>([]);
const footerLinks = ref<IFooterLink[]>([]);
const isInitialLoading = ref(false);
const isUpdating = ref(false);
const error = ref<string | null>(null);
const activeAccordion = ref<SectionKey | null>('shop');
const editingLink = ref<Partial<IFooterLink> | null>(null);
const isModalOpen = ref(false);
const linkSourceMode = ref<'custom' | 'existing'>('custom');
const selectedLinkForModal = ref<LinkData | null>(null);

watch(selectedLinkForModal, (newSelectedLink) => {
  if (
    newSelectedLink &&
    linkSourceMode.value === 'existing' &&
    editingLink.value
  ) {
    editingLink.value.name = newSelectedLink.name;
  }
});

const availableLinksForModal = computed(() => {
  const existingSourceIds = new Set(
    footerLinks.value
      .map((l) => l.source_id)
      .filter((id): id is number => id !== null && id !== undefined)
  );

  // When editing, allow the current link to be in the list
  if (editingLink.value?.source_id) {
    existingSourceIds.delete(editingLink.value.source_id);
  }

  return allLinksData.value.filter(
    (l) => !existingSourceIds.has(l.links_data_id)
  );
});

const API_URL = '/server/php/admin/api/linksData/linksData.php';
const API_URL_FOOTER = '/server/php/admin/api/footer/footer.php';

const sectionData = computed(() => ({
  shop: {
    title: 'Магазин',
    links: footerLinks.value
      .filter((l) => l.section === 'shop')
      .sort((a, b) => a.position - b.position),
  },
  installation: {
    title: 'Установочный центр',
    links: footerLinks.value
      .filter((l) => l.section === 'installation')
      .sort((a, b) => a.position - b.position),
  },
  client: {
    title: 'Клиенту',
    links: footerLinks.value
      .filter((l) => l.section === 'client')
      .sort((a, b) => a.position - b.position),
  },
}));

const fetchLinks = async (isUpdate = false) => {
  if (isUpdate) {
    isUpdating.value = true;
  } else {
    isInitialLoading.value = true;
  }
  error.value = null;

  try {
    const response = await fetch(API_URL_FOOTER);
    if (!response.ok) throw new Error(`Ошибка сети: ${response.statusText}`);
    const result = await response.json();
    if (result.success) {
      const activeId = activeAccordion.value;
      footerLinks.value = result.data.map((link: any) => ({
        ...link,
        visible: !!parseInt(String(link.visible), 10),
      }));
      await nextTick();
      activeAccordion.value = activeId;
    } else {
      throw new Error(result.error || 'Не удалось загрузить данные');
    }
  } catch (e: any) {
    error.value = e.message;
  } finally {
    isInitialLoading.value = false;
    isUpdating.value = false;
  }
};

const getLinksDataFromDB = async () => {
  try {
    const response = await fetchWithCors(API_URL);
    if (!response.success) {
      throw new Error(response.error || 'Failed to fetch links data');
    }
    allLinksData.value = response.data;
  } catch (err: any) {
    Swal.fire({
      icon: 'error',
      title: 'Ошибка',
      text: 'Не удалось получить данные ссылок из LinksData',
    });
  }
};

const sendRequest = async (
  url: string,
  options: RequestInit,
  successMessage: string
) => {
  try {
    const response = await fetch(url, options);
    const result = await response.json();
    if (!result.success) throw new Error(result.error || 'Произошла ошибка');
    Swal.fire('Успех!', successMessage, 'success');
    await fetchLinks(true); // Pass true to indicate an update
    return true;
  } catch (e: any) {
    Swal.fire('Ошибка!', e.message, 'error');
    return false;
  }
};

const updatePositions = async (items: IFooterLink[]) => {
  const payload = items.map((item, index) => ({
    footer_id: item.footer_id,
    position: index + 1,
  }));
  try {
    const response = await fetch(API_URL_FOOTER, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'update_positions', items: payload }),
    });
    const result = await response.json();
    if (!result.success) throw new Error(result.error);
    Swal.fire('Успех!', 'Порядок ссылок обновлен!', 'success');
  } catch (e: any) {
    Swal.fire('Ошибка!', 'Не удалось сохранить порядок.', 'error');
    // Revert on error by refetching from server
    await fetchLinks(true);
  }
};

const saveLink = async (link: Partial<IFooterLink> | null) => {
  if (!link) return;

  let payload: Partial<IFooterLink>;

  if (linkSourceMode.value === 'existing') {
    if (!selectedLinkForModal.value) {
      Swal.fire('Ошибка', 'Пожалуйста, выберите страницу.', 'error');
      return;
    }
    payload = {
      ...link,
      link: selectedLinkForModal.value.link,
      source_table: 'custom', // Per existing logic
      source_id: selectedLinkForModal.value.links_data_id,
    };
  } else {
    // Custom link
    payload = {
      ...link,
      source_table: 'custom',
      source_id: null,
    };
  }

  const formData = new FormData();
  Object.entries(payload).forEach(([key, value]) => {
    if (value !== null && value !== undefined) {
      let val = value;
      if (typeof value === 'boolean') val = value ? '1' : '0';
      formData.append(key, String(val));
    }
  });

  const successMessage = payload.footer_id
    ? 'Ссылка успешно обновлена!'
    : 'Ссылка успешно создана!';

  if (
    await sendRequest(
      API_URL_FOOTER,
      { method: 'POST', body: formData },
      successMessage
    )
  ) {
    closeModal();
  }
};

const deleteLink = async (id: number) => {
  const result = await Swal.fire({
    title: 'Вы уверены?',
    text: 'Это действие нельзя отменить!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
  });
  if (result.isConfirmed) {
    await sendRequest(
      `${API_URL_FOOTER}?id=${id}`,
      { method: 'DELETE' },
      'Ссылка успешно удалена!'
    );
  }
};

// --- Modal Logic ---
const openModal = (
  link: Partial<IFooterLink> | null = null,
  section: SectionKey | null = null
) => {
  if (link) {
    // Editing existing link
    editingLink.value = { ...link };
    linkSourceMode.value =
      link.source_table === 'custom' && !link.source_id ? 'custom' : 'existing';

    if (linkSourceMode.value === 'existing' && link.source_id) {
      selectedLinkForModal.value =
        allLinksData.value.find((l) => l.links_data_id === link.source_id) ??
        null;
    } else {
      selectedLinkForModal.value = null;
    }
  } else {
    // Adding new link
    editingLink.value = {
      name: '',
      link: '',
      section: section || 'shop',
      position: 99,
      visible: true,
      source_table: 'custom',
    };
    linkSourceMode.value = 'custom';
    selectedLinkForModal.value = null;
  }
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  editingLink.value = null;
};

const handleSectionReorder = (
  reorderedLinks: IFooterLink[],
  sectionKey: SectionKey
) => {
  // Create a new array with updated positions for optimistic UI update
  const updatedLinks = reorderedLinks.map((link, index) => ({
    ...link,
    position: index + 1,
  }));

  // 1. Optimistic update of the main array
  const otherSectionsLinks = footerLinks.value.filter(
    (link) => link.section !== sectionKey
  );
  footerLinks.value = [...otherSectionsLinks, ...updatedLinks];

  // 2. Call the function that handles API communication
  updatePositions(updatedLinks);
};

const toggleAccordion = (key: SectionKey) => {
  const currentOpen = activeAccordion.value;

  // If we're clicking the currently open accordion, just close it.
  if (currentOpen === key) {
    activeAccordion.value = null;
    return;
  }

  // If another accordion is open, close it first.
  if (currentOpen !== null) {
    activeAccordion.value = null;
    // Wait for the closing animation to start before opening the new one.
    // A timeout is more reliable than nextTick for this animation scenario.
    setTimeout(() => {
      activeAccordion.value = key;
    }, 10);
  } else {
    // If no accordion is open, just open the new one directly.
    activeAccordion.value = key;
  }
};

onMounted(() => {
  getLinksDataFromDB();
  fetchLinks(true);
});

watchEffect(() => {
  if (isInitialLoading.value) {
    Swal.fire({
      title: 'Загрузка данных футера...',
      text: 'Пожалуйста, подождите',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  } else if (!isUpdating.value) {
    Swal.close();
  }
});
</script>

<template>
  <div class="new-footer-container">
    <h1 class="my-title">Футер</h1>
    <div class="accordions-wrapper">
      <div v-for="(section, key) in sectionData" :key="key" class="accordion">
        <div class="accordion-header" @click="toggleAccordion(key)">
          <h3 class="accordion-title">{{ section.title }}</h3>
          <MyBtn variant="primary" class="accordion-icon">{{
            activeAccordion === key ? 'Закрыть' : 'Открыть'
          }}</MyBtn>
        </div>
        <MyTransition>
          <div v-if="activeAccordion === key" class="accordion-content-inner">
            <DraggableList :model-value="section.links" item-key="footer_id" tag="ul" class="links-list" @reorder="
              (reorderedLinks) => handleSectionReorder(reorderedLinks, key)
            ">
              <template #item="{ item, dragHandleProps, isDragOver }">
                <li class="link-item" :class="{ 'drag-over': isDragOver }">
                  <div class="link-info">
                    <span class="drag-handle" v-bind="dragHandleProps">⠿</span>
                    <span>{{ item.name }}</span>
                  </div>
                  <div class="item-controls">
                    <MyBtn variant="primary" @click="openModal(item)">Редактировать</MyBtn>
                    <MyBtn v-if="item.source_table === 'custom'" variant="secondary"
                      @click="deleteLink(item.footer_id)">Удалить</MyBtn>
                  </div>
                </li>
              </template>
            </DraggableList>
            <MyBtn variant="secondary" @click.stop="openModal(null, key)">Добавить ссылку</MyBtn>
          </div>
        </MyTransition>
      </div>
    </div>
    <MyModal v-if="isModalOpen && editingLink" @close="closeModal">
      <template v-slot:header>
        <div class="modal-header-content">
          <h3>
            {{
              editingLink.footer_id ? 'Редактировать ссылку' : 'Добавить ссылку'
            }}
          </h3>
          <div class="add-mode-toggle">
            <label :class="{ active: linkSourceMode === 'custom' }">
              <input type="radio" value="custom" v-model="linkSourceMode" name="link-source-mode" />
              Внешняя ссылка
            </label>
            <label :class="{ active: linkSourceMode === 'existing' }">
              <input type="radio" value="existing" v-model="linkSourceMode" name="link-source-mode" />
              Внутренняя страница
            </label>
          </div>
        </div>
      </template>
      <template v-slot:body>
        <form @submit.prevent="saveLink(editingLink!)">
          <!-- Поля для кастомной/внешней ссылки -->
          <div v-if="linkSourceMode === 'custom'" class="form-group">
            <label for="name">Название</label>
            <input type="text" id="name" v-model="editingLink.name" required />
            <label for="link">Ссылка</label>
            <input type="text" id="link" v-model="editingLink.link" required />
          </div>

          <!-- Поля для выбора существующей -->
          <div v-if="linkSourceMode === 'existing'" class="form-group">
            <label for="name-existing">Название (можно изменить)</label>
            <input type="text" id="name-existing" v-model="editingLink.name" required />
            <LinkSelector v-model="selectedLinkForModal" :links="availableLinksForModal" label="Выберите страницу"
              id="modal-link-selector" />
          </div>

          <!-- Общие поля для всех режимов -->
          <div class="form-group common-fields">
            <label for="section">Раздел</label>
            <select id="section" v-model="editingLink.section" required>
              <option value="shop">Магазин</option>
              <option value="installation">Установочный центр</option>
              <option value="client">Клиенту</option>
            </select>
            <div class="form-row">
              <label for="visible">Видимость</label>
              <MySwitch id="visible" :model-value="editingLink.visible!"
                @update:model-value="editingLink.visible = $event" />
            </div>
          </div>
        </form>
      </template>
      <template v-slot:footer>
        <MyBtn variant="secondary" @click="closeModal">Отмена</MyBtn>
        <MyBtn variant="primary" @click="saveLink(editingLink!)">Сохранить</MyBtn>
      </template>
    </MyModal>
  </div>
</template>

<style scoped>
/* Modal Header & Toggle Styles */
.modal-header-content {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 15px;
}

.add-mode-toggle {
  display: flex;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid #555;
  width: 100%;
  max-width: 400px;
}

.add-mode-toggle input[type='radio'] {
  display: none;
}

.add-mode-toggle label {
  flex: 1;
  text-align: center;
  padding: 10px;
  cursor: pointer;
  background-color: #3a3a3c;
  color: #f0f0f0;
  transition: all 0.2s ease-in-out;
  font-size: 0.9rem;
  font-weight: 500;
  user-select: none;
}

.add-mode-toggle label:not(:first-of-type) {
  border-left: 1px solid #555;
}

.add-mode-toggle label.active {
  background: linear-gradient(180deg, #280000 0%, #ff0000 100%);
  color: white;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.4);
}

.my-title {
  font-size: 32px;
  font-weight: bold;
  margin: 0;
}

.new-footer-container {
  padding: 20px;
}

.accordions-wrapper {
  margin-top: 20px;
  background-color: black;
}

.accordion {
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-bottom: 10px;
  background-color: black;
}

.accordion-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 15px;
  cursor: pointer;
}

.accordion-title {
  margin: 0;
  font-size: 1.1rem;
}

.accordion-content {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease-in-out;
  background-color: #2c2c2e;
}

.accordion-content.is-open {
  max-height: 1000px;
  /* Adjust as needed */
}

.accordion-content-inner {
  background-color: black;
  padding: 15px;
}

.links-list {
  list-style: none;
  padding: 0;
  margin: 0 0 15px 0;
}

.link-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 5px;
  border-bottom: 1px solid #444;
  transition: background-color 0.2s ease;
}

.link-item.drag-over {
  background: #3a3a3c;
  border-radius: 4px;
}

.link-item:last-child {
  border-bottom: none;
}

.link-item[draggable='true'] {
  cursor: grab;
}

.link-item.dragging {
  opacity: 0.5;
  background: #3a3a3c;
}

.link-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.drag-handle {
  font-size: 1.4rem;
  color: #888;
  cursor: grab;
  padding: 0 5px;
}

.item-controls {
  display: flex;
  gap: 10px;
  align-items: center;
}

/* Modal Form Styles */
.form-group {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.form-row {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-top: 10px;
}

.form-group label {
  font-weight: 500;
  margin-top: 8px;
  margin-bottom: -2px;
}

.form-group input[type='text'],
.form-group select {
  background-color: #1c1c1e;
  color: #f0f0f0;
  border: 1px solid #555;
  padding: 10px;
  border-radius: 6px;
  width: 100%;
  box-sizing: border-box;
  font-size: 1rem;
}

.form-group input[type='text']:focus,
.form-group select:focus {
  outline: none;
  border-color: #007aff;
  box-shadow: 0 0 0 2px rgba(0, 122, 255, 0.5);
}

.form-group input:disabled,
.form-group select:disabled {
  background-color: #3a3a3c;
  cursor: not-allowed;
  opacity: 0.6;
}
</style>
