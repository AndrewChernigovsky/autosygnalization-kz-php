<script setup lang="ts">
import { computed, ref } from 'vue';
import logo from '../../assets/input-file-plus.svg';

interface Props {
  modelValue?: string;
  type?: 'text' | 'email' | 'password' | 'number' | 'tel' | 'file';
  variant?: 'primary' | 'secondary' | '';
  placeholder?: string;
  disabled?: boolean;
  id?: string;
  className?: string;
}

interface Emits {
  (e: 'update:modelValue', value: string): void;
  (e: 'focus', event: FocusEvent): void;
  (e: 'blur', event: FocusEvent): void;
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  placeholder: '',
  disabled: false,
  variant: '',
  className: '',
});

const imgPath = ref('');

const inputClass = computed(() =>
  [
    'my-input',
    props.variant ? props.variant : false,
    props.className ? props.className : false,
  ]
    .filter(Boolean)
    .join(' ')
);

const emit = defineEmits<Emits>();

const handleInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  emit('update:modelValue', target.value);
  if (props.type === 'file') {
    imgPath.value = URL.createObjectURL(target.files?.[0] || new Blob());
    console.log(imgPath.value);
  }
};

const handleFocus = (event: FocusEvent) => {
  emit('focus', event);
};

const handleBlur = (event: FocusEvent) => {
  emit('blur', event);
};
</script>

<template>
  <div :class="['my-input-wrapper', props.type === 'file' ? 'file' : '']">
    <input
      :id="props.id || 'my-input'"
      :type="props.type"
      :value="props.modelValue"
      :placeholder="props.placeholder"
      :disabled="props.disabled"
      :class="inputClass"
      @input="handleInput"
      @focus="handleFocus"
      @blur="handleBlur"
    />
    <img v-if="props.type === 'file'" :src="imgPath || logo" alt="" />
  </div>
</template>

<style scoped>
.my-input-wrapper {
  width: 100%;
  background-color: #141212;
  padding: 10px;
  border-radius: 20%;

  &.file {
    position: relative;
    max-width: 544px;
    height: 100%;
    max-height: 544px;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 30%;

    & img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
  }
}

.my-input {
  padding: 8px;
  border: none;

  &.primary {
    font-family: var(--font-family);
    width: 100%;
    padding: 10px;
    border-radius: 10%;
    color: #000000;
    background-color: #363535;
    font-size: 34px;
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
