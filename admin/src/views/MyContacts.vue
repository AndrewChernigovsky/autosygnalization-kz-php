<script setup lang="ts">
import useContactsStore from '../stores/contactsStore';
import { onMounted } from 'vue';
import AddContact from '../components/Contacts/AddContact.vue';
import LoadingModal from '../components/UI/LoadingModal.vue';
import ContactsList from '../components/Contacts/ContactsList.vue';

const contactsStore = useContactsStore();

onMounted(() => {
  contactsStore.getContacts();
});
</script>

<template>
  <section class="contacts">
    <h2 class="contacts-title my-title m-0">Контакты</h2>
    <LoadingModal
      v-if="contactsStore.isLoading || contactsStore.error"
      :isLoading="contactsStore.isLoading"
      :error="contactsStore.error"
      :retry="contactsStore.getContacts"
    />
    <div v-else>
      <AddContact />
      <ContactsList />
    </div>
  </section>
</template>

<style scoped>
.contacts {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
</style>
