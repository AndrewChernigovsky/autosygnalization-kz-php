<script setup lang="ts">
import { onMounted, ref } from 'vue';
import contactsStore from '../stores/contactsStore';
import LoadingModal from '../components/UI/LoadingModal.vue';
import MyInput from '../components/UI/MyInput.vue';
import MyBtn from '../components/UI/MyBtn.vue';
import Swal from 'sweetalert2';

interface IContacts {
  contact_id?: number;
  title: string;
  content: string;
  icon_path: File | string | null; // При редактировании может быть File, из БД приходит string
  icon_path_url?: string | null; // Для предпросмотра
  link: string | null;
  type: string;
}

const API_BASE_URL = '/server/php/admin/api/contacts/contact.php';

const store = contactsStore();

const contacts = ref<IContacts[]>([]);

const newContact = ref<IContacts>({
  title: '',
  content: '',
  icon_path: null, // Изменено с '' на null
  icon_path_url: null,
  link: '', // Изменить с null на пустую строку
  type: '',
});

const isLoading = ref(false);

const error = ref<string | null>(null);

const contactsType = ref<string[]>([]);

const activeEditType = ref<string | null>(null);

const activeEditItem = ref<IContacts | null>(null);

onMounted(async () => {
  await getContacts();
});

const getContacts = async () => {
  isLoading.value = true;
  await store.getContacts(`${API_BASE_URL}`);
  contacts.value = store.contacts;
  contactsType.value = contacts.value.map((contact) => contact.type);
  contactsType.value = [...new Set(contactsType.value)];
  if (contacts.value.length > 0) {
    isLoading.value = false;
  }
};

const addContact = async (type: string) => {
  await store.addContact(API_BASE_URL, newContact.value, type);
  resetNewContact();
};

const updateContact = async (item: IContacts) => {
  await store.updateContact(API_BASE_URL, item.contact_id as number, item);
  await getContacts();
};

const deleteContact = async (item: IContacts) => {
  // Показываем подтверждение перед удалением
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

const handleRetry = async () => {
  await store.getContacts(`${API_BASE_URL}`);
  contacts.value = store.contacts;
};

const toggleEditMode = (type: string) => {
  if (activeEditType.value === type) {
    activeEditType.value = null; // Закрываем если уже открыт
  } else {
    activeEditType.value = type; // Открываем выбранный тип
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
                <label class="add-contact-label">
                  <span class="add-contact-label-text">Заголовок</span>
                  <MyInput
                    type="text"
                    variant="primary"
                    v-model="newContact.title"
                  />
                </label>
                <label class="add-contact-label">
                  <span class="add-contact-label-text">Реквизиты</span>
                  <MyInput
                    type="text"
                    variant="primary"
                    v-model="newContact.content"
                  />
                </label>
                <label class="add-contact-label">
                  <span class="add-contact-label-text">Ссылка</span>
                  <MyInput
                    type="text"
                    variant="primary"
                    v-model="newContact.link"
                  />
                </label>
                <label class="add-contact-label file">
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
                v-for="item in contacts.filter((item) => item.type === type)"
                :key="item.contact_id"
              >
                <div class="item-header">
                  <h3 v-if="activeEditItem !== item">{{ item.title }}</h3>
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
                      <label>
                        <span class="add-contact-label-text">Реквизиты</span>
                        <MyInput
                          type="text"
                          variant="primary"
                          v-model="item.content as string"
                        />
                      </label>
                      <label>
                        <span class="add-contact-label-text">Ссылка</span>
                        <MyInput
                          type="text"
                          variant="primary"
                          v-model="item.link as string"
                        />
                      </label>
                      <label class="file">
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
.contacts-wrapper {
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 16px;
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
    align-items: flex-start;
    justify-content: flex-start;

    &.file {
      max-width: 150px;
    }
  }
}

.item-content-footer {
  display: flex;
  gap: 16px;
}
</style>
