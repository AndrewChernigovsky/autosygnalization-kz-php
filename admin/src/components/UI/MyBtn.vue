<script setup lang="ts">
interface Props {
  type?: 'submit' | 'button' | 'reset';
  variant?: 'primary' | 'secondary' | '';
  disabled?: boolean;
}

interface Emits {
  (e: 'click', event: MouseEvent): void;
}

const props = withDefaults(defineProps<Props>(), {
  type: 'button',
  variant: '',
});

const emit = defineEmits<Emits>();

const handleClick = (event: MouseEvent) => {
  emit('click', event);
};
</script>

<template>
  <button
    :type="props.type"
    :class="['btn', props.variant]"
    :disabled="props.disabled"
    @click="handleClick"
  >
    <slot>btn</slot>
  </button>
</template>

<style scoped>
.btn {
  /* Сброс стилей по умолчанию */
  border: 2px solid #ffffff;
  padding: 8px;
  background: none;
  cursor: pointer;
  outline: none;
  color: black;
  width: auto;
  text-align: center;
  min-width: 200px;
  max-width: 400px;
  transition: all 0.3s ease;
}

.primary {
  background: linear-gradient(180deg, #280000 0%, #ff0000 100%);
  border-radius: 10px;
  font-weight: bold;
  text-transform: uppercase;
  color: white;
}

.secondary {
  background: linear-gradient(180deg, #10172d 0%, #0031bc 100%);
  color: white;
  border-radius: 10px;
  font-weight: bold;
  text-transform: uppercase;
}

.primary:hover,
.primary:focus-visible {
  opacity: 0.7;
}

.primary:active {
  opacity: 0.3;
}

.secondary:hover,
.secondary:focus-visible {
  opacity: 0.7;
}

.secondary:active {
  opacity: 0.3;
}

.primary:disabled,
.secondary:disabled {
  opacity: 0.3;
  cursor: not-allowed;
  background: none;
  background-color: #cdcdcd;
}
</style>
