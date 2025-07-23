<script setup lang="ts">
import { computed, ref } from 'vue';
import MyBtn from './MyBtn.vue';
import plus from '../../assets/input-file-plus.svg';

interface Props {
  modelValue?: string | null;
  type?: 'text' | 'email' | 'password' | 'number' | 'tel' | 'file';
  variant?: 'primary' | 'secondary' | '';
  placeholder?: string;
  disabled?: boolean;
  id?: string | undefined;
  className?: string;
  width?: string;
  height?: string;
  img?: string;
}

interface Emits {
  (e: 'update:modelValue', value: string | null): void;
  (e: 'fileChange', file: File | null): void;
  (e: 'focus', event: FocusEvent): void;
  (e: 'blur', event: FocusEvent): void;
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  placeholder: '',
  disabled: false,
  variant: '',
  className: '',
  img: '',
});

const imgPath = ref(props.img);
const isLoading = ref(false);

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

const handleInput = async (event: Event) => {
  const target = event.target as HTMLInputElement;

  if (props.type === 'file') {
    const file = target.files?.[0] || null;

    if (file) {
      isLoading.value = true;
      await new Promise((resolve) => setTimeout(resolve, 1500));
      imgPath.value = URL.createObjectURL(file);
      isLoading.value = false;
      emit('fileChange', file);
    } else {
      imgPath.value = '';
      emit('fileChange', null);
    }
  } else {
    emit('update:modelValue', target.value || null);
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
  <div
    :style="{ width: props.width, height: props.height }"
    :class="['my-input-wrapper', props.type === 'file' ? 'file' : '']"
  >
    <input
      :id="props.id || undefined"
      :type="props.type"
      :value="props.modelValue || ''"
      :placeholder="props.placeholder"
      :disabled="props.disabled"
      :class="inputClass"
      @input="handleInput"
      @focus="handleFocus"
      @blur="handleBlur"
    />

    <div v-if="props.type === 'file' && isLoading" class="file-loader">
      <div class="spinner"></div>
      <p>Загрузка файла...</p>
    </div>

    <img
      v-if="props.type === 'file' && !isLoading && imgPath"
      :src="imgPath"
      :alt="imgPath"
    />

    <img
      v-if="props.type === 'file' && !isLoading && !imgPath"
      :src="plus"
      :alt="plus"
    />

    <MyBtn
      class="my-input-delete"
      v-if="props.type === 'file' && !isLoading && imgPath.length > 0"
      type="button"
      @click="imgPath = ''"
      >x
    </MyBtn>
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

.file-loader {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  min-height: 200px;

  p {
    margin-top: 15px;
    color: #666;
    font-size: 16px;
    font-weight: 500;
  }
}

.spinner {
  width: 60px;
  height: 60px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
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
    font-size: 34px;

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

.my-input-delete {
  transition: all 0.3s ease;
  position: absolute;
  top: 0px;
  right: 0px;
  min-width: 30px;
  max-width: 30px;
  height: 30px;
  background: linear-gradient(180deg, #280000 0%, #ff0000 100%);
  border-radius: 50%;
  border: none;
  padding: 0;
  font-size: 15px;
  line-height: 15px;
  text-transform: uppercase;
  color: #ffffff;
  cursor: pointer;

  &:hover {
    opacity: 0.7;
  }

  &:active {
    opacity: 0.3;
  }
}
</style>
