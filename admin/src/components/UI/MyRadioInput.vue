<script setup lang="ts">
const props = defineProps<{
  name: string;
  value: string;
  checked: boolean;
  variant?: 'primary' | 'secondary';
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
  (e: 'update:checked', value: boolean): void;
}>();

const handleChange = () => {
  emit('update:modelValue', props.value);
  emit('update:checked', !props.checked);
};
</script>

<template>
  <input
    class="my-radio-input m-0 p-0 border-none"
    :class="props.variant"
    type="radio"
    :name="props.name"
    :value="props.value"
    @change="handleChange"
  />
</template>

<style scoped>
.my-radio-input {
  transition: all 0.3s ease;

  &.primary {
    position: absolute;
    display: flex;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    appearance: none;
    width: 100%;
    height: 100%;
    background-color: #363535;
    border-radius: 10px;
    cursor: pointer;

    &:checked {
      box-shadow: 0 0 0 6px #363535;
      background-color: #fff;
    }
  }
}
</style>
