<script setup lang="ts">
const props = defineProps<{
  modelValue: boolean;
  value: boolean;
  variant?: 'primary' | 'secondary';
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void;
}>();

const handleChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  emit('update:modelValue', target.checked);
};
</script>

<template>
  <input
    class="my-checkbox-input"
    :class="{
      primary: variant === 'primary',
      secondary: variant === 'secondary',
    }"
    type="checkbox"
    :checked="modelValue"
    @change="handleChange"
  />
</template>

<style scoped>
.my-checkbox-input {
  width: 20px;
  height: 20px;
  transition: all 0.3s ease;

  &.primary {
    display: block;
    position: relative;
    width: 40px;
    height: 20px;
    appearance: none;
    background: linear-gradient(180deg, #280000 0%, #ff0000 100%);
    border-radius: 10px;
    border: 1px solid #fff;

    &::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 2px;
      transform: translateY(-50%);
      width: 15px;
      height: 15px;
      background: #fff;
      border-radius: 50%;
      transition: all 0.3s ease;
    }

    &:checked {
      background: linear-gradient(180deg, #10172d 0%, #0031bc 100%);
      &::before {
        left: 22px;
      }
    }
  }

  &.secondary {
    background-color: #fff;
  }
}
</style>
