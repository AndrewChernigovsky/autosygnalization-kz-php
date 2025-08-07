<script setup lang="ts">
import { computed } from 'vue';

interface IProps {
  modelValue?: string | null;
  type?: 'text' | 'email' | 'password' | 'number' | 'tel';
  variant?: 'primary' | 'secondary' | '';
  placeholder?: string;
  disabled?: boolean;
  id?: string | undefined;
  className?: string;
  name?: string;
}

const props = defineProps<IProps>();

const inputClass = computed(() =>
  [
    'my-input',
    props.variant,
    props.modelValue ? 'active' : false,
    props.className ? props.className : false,
  ]
    .filter(Boolean)
    .join(' ')
);

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | null): void;
}>();
</script>

<template>
  <div class="my-input-wrapper">
    <input
      :class="inputClass"
      :name="name"
      :id="id"
      :type="type"
      :placeholder="placeholder"
      :disabled="disabled"
      :value="modelValue"
      @input="
        emit('update:modelValue', ($event.target as HTMLInputElement)?.value)
      "
    />
  </div>
</template>

<style scoped>
.my-input-wrapper {
  width: 100%;
  background-color: #141212;
  padding: 10px;
  border-radius: 20px;

  &.file {
    position: relative;
    max-width: 544px;
    height: 100%;
    max-height: 544px;
    padding: 10px;
    background-color: #ffffff;
    border-radius: 30px;

    & img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
  }
}

.my-input {
  transition: all 0.3s ease;
  padding: 8px;
  border: none;

  &.primary {
    font-family: var(--font-family);
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    color: #000000;
    background-color: #363535;
    font-size: var(--input-size);
    line-height: var(--input-line-height);

    &:hover,
    &:focus {
      background-color: #ffffff;
      box-shadow: 0 0 0 10px #363535;
      outline: none;
    }
  }

  &:not(:placeholder-shown):not([value='']):not([type='file']).primary {
    color: #000000;
    background-color: #ffffff;
    box-shadow: 0 0 0 10px #363535;
  }

  &.secondary {
    background-color: #363535;
  }

  &[type='file'] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
  }
}

.my-input::placeholder {
  color: #ffffff;
  opacity: 0.6;
}

.my-input:disabled {
  opacity: 0.3;
  cursor: not-allowed;
  background-color: #cdcdcd;
  border-color: #999999;
  color: #666666;
}
</style>
