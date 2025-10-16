<script setup lang="ts">
import contactsStore from '../../stores/contactsStore';
import MyRadioInput from '../UI/MyRadioInput.vue';

const store = contactsStore();
</script>

<template>
  <ul
    id="contact-type-list"
    class="addcontact-type-list list-style-none m-0 p-0"
    :class="store.isValid ? '' : 'not-valid'"
  >
    <li
      v-for="contactType in store.contactsTypes"
      :key="contactType"
      class="addcontact-type-item"
    >
    <template v-if="contactType !== 'Карта'">
        <label
          class="addcontact-type-label"
          :class="{
            active: store.newContact.type === contactType,
          }"
        >
          <h3 class="subtitle m-0">{{ contactType }}</h3>
          <MyRadioInput
            variant="primary"
            name="contact"
            v-model="store.newContact.type"
            :value="contactType"
            :checked="store.newContact.type === contactType"
          />
        </label>
      </template>
    </li>
  </ul>
</template>

<style scoped>
.addcontact-type-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  padding: 20px;
}

.addcontact-type-label {
  display: block;
  position: relative;
  padding: 20px;
  color: #fff;
  transition: all 0.3s ease;

  &:hover {
    opacity: 0.7;
  }

  &:active {
    opacity: 0.3;
  }

  & .subtitle {
    position: relative;
    z-index: 2;
    font-size: 16px;
  }

  &.active {
    opacity: 1;
    .subtitle {
      color: #000;
    }
  }
}
</style>
