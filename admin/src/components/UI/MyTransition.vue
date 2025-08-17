<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  duration?: number;
}>();

const emit = defineEmits(['after-enter']);

const durationInMs = computed(() => `${props.duration || 400}ms`);

const beforeEnter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = '0';
  htmlEl.style.opacity = '0';
  htmlEl.style.paddingTop = '0';
  htmlEl.style.paddingBottom = '0';
  htmlEl.style.marginTop = '0';
};
const enter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = `${htmlEl.scrollHeight}px`;
  htmlEl.style.opacity = '1';
  htmlEl.style.paddingTop = '';
  htmlEl.style.paddingBottom = '';
  htmlEl.style.marginTop = '';
};
const afterEnter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = '';
  emit('after-enter');
};
const beforeLeave = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = `${htmlEl.scrollHeight}px`;
};
const leave = (el: Element) => {
  const htmlEl = el as HTMLElement;
  getComputedStyle(htmlEl).height;
  requestAnimationFrame(() => {
    htmlEl.style.height = '0';
    htmlEl.style.opacity = '0';
    htmlEl.style.paddingTop = '0';
    htmlEl.style.paddingBottom = '0';
    htmlEl.style.marginTop = '0';
  });
};
</script>

<template>
  <transition
    @before-enter="beforeEnter"
    @enter="enter"
    @after-enter="afterEnter"
    @before-leave="beforeLeave"
    @leave="leave"
  >
    <slot />
  </transition>
</template>

<style>
.v-enter-active,
.v-leave-active {
  transition: all v-bind(durationInMs) ease-out;
  overflow: hidden;
}
</style>
