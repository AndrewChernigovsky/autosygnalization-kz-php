<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Header from '../components/layout/Header/Header.vue';
import contactsStore from '../stores/contactsStore';
import LoadingModal from '../components/UI/LoadingModal.vue';

interface IContacts {
  contact_id: number;
  title: string;
  content: string;
  icon_path: string | null;
  link: string | null;
  type: string;
}

const API_BASE_URL = '/server/php/admin/api/contacts/contact.php';

const store = contactsStore();

const contacts = ref<IContacts[]>([]);

const isLoading = ref(false);

const error = ref<string | null>(null);

const contactsType = ref<string[]>([]);

const contactsPhone = ref<IContacts[]>([]);
const contactsEmail = ref<IContacts[]>([]);

onMounted(async () => {
  isLoading.value = true;
  await store.getContacts(`${API_BASE_URL}`);
  contacts.value = store.contacts;
  contactsType.value = contacts.value.map((contact) => contact.type);
  contactsType.value = [...new Set(contactsType.value)];
  console.log(contactsType.value, 'contactsType');
  contactsPhone.value = contacts.value.filter(
    (contact) => contact.type === 'main-phone'
  );
  contactsEmail.value = contacts.value.filter(
    (contact) => contact.type === 'email'
  );
  if (contacts.value.length > 0) {
    isLoading.value = false;
  }
});

const handleRetry = async () => {
  await store.getContacts(`${API_BASE_URL}`);
  contacts.value = store.contacts;
};
</script>

<template>
  <div class="my-page p-20">
    <Header />
    <section class="ui">
      <h2 class="my-title">Контакты</h2>
      <LoadingModal
        v-if="isLoading || error"
        :is-loading="isLoading"
        :error="error"
        :retry="handleRetry"
      />
      <div v-else>
        <ul>
          <li v-for="contact in contacts" :key="contact.contact_id">
            <h3>{{ contact.title }}</h3>
            <p>{{ contact.content }}</p>
            <p>{{ contact.link }}</p>
            <p>{{ contact.type }}</p>
          </li>
        </ul>
      </div>
    </section>
  </div>
</template>

<style scoped>
.my-title {
  text-align: center;
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.9);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  gap: 1rem;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #42b883;
  border-radius: 50%;
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

.error-message {
  color: #dc3545;
  padding: 2rem;
  background: #f8d7da;
  border-radius: 8px;
  margin: 2rem 0;
  text-align: center;
}

.retry-btn {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  background: #42b883;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s;
}

.retry-btn:hover {
  background: #3aa876;
}
</style>
