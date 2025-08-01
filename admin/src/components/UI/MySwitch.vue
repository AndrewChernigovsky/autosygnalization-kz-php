<script setup lang="ts">
interface Props {
  modelValue: boolean;
}
const props = defineProps<Props>();
const emit = defineEmits(['update:modelValue']);

const toggle = () => {
  emit('update:modelValue', !props.modelValue);
};
</script>

<template>
  <label class="switch">
    <input type="checkbox" :checked="props.modelValue" @change="toggle" />
    <span class="slider"></span>
  </label>
</template>

<style scoped>
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 28px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #545458;
  transition: 0.4s;
  border-radius: 28px;
}

.slider:before {
  position: absolute;
  content: '';
  height: 20px;
  width: 20px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #4a90e2;
}

input:focus + .slider {
  box-shadow: 0 0 1px #4a90e2;
}

input:checked + .slider:before {
  transform: translateX(22px);
}
</style>
