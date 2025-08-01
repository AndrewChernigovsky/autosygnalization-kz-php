<script setup lang="ts">
import { computed } from 'vue';

interface LinkData {
  links_data_id: number;
  name: string;
  link: string;
  source_table: string;
}

interface Props {
  modelValue: string;
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
    <label v-if="props.label" :for="props.id">{{ props.label }}</label>
    <select :id="props.id" v-model="selectedValue">
      <option disabled value="">-- Выберите ссылку --</option>
      <option
        v-for="link in props.links"
        :key="link.links_data_id"
        :value="link.link"
      >
        {{ link.name }}
      </option>
    </select>
  </div>
</template>

<style scoped>
.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 1rem;
}
label {
  font-weight: 500;
  font-size: 0.9rem;
}
select {
  padding: 10px 8px;
  border-radius: 4px;
  border: 1px solid #ccc;
  width: 100%;
  max-width: 400px;
  background-color: #fff;
}
</style>
