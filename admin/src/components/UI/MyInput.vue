<script setup lang="ts">
interface Props {
  modelValue?: string;
  type?: 'text' | 'email' | 'password' | 'number' | 'tel';
  placeholder?: string;
  disabled?: boolean;
  id?: string;
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
});

const emit = defineEmits<Emits>();

const handleInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  emit('update:modelValue', target.value);
};

const handleFocus = (event: FocusEvent) => {
  emit('focus', event);
};

const handleBlur = (event: FocusEvent) => {
  emit('blur', event);
};
</script>

<template>
  <div>
    <div class="my-input-wrapper">
      <input
        :id="props.id || 'my-input'"
        :type="props.type"
        :value="props.modelValue"
        :placeholder="props.placeholder"
        :disabled="props.disabled"
        class="my-input"
        @input="handleInput"
        @focus="handleFocus"
        @blur="handleBlur"
      />
    </div>
  </div>
</template>

<style scoped>
.my-input {
  width: 100%;
  max-width: 100%;
  padding: 10px;
  border: 2px solid #363535;
  border-radius: 10px;
  background-color: #141212;
  color: #ffffff;
  outline: none;
  transition: all 0.3s ease;
  font-size: 16px;
}

.my-input::placeholder {
  color: #ffffff;
  opacity: 0.6;
}

/* Состояние с текстом */
.my-input:not(:placeholder-shown) {
  background-color: #363535;
  border-color: #000000;
  color: #ffffff;
}

.my-input:hover,
.my-input:focus-visible {
  opacity: 0.7;
}

.my-input:active {
  opacity: 0.3;
}

.my-input:disabled {
  opacity: 0.3;
  cursor: not-allowed;
  background-color: #cdcdcd;
  border-color: #999999;
  color: #666666;
}
</style>
