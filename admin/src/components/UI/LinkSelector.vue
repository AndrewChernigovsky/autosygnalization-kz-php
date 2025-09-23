<script setup lang="ts">
import { computed } from 'vue';
import type { LinkData } from '../../types/FooterLinks';

interface Props {
  modelValue: LinkData | null;
  links: LinkData[];
  label?: string;
  id?: string;
}

const props = withDefaults(defineProps<Props>(), {
  label: '',
  id: '',
});

const emit = defineEmits(['update:modelValue']);

const selectedValue = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
});
</script>

<template>
  <div class="form-group">
    <label v-if="label" :for="id">{{ label }}</label>
    <select :id="id" v-model="selectedValue" class="form-input">
      <option :value="null" disabled>-- Выберите ссылку --</option>
      <option v-for="link in links" :key="link.links_data_id" :value="link">
        {{ link.name }}
      </option>
    </select>
  </div>
</template>

<style scoped>
.form-group {
  display: inline-flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 1rem;
  flex-grow: 0;
  align-items: flex-start;
}
label {
  display: flex;
  flex-direction: column;
  flex-grow: 0;
  font-weight: 500;
  font-size: 0.9rem;
}
select {
  padding: 10px 8px;
  border-radius: 4px;
  border: 1px solid #ccc;
  width: 100%;
  max-width: 400px;
  background-color: black;
  color: white;
}
</style>
