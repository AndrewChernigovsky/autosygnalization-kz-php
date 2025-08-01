<script setup lang="ts">
import { ref, onMounted, computed, nextTick } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../utils/fetchWithCors';
import LinkSelector from '../components/UI/LinkSelector.vue';
import LoadingModal from '../components/UI/LoadingModal.vue';
import MyModal from '../components/UI/MyModal.vue';
import MyBtn from '../components/UI/MyBtn.vue';
import type { LinkData, IFooterLink, SectionKey } from '../types/FooterLinks';

const allLinksData = ref<LinkData[]>([]);
const selectedLink = ref<LinkData | null>(null);
const footerLinks = ref<IFooterLink[]>([]);
const isInitialLoading = ref(false);
const isUpdating = ref(false);
const error = ref<string | null>(null);
const activeAccordion = ref<SectionKey | null>('shop');
const editingLink = ref<Partial<IFooterLink> | null>(null);
const isModalOpen = ref(false);
const draggedItem = ref<IFooterLink | null>(null);
const addMode = ref<'custom' | 'existing'>('custom');
const selectedLinkForModal = ref<LinkData | null>(null);

const availableLinksForModal = computed(() => {
  const existingSourceIds = new Set(
    footerLinks.value
      .filter((l) => l.source_table === 'custom')
      .map((l) => l.source_id)
  );
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
  // Optimistic update for reordering
  const currentOrder = JSON.parse(JSON.stringify(footerLinks.value));
  const newOrder = [...footerLinks.value];
  items.forEach((item, index) => {
    const linkInNewOrder = newOrder.find((l) => l.footer_id === item.footer_id);
    if (linkInNewOrder) linkInNewOrder.position = index + 1;
  });
  footerLinks.value = newOrder;

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
    footerLinks.value = currentOrder; // Revert on error
    Swal.fire('Ошибка!', 'Не удалось сохранить порядок.', 'error');
  }
};

const saveLink = async (link: Partial<IFooterLink> | null) => {
  if (!link) return;

  let payload: Partial<IFooterLink> = {};

  if (addMode.value === 'existing' && !link.footer_id) {
    if (!selectedLinkForModal.value || !link.section) {
      Swal.fire(
        'Ошибка',
        'Пожалуйста, выберите ссылку и раздел для нее.',
        'error'
      );
      return;
    }
    payload = {
      name: selectedLinkForModal.value.name,
      link: selectedLinkForModal.value.link,
      section: link.section,
      source_table: 'custom',
      source_id: selectedLinkForModal.value.links_data_id,
      position: 99,
      visible: true,
    };
  } else {
    payload = { ...link };
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
  addMode.value = 'custom';
  selectedLinkForModal.value = null;
  editingLink.value = link
    ? { ...link }
    : {
        name: '',
        link: '',
        section: section || 'shop',
        position: 99,
        visible: true,
        source_table: 'custom',
      };
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  editingLink.value = null;
};

// --- Drag and Drop Logic ---
const handleDragStart = (item: IFooterLink) => {
  draggedItem.value = item;
};
const handleDragEnd = () => {
  draggedItem.value = null;
};
const handleDrop = (event: DragEvent, targetSection: SectionKey) => {
  const targetElement = event.currentTarget as HTMLElement;
  if (
    !targetElement ||
    !draggedItem.value ||
    draggedItem.value.section !== targetSection
  )
    return;

  const sectionLinks = [...sectionData.value[targetSection].links];
  const toIndex = sectionLinks.findIndex(
    (l) => l.footer_id === Number(targetElement.getAttribute('data-id'))
  );
  const fromIndex = sectionLinks.findIndex(
    (l) => l.footer_id === draggedItem.value!.footer_id
  );

  if (fromIndex === -1 || toIndex === -1 || fromIndex === toIndex) return;

  const [movedItem] = sectionLinks.splice(fromIndex, 1);
  sectionLinks.splice(toIndex, 0, movedItem);

  const otherSectionsLinks = footerLinks.value.filter(
    (link) => link.section !== targetSection
  );
  footerLinks.value = [...otherSectionsLinks, ...sectionLinks];
  updatePositions(sectionLinks);
};

onMounted(() => {
  getLinksDataFromDB();
  fetchLinks(true);
});
</script>

<template>
  <div class="new-footer-container">
    <div class="accordions-wrapper">
      <div v-for="(section, key) in sectionData" :key="key" class="accordion">
        <div
          class="accordion-header"
          @click="activeAccordion = activeAccordion === key ? null : key"
        >
          <h3 class="accordion-title">{{ section.title }}</h3>
          <span class="accordion-icon">{{
            activeAccordion === key ? '−' : '+'
          }}</span>
        </div>
        <div v-if="activeAccordion === key" class="accordion-content">
          <ul class="links-list">
            <li
              v-for="link in section.links"
              :key="link.footer_id"
              class="link-item"
              :class="{
                dragging:
                  draggedItem && draggedItem.footer_id === link.footer_id,
              }"
              draggable="true"
              :data-id="link.footer_id"
              @dragstart="handleDragStart(link)"
              @dragend="handleDragEnd"
              @drop.prevent="handleDrop($event, key)"
              @dragover.prevent
            >
              <div class="link-info">
                <span class="drag-handle">⠿</span>
                <span>{{ link.name }}</span>
              </div>
              <div class="item-controls">
                <MySwitch
                  :model-value="link.visible"
                  @update:model-value="saveLink({ ...link, visible: $event })"
                />
                <MyBtn variant="primary" @click="openModal(link)"
                  >Редактировать</MyBtn
                >
                <MyBtn
                  v-if="link.source_table === 'custom'"
                  variant="secondary"
                  @click="deleteLink(link.footer_id)"
                  >Удалить</MyBtn
                >
              </div>
            </li>
          </ul>
          <MyBtn variant="secondary" @click.stop="openModal(null, key)"
            >Добавить ссылку</MyBtn
          >
        </div>
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
          <div v-if="!editingLink.footer_id" class="add-mode-toggle">
            <label :class="{ active: addMode === 'custom' }">
              <input
                type="radio"
                value="custom"
                v-model="addMode"
                name="add-mode"
              />
              Создать новую
            </label>
            <label :class="{ active: addMode === 'existing' }">
              <input
                type="radio"
                value="existing"
                v-model="addMode"
                name="add-mode"
              />
              Выбрать существующую
            </label>
          </div>
        </div>
      </template>
      <template v-slot:body>
        <form @submit.prevent="saveLink(editingLink!)">
          <div v-if="addMode === 'custom'" class="form-group">
            <label for="name">Название</label>
            <input type="text" id="name" v-model="editingLink.name" required />
            <label for="link">Ссылка</label>
            <input type="text" id="link" v-model="editingLink.link" required />
            <label for="section">Раздел</label>
            <select id="section" v-model="editingLink.section" required>
              <option value="shop">Магазин</option>
              <option value="installation">Установочный центр</option>
              <option value="client">Клиенту</option>
            </select>
          </div>
          <div v-if="addMode === 'existing'" class="form-group">
            <LinkSelector
              v-model="selectedLinkForModal"
              :links="availableLinksForModal"
              label="Выберите существующую ссылку"
              id="modal-link-selector"
            />
            <label for="modal-section" v-if="selectedLinkForModal"
              >Выберите раздел для этой ссылки</label
            >
            <select
              id="modal-section"
              v-if="selectedLinkForModal"
              v-model="editingLink.section"
              required
            >
              <option value="shop">Магазин</option>
              <option value="installation">Установочный центр</option>
              <option value="client">Клиенту</option>
            </select>
          </div>
        </form>
      </template>
      <template v-slot:footer>
        <MyBtn variant="secondary" @click="closeModal">Отмена</MyBtn>
        <MyBtn variant="primary" @click="saveLink(editingLink!)"
          >Сохранить</MyBtn
        >
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
  background-color: #2e7d32; /* Dark Green */
  color: white;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.4);
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
.accordion-icon {
  font-size: 1.5rem;
  font-weight: bold;
}
.accordion-content {
  padding: 15px;
}
.links-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.link-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 5px;
  border-bottom: 1px solid #444;
  transition: background-color 0.2s ease;
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
