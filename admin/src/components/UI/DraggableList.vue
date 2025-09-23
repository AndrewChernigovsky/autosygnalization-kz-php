<script setup lang="ts" generic="T extends { [key: string]: any }">
import { ref, computed } from 'vue';

const props = defineProps<{
  modelValue: T[];
  itemKey: string;
  tag?: string;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: T[]): void;
  (e: 'reorder', value: T[]): void;
}>();

const draggedItemId = ref<string | number | null>(null);
const dragOverItemId = ref<string | number | null>(null);

const rootEl = computed(() => props.tag || 'div');

const getItemId = (item: T): string | number => {
  return item[props.itemKey];
};

const handleDragStart = (item: T, event: DragEvent) => {
  draggedItemId.value = getItemId(item);
  if (event.dataTransfer) {
    event.dataTransfer.effectAllowed = 'move';

    const dragItemElement = (event.currentTarget as HTMLElement).closest(
      '.draggable-item'
    );
    if (dragItemElement) {
      const clone = dragItemElement.cloneNode(true) as HTMLElement;

      const elementsToHide = clone.querySelectorAll(
        '[data-drag-preview-hide="true"]'
      );
      elementsToHide.forEach((el) => {
        (el as HTMLElement).style.display = 'none';
      });

      clone.style.position = 'absolute';
      clone.style.left = '-9999px';
      clone.style.width = `${dragItemElement.clientWidth}px`;
      document.body.appendChild(clone);

      event.dataTransfer.setDragImage(clone, event.offsetX, event.offsetY);

      setTimeout(() => document.body.removeChild(clone), 0);
    }
  }
};

const handleDragOver = (event: DragEvent, item: T) => {
  event.preventDefault();
  if (draggedItemId.value === null) return;
  dragOverItemId.value = getItemId(item);
};

const handleDragLeave = () => {
  dragOverItemId.value = null;
};

const handleDrop = (targetItem: T) => {
  if (
    draggedItemId.value === null ||
    draggedItemId.value === getItemId(targetItem)
  ) {
    return;
  }

  const localItems = [...props.modelValue];
  const draggedItemIndex = localItems.findIndex(
    (item) => getItemId(item) === draggedItemId.value
  );
  const targetItemIndex = localItems.findIndex(
    (item) => getItemId(item) === getItemId(targetItem)
  );

  if (draggedItemIndex !== -1 && targetItemIndex !== -1) {
    const draggedItem = localItems[draggedItemIndex];
    localItems[draggedItemIndex] = localItems[targetItemIndex];
    localItems[targetItemIndex] = draggedItem;

    emit('update:modelValue', localItems);
    emit('reorder', localItems);
  }
};

const handleDragEnd = () => {
  draggedItemId.value = null;
  dragOverItemId.value = null;
};
</script>

<template>
  <component :is="rootEl" class="draggable-list">
    <div
      v-for="(item, index) in modelValue"
      :key="getItemId(item)"
      class="draggable-item"
      :class="{
        'is-dragging': draggedItemId === getItemId(item),
      }"
      @dragover="handleDragOver($event, item)"
      @dragleave="handleDragLeave"
      @drop.prevent="handleDrop(item)"
    >
      <slot
        name="item"
        :item="item"
        :index="index"
        :is-drag-over="dragOverItemId === getItemId(item)"
        :drag-handle-props="{
          draggable: true,
          onDragstart: (event: DragEvent) => handleDragStart(item, event),
          onDragend: handleDragEnd,
        }"
      ></slot>
    </div>
    <slot name="footer"></slot>
  </component>
</template>

<style scoped>
.draggable-item {
  transition: all 0.2s ease;
}
.draggable-item.is-dragging {
  opacity: 0.5;
  transform: scale(0.98);
}
</style>
