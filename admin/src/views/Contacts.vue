<script setup lang="ts">
import ContactsSection from '../components/Contacts/ContactsSection.vue';
import SocialSection from '../components/Contacts/SocialSection.vue';
import ContactsMapSection from '../components/Contacts/ContactsMapSection.vue';
import AdressSection from '../components/Contacts/AdressSection.vue';
import ScheduleSecyion from '../components/Contacts/ScheduleSecyion.vue';
import SecondaryContactsPhone from '../components/Contacts/SecondaryContactsPhone.vue';
import EmailSection from '../components/Contacts/EmailSection.vue';
</script>

<template>
  <div class="container">
    <ContactsSection />
    <SecondaryContactsPhone />
    <SocialSection />
    <ContactsMapSection />
    <AdressSection />
    <ScheduleSecyion />
    <EmailSection />
  </div>
</template>

<style scoped>
.container {
  grid-column: 2 / 3;
}
</style>
