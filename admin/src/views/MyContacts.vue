<script setup lang="ts">
import { onMounted, ref } from 'vue';
import contactsStore from '../stores/contactsStore';
import LoadingModal from '../components/UI/LoadingModal.vue';
import MyInput from '../components/UI/MyInput.vue';
import MyBtn from '../components/UI/MyBtn.vue';
import Swal from 'sweetalert2';
import { QuillEditor } from '@rafaeljunioxavier/vue-quill-fix';
import '@rafaeljunioxavier/vue-quill-fix/dist/vue-quill.snow.css';

interface IContacts {
  contact_id?: number;
  title: string;
  content: string;
  icon_path: File | string | null;
  icon_path_url?: string | null;
  link: string | null;
  type: string;
  order?: number;
}

const quillOptions = {
  placeholder: 'Введите текст...',
  formats: ['header', 'bold', 'italic', 'underline', 'list', 'link'],
  modules: {
    toolbar: [
      [{ header: [1, 2, 3, false] }],
      ['bold', 'italic', 'underline'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['link'],
      ['clean'],
    ],
  },
};

const API_BASE_URL = '/server/php/admin/api/contacts/contact.php';

const store = contactsStore();

const contacts = ref<IContacts[]>([]);

const newContact = ref<IContacts>({
  title: '',
  content: '',
  icon_path: null,
  icon_path_url: null,
  link: '',
  type: '',
});

const isLoading = ref(false);

const error = ref<string | null>(null);

const contactsType = ref<string[]>([
  'Основной телефон',
  'Адрес',
  'Расписание',
  'Контактный телефон',
  'Соц. сети',
  'Электронная почта',
  'Расписание',
  'Карта',
  'Как к нам добраться',
  'Сайт',
]);

const activeEditType = ref<string | null>(null);

const activeEditItem = ref<IContacts | null>(null);

// Добавляем переменные для drag and drop
const draggedItem = ref<IContacts | null>(null);
const dragOverItem = ref<IContacts | null>(null);

// Функция удалена - теперь используем QuillEditor везде где content: true

// Функция для определения, какие поля должны быть доступны для каждого типа
const getAvailableFields = (type: string) => {
  switch (type) {
    case 'Основной телефон':
    case 'Контактный телефон':
    case 'Электронная почта':
      return {
        title: true,
        content: false,
        link: true,
        icon_path: true,
      };
    case 'Адрес':
    case 'Социальные сети':
      return {
        title: true,
        content: true,
        link: true,
        icon_path: true,
      };
    case 'Расписание':
      return {
        title: true,
        content: true,
        link: false,
        icon_path: true,
      };
    case 'Карта':
    case 'Сайт':
      return {
        title: true,
        content: true,
        link: true,
        icon_path: false,
      };
    case 'Как к нам добраться':
      return {
        title: true,
        content: true,
        link: false,
        icon_path: false,
      };
    default:
      return {
        title: true,
        content: true,
        link: true,
        icon_path: true,
      };
  }
};

// Функция для получения названия поля title в зависимости от типа
const getTitleFieldLabel = (type: string) => {
  switch (type) {
    case 'Социальные сети':
      return 'Название Соц сети';
    default:
      return 'Заголовок';
  }
};

// Функция для получения названия поля ссылки в зависимости от типа
const getLinkFieldLabel = (type: string) => {
  switch (type) {
    case 'Основной телефон':
    case 'Контактный телефон':
      return 'Телефон';
    case 'Социальные сети':
      return 'Ссылка (если есть)';
    case 'Электронная почта':
      return 'Электронная почта';
    case 'Карта':
      return 'Ссылка на google карты';
    case 'Сайт':
      return 'Адрес сайта';
    default:
      return 'Ссылка';
  }
};

// Функция для получения названия поля content в зависимости от типа
const getContentFieldLabel = (type: string) => {
  switch (type) {
    case 'Адрес':
      return 'Адрес';
    case 'Социальные сети':
      return 'Номер телефона(если есть)';
    case 'Расписание':
      return 'График работы';
    case 'Как к нам добраться':
      return 'Как добратся';
    case 'Сайт':
      return 'Контент на странице';
    default:
      return 'Реквизиты';
  }
};

onMounted(async () => {
  await getContacts();
});

const getContacts = async () => {
  isLoading.value = true;
  await store.getContacts(`${API_BASE_URL}`);
  contacts.value = store.contacts;
  if (contacts.value.length > 0) {
    isLoading.value = false;
  }
};

const addContact = async (type: string) => {
  await store.addContact(API_BASE_URL, newContact.value, type);
  resetNewContact();
  await getContacts();
};

const updateContact = async (item: IContacts) => {
  await store.updateContact(API_BASE_URL, item.contact_id as number, item);
  await getContacts();
};

const deleteContact = async (item: IContacts) => {
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
    await store.deleteContact(API_BASE_URL, item.contact_id as number);
    await getContacts();
  }
};

const updateContactsOrder = async (
  orderData: { contact_id: number; order: number }[]
) => {
  try {
    const response = await fetch(`${API_BASE_URL}?action=update-order`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(orderData),
    });

    const result = await response.json();

    if (!result.success) {
      console.error('Ошибка обновления порядка:', result.error);
      Swal.fire('Ошибка!', 'Не удалось обновить порядок контактов', 'error');
    }
  } catch (error) {
    console.error('Ошибка отправки запроса:', error);
    Swal.fire('Ошибка!', 'Не удалось отправить запрос на сервер', 'error');
  }
};

const handleRetry = async () => {
  await store.getContacts(`${API_BASE_URL}`);
  contacts.value = store.contacts;
};

const toggleEditMode = (type: string) => {
  if (activeEditType.value === type) {
    activeEditType.value = null;
  } else {
    activeEditType.value = type;
  }
};

const toggleEditItem = (item: IContacts) => {
  if (activeEditItem.value === item) {
    activeEditItem.value = null;
  } else {
    activeEditItem.value = item;
  }
};

const handleEditFileChange = (file: File | null, item: IContacts) => {
  if (file) {
    item.icon_path = file;
    item.icon_path_url = URL.createObjectURL(file);
  }
};

const handleNewFileChange = (file: File | null) => {
  if (file) {
    newContact.value.icon_path = file;
    newContact.value.icon_path_url = URL.createObjectURL(file);
  }
};

const resetNewContact = () => {
  newContact.value = {
    title: '',
    content: '',
    icon_path: null,
    icon_path_url: null,
    link: '',
    type: '',
  };
};

const getImageUrl = (item: IContacts): string => {
  if (item.icon_path_url) return item.icon_path_url;
  if (typeof item.icon_path === 'string') return item.icon_path;
  return '';
};

// Методы для drag and drop
const handleDragStart = (event: DragEvent, item: IContacts) => {
  draggedItem.value = item;
  if (event.dataTransfer) {
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', item.contact_id?.toString() || '');
  }
};

const handleDragOver = (event: DragEvent) => {
  event.preventDefault();
  if (event.dataTransfer) {
    event.dataTransfer.dropEffect = 'move';
  }
};

const handleDragEnter = (event: DragEvent, item: IContacts) => {
  event.preventDefault();
  dragOverItem.value = item;
};

const handleDragLeave = (event: DragEvent) => {
  // Проверяем, что мы действительно покидаем элемент, а не его дочерний элемент
  if (
    !(event.currentTarget as Element)?.contains(event.relatedTarget as Node)
  ) {
    dragOverItem.value = null;
  }
};

const handleDrop = async (
  event: DragEvent,
  targetItem: IContacts,
  type: string
) => {
  event.preventDefault();

  if (
    !draggedItem.value ||
    draggedItem.value.contact_id === targetItem.contact_id
  ) {
    draggedItem.value = null;
    dragOverItem.value = null;
    return;
  }

  // Получаем элементы этого типа
  const itemsOfType = contacts.value.filter((item) => item.type === type);
  const draggedIndex = itemsOfType.findIndex(
    (item) => item.contact_id === draggedItem.value?.contact_id
  );
  const targetIndex = itemsOfType.findIndex(
    (item) => item.contact_id === targetItem.contact_id
  );

  if (draggedIndex !== -1 && targetIndex !== -1) {
    // Создаем новый массив с измененным порядком
    const reorderedItems = [...itemsOfType];
    const [movedItem] = reorderedItems.splice(draggedIndex, 1);
    reorderedItems.splice(targetIndex, 0, movedItem);

    // Обновляем порядковые номера
    const orderData: { contact_id: number; order: number }[] = [];
    reorderedItems.forEach((item, index) => {
      const newOrder = index + 1;
      if (item.contact_id) {
        orderData.push({
          contact_id: item.contact_id,
          order: newOrder,
        });
        // Обновляем локальные данные
        item.order = newOrder;
      }
    });

    // Обновляем основной массив contacts
    const otherItems = contacts.value.filter((item) => item.type !== type);
    contacts.value = [...otherItems, ...reorderedItems];

    // Отправляем изменения на сервер
    await updateContactsOrder(orderData);
  }

  draggedItem.value = null;
  dragOverItem.value = null;
};

const handleDragEnd = () => {
  draggedItem.value = null;
  dragOverItem.value = null;
};
</script>

<template>
  <div class="my-page p-20">
    <section class="contacts">
      <h2 class="contacts-title my-title m-0">Контакты</h2>
      <LoadingModal
        v-if="isLoading || error"
        :is-loading="isLoading"
        :error="error"
        :retry="handleRetry"
      />
      <div class="contacts-wrapper" v-for="type in contactsType" :key="type">
        <div class="contacts-header">
          <h3 class="contacts-subtitle">{{ type }}</h3>
          <MyBtn variant="primary" @click="toggleEditMode(type)">
            {{ activeEditType === type ? 'Закрыть' : 'Редактировать' }}
          </MyBtn>
        </div>
        <div
          class="contacts-content"
          :class="{ active: activeEditType === type }"
        >
          <div class="contacts-content-wrapper">
            <div class="add-contact">
              <div class="add-contact-label-wrapper">
                <label
                  v-if="getAvailableFields(type).title"
                  class="add-contact-label"
                >
                  <span class="add-contact-label-text">{{
                    getTitleFieldLabel(type)
                  }}</span>
                  <MyInput
                    type="text"
                    variant="primary"
                    v-model="newContact.title"
                  />
                </label>
                <label
                  v-if="getAvailableFields(type).content"
                  class="add-contact-label"
                >
                  <span class="add-contact-label-text">{{
                    getContentFieldLabel(type)
                  }}</span>
                  <div class="quill-editor-wrapper">
                    <QuillEditor
                      v-model:content="newContact.content"
                      contentType="html"
                      theme="snow"
                      :options="quillOptions"
                    />
                  </div>
                </label>
                <label
                  v-if="getAvailableFields(type).link"
                  class="add-contact-label"
                >
                  <span class="add-contact-label-text">{{
                    getLinkFieldLabel(type)
                  }}</span>
                  <MyInput
                    type="text"
                    variant="primary"
                    v-model="newContact.link"
                  />
                </label>
                <label
                  v-if="getAvailableFields(type).icon_path"
                  class="add-contact-label file"
                >
                  <span class="add-contact-label-text">Иконка</span>
                  <MyInput
                    width="150px"
                    height="150px"
                    type="file"
                    variant="primary"
                    :img="newContact.icon_path_url || ''"
                    @fileChange="handleNewFileChange"
                  />
                </label>
              </div>
              <MyBtn
                variant="primary"
                @click="
                  async () => {
                    addContact(type);
                  }
                "
                >Добавить</MyBtn
              >
            </div>
            <ul class="contacts-list list-style-none">
              <li
                class="contacts-item"
                :class="{
                  dragging: draggedItem?.contact_id === item.contact_id,
                  'drag-over': dragOverItem?.contact_id === item.contact_id,
                }"
                v-for="item in contacts.filter((item) => item.type === type)"
                :key="item.contact_id"
                @dragover="handleDragOver($event)"
                @dragenter="handleDragEnter($event, item)"
                @dragleave="handleDragLeave($event)"
                @drop="handleDrop($event, item, type)"
              >
                <div class="item-header">
                  <div class="item-header-wrapper">
                    <MyBtn
                      class="d-and-d-btn"
                      draggable="true"
                      @dragstart="handleDragStart($event, item)"
                      @dragend="handleDragEnd"
                    >
                      <img
                        width="20px"
                        height="20px"
                        src="/src/assets/d-and-d.svg"
                        alt="drag-and-drop"
                      />
                    </MyBtn>
                    <h3 v-if="activeEditItem !== item">{{ item.title }}</h3>
                  </div>
                  <MyInput
                    v-if="activeEditItem === item"
                    type="text"
                    variant="primary"
                    v-model="item.title"
                  />
                  <MyBtn variant="primary" @click="toggleEditItem(item)">
                    {{ activeEditItem === item ? 'Закрыть' : 'Редактировать' }}
                  </MyBtn>
                </div>
                <div
                  class="item-content"
                  :class="{ active: activeEditItem === item }"
                >
                  <div class="item-content-wrapper">
                    <div class="item-conent-inputs">
                      <label v-if="getAvailableFields(item.type).content">
                        <span class="add-contact-label-text">{{
                          getContentFieldLabel(item.type)
                        }}</span>
                        <div class="quill-editor-wrapper">
                          <QuillEditor
                            v-model:content="item.content"
                            contentType="html"
                            theme="snow"
                            :options="quillOptions"
                          />
                        </div>
                      </label>
                      <label v-if="getAvailableFields(item.type).link">
                        <span class="add-contact-label-text">{{
                          getLinkFieldLabel(item.type)
                        }}</span>
                        <MyInput
                          type="text"
                          variant="primary"
                          v-model="item.link as string"
                        />
                      </label>
                      <label
                        v-if="getAvailableFields(item.type).icon_path"
                        class="file"
                      >
                        <span class="add-contact-label-text">Иконка</span>
                        <MyInput
                          width="150px"
                          height="150px"
                          type="file"
                          variant="primary"
                          :img="getImageUrl(item)"
                          @fileChange="
                            (file) => handleEditFileChange(file, item)
                          "
                        />
                      </label>
                    </div>
                    <div class="item-content-footer">
                      <MyBtn variant="primary" @click="updateContact(item)"
                        >Сохранить</MyBtn
                      >
                      <MyBtn variant="primary" @click="deleteContact(item)"
                        >Удалить</MyBtn
                      >
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.contacts {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.contacts-wrapper {
  padding: 16px;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: inset 0 0 0 1px #ffffff;
}

.contacts-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.contacts-content {
  display: grid;
  grid-template-rows: 0fr;
  transition: all 0.3s ease;

  &.active {
    grid-template-rows: 1fr;
  }
}

.contacts-content-wrapper {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.add-contact {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.add-contact-label.file {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 16px;
  max-width: 150px;
}

.contacts-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
  padding: 0;
  margin: 0;
}

.contacts-item {
  box-shadow: inset 0 0 0 1px #ffffff;
  padding: 16px;
  border-radius: 8px;
  transition: all 0.3s ease;

  &:hover {
    transform: translateY(-2px);
    box-shadow: inset 0 0 0 1px #ffffff, 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  &.dragging {
    opacity: 0.5;
    transform: rotate(5deg);
    z-index: 1000;
  }

  &.drag-over {
    transform: translateY(-4px);
    box-shadow: inset 0 0 0 2px #007bff, 0 8px 16px rgba(0, 123, 255, 0.3);
    background-color: rgba(0, 123, 255, 0.05);
  }
}

.item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 30px;
}

.item-content {
  transition: all 0.3s ease;
  display: grid;
  grid-template-rows: 0fr;

  &.active {
    grid-template-rows: 1fr;
  }
}

.item-header-wrapper {
  display: flex;
  align-items: center;
  gap: 16px;

  & .btn {
    min-width: 20px;
    max-width: 20px;
    padding: 0;
    width: 20px;
    height: 20px;
    border: none;
  }
}

.d-and-d-btn {
  cursor: grab !important;
  opacity: 0.7;
  transition: all 0.2s ease;

  &:hover {
    opacity: 1;
    transform: scale(1.1);
  }

  &:active {
    transform: scale(0.95);
  }
}

.item-content-wrapper {
  display: flex;
  flex-direction: column;
  gap: 16px;
  overflow: hidden;
}

.item-conent-inputs {
  display: flex;
  flex-direction: column;
  gap: 16px;

  & > label {
    display: flex;
    flex-direction: column;
    gap: 16px;

    &.file {
      max-width: 150px;
    }
  }
}

.item-content-footer {
  display: flex;
  gap: 16px;
}

:deep(.ql-editor) {
  min-height: 120px;
  max-height: 200px;
  font-size: 16px;
  color: #000;
  background-color: #ffffff;
}

:deep(.ql-toolbar .ql-snow) {
  z-index: 2;
}

:deep(.ql-container) {
  max-height: 200px;
  border-radius: 10px;
  border: 1px solid #363535;
  z-index: 10;
}

:deep(.ql-toolbar) {
  border-radius: 10px 10px 0 0;
  border: 1px solid #363535;
  border-bottom: none;
}
</style>
