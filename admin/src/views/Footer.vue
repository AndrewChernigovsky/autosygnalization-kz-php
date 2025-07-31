<script setup lang="ts">
import { ref, onMounted, computed, nextTick } from 'vue';
import Swal from 'sweetalert2';
import LoadingModal from '../components/UI/LoadingModal.vue';
import MyBtn from '../components/UI/MyBtn.vue';
import MyInput from '../components/UI/MyInput.vue';
import MySwitch from '../components/UI/MySwitch.vue';
import MyModal from '../components/UI/MyModal.vue';

// --- Interfaces ---
interface IFooterLink {
  footer_id: number;
  section: 'shop' | 'installation' | 'client';
  name: string;
  link: string;
  position: number;
  visible: boolean;
  source_table: 'Sections-Product' | 'Services' | 'custom';
  source_id: number | null;
}

type SectionKey = 'shop' | 'installation' | 'client';

// --- State ---
const API_BASE_URL = '/server/php/admin/api/footer/footer.php';
const allLinks = ref<IFooterLink[]>([]);
const isInitialLoading = ref(true); // For full-page loader
const isUpdating = ref(false); // For overlay spinner
const error = ref<string | null>(null);

const activeAccordion = ref<SectionKey | null>('shop');
const isModalOpen = ref(false);
const editingLink = ref<Partial<IFooterLink> | null>(null);
const draggedItem = ref<IFooterLink | null>(null);

// --- Computed Properties ---
const sectionData = computed(() => ({
  shop: {
    title: 'Магазин',
    links: allLinks.value
      .filter((l) => l.section === 'shop')
      .sort((a, b) => a.position - b.position),
  },
  installation: {
    title: 'Установочный центр',
    links: allLinks.value
      .filter((l) => l.section === 'installation')
      .sort((a, b) => a.position - b.position),
  },
  client: {
    title: 'Клиенту',
    links: allLinks.value
      .filter((l) => l.section === 'client')
      .sort((a, b) => a.position - b.position),
  },
}));

// --- API Calls ---
const fetchLinks = async (isUpdate = false) => {
  if (isUpdate) {
    isUpdating.value = true;
  } else {
    isInitialLoading.value = true;
  }
  error.value = null;

  try {
    const response = await fetch(API_BASE_URL);
    if (!response.ok) throw new Error(`Ошибка сети: ${response.statusText}`);
    const result = await response.json();
    if (result.success) {
      const activeId = activeAccordion.value;
      allLinks.value = result.data.map((link: any) => ({
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
  const currentOrder = JSON.parse(JSON.stringify(allLinks.value));
  const newOrder = [...allLinks.value];
  items.forEach((item, index) => {
    const linkInNewOrder = newOrder.find((l) => l.footer_id === item.footer_id);
    if (linkInNewOrder) linkInNewOrder.position = index + 1;
  });
  allLinks.value = newOrder;

  const payload = items.map((item, index) => ({
    footer_id: item.footer_id,
    position: index + 1,
  }));
  try {
    const response = await fetch(API_BASE_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'update_positions', items: payload }),
    });
    const result = await response.json();
    if (!result.success) throw new Error(result.error);
    Swal.fire('Успех!', 'Порядок ссылок обновлен!', 'success');
  } catch (e: any) {
    allLinks.value = currentOrder; // Revert on error
    Swal.fire('Ошибка!', 'Не удалось сохранить порядок.', 'error');
  }
};

const saveLink = async (link: Partial<IFooterLink>) => {
  if (!link) return;
  const formData = new FormData();
  Object.entries(link).forEach(([key, value]) => {
    if (value !== null && value !== undefined) {
      let val = value;
      if (typeof value === 'boolean') val = value ? '1' : '0';
      formData.append(key, String(val));
    }
  });

  const successMessage = link.footer_id
    ? 'Ссылка успешно обновлена!'
    : 'Ссылка успешно создана!';

  if (
    await sendRequest(
      API_BASE_URL,
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
      `${API_BASE_URL}?id=${id}`,
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

  const otherSectionsLinks = allLinks.value.filter(
    (link) => link.section !== targetSection
  );
  allLinks.value = [...otherSectionsLinks, ...sectionLinks];
  updatePositions(sectionLinks);
};

// --- Lifecycle & Helpers ---
onMounted(() => fetchLinks(false)); // Initial load
const handleRetry = async () => {
  fetchLinks(false);
};
</script>

<template>
  <div class="my-page p-20">
    <section class="footer-admin">
      <h2 class="footer-admin-title my-title m-0">Управление футером</h2>

      <!-- Initial Loader -->
      <LoadingModal
        v-if="isInitialLoading || error"
        :is-loading="isInitialLoading"
        :error="error"
        :retry="handleRetry"
      />

      <!-- Main Content Area -->
      <div v-else class="content-wrapper">
        <!-- Update Spinner Overlay -->
        <div v-if="isUpdating" class="update-overlay">
          <div class="spinner"></div>
        </div>

        <div class="accordions-wrapper">
          <div
            v-for="(section, key) in sectionData"
            :key="key"
            class="accordion"
          >
            <div
              class="accordion-header"
              @click="activeAccordion = activeAccordion === key ? null : key"
            >
              <h3 class="accordion-title">{{ section.title }}</h3>
              <MyBtn @click.stop="openModal(null, key)">Добавить ссылку</MyBtn>
            </div>
            <div
              class="accordion-content"
              :class="{ active: activeAccordion === key }"
            >
              <ul class="footer-list" @dragover.prevent>
                <li
                  v-for="link in section.links"
                  :key="link.footer_id"
                  :data-id="link.footer_id"
                  class="footer-item"
                  :class="{
                    dragging: draggedItem?.footer_id === link.footer_id,
                  }"
                  draggable="true"
                  @dragstart="handleDragStart(link)"
                  @drop="handleDrop($event, key)"
                  @dragend="handleDragEnd"
                >
                  <span>{{ link.name }}</span>
                  <div class="item-controls">
                    <MySwitch
                      :model-value="link.visible"
                      @update:model-value="
                        saveLink({ ...link, visible: $event })
                      "
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
            </div>
          </div>
        </div>
      </div>
    </section>

    <MyModal v-if="isModalOpen && editingLink" @close="closeModal">
      <template v-slot:header>
        <h3>
          {{
            editingLink.footer_id ? 'Редактировать ссылку' : 'Добавить ссылку'
          }}
        </h3>
      </template>
      <template v-slot:body>
        <form @submit.prevent="saveLink(editingLink!)" class="modal-form">
          <label class="modal-form-label"
            >Название
            <MyInput
              v-model="editingLink.name"
              :disabled="editingLink.source_table !== 'custom'"
            />
          </label>
          <label class="modal-form-label"
            >Ссылка (URL)
            <MyInput
              v-model="editingLink.link"
              :disabled="editingLink.source_table !== 'custom'"
            />
          </label>
          <label class="modal-form-label"
            >Секция
            <select
              v-model="editingLink.section"
              :disabled="editingLink.source_table !== 'custom'"
            >
              <option value="shop">Магазин</option>
              <option value="installation">Установочный центр</option>
              <option value="client">Клиенту</option>
            </select>
          </label>
          <label class="modal-form-label switch-label"
            >Видимость
            <MySwitch
              :model-value="editingLink.visible ?? false"
              @update:model-value="editingLink.visible = $event"
            />
          </label>
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
.footer-admin {
  display: flex;
  flex-direction: column;
  gap: 24px;
}
.content-wrapper {
  position: relative;
}
.update-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(44, 44, 46, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10;
  border-radius: 8px;
}
.spinner {
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top: 4px solid #fff;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.accordions-wrapper {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.accordion {
  padding: 16px;
  border-radius: 8px;
  box-shadow: inset 0 0 0 1px #4b4b4b;
  background-color: #2c2c2e;
}
.accordion-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
}
.accordion-title {
  color: #f0f0f0;
}
.accordion-content {
  display: grid;
  grid-template-rows: 0fr;
  transition: grid-template-rows 0.3s ease-out;
  overflow: hidden;
}
.accordion-content.active {
  grid-template-rows: 1fr;
  margin-top: 16px;
}
.footer-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
  min-height: 1px; /* Part of the fix for grid animation */
}
.footer-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px;
  border-radius: 6px;
  background-color: #3a3a3c;
  box-shadow: inset 0 0 0 1px #545458;
  cursor: move;
  transition: all 0.2s ease-in-out;
}
.footer-item.dragging {
  opacity: 0.5;
  transform: scale(1.02);
  background-color: #4a90e2;
}
.item-controls {
  display: flex;
  align-items: center;
  gap: 12px;
}
.modal-form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.modal-form-label {
  display: flex;
  flex-direction: column;
  gap: 8px;
  color: #f0f0f0;
}
.switch-label {
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
}
select {
  padding: 8px 12px;
  background-color: #3a3a3c;
  color: #f0f0f0;
  border: 1px solid #545458;
  border-radius: 4px;
}
select:disabled {
  background-color: #545458;
  cursor: not-allowed;
}
.error-message {
  text-align: center;
  color: #ff6b6b;
}
</style>
