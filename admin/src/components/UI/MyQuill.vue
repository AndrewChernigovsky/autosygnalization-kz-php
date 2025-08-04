<script setup lang="ts">
import { QuillEditor } from '@rafaeljunioxavier/vue-quill-fix';
import '@rafaeljunioxavier/vue-quill-fix/dist/vue-quill.snow.css';

const emit = defineEmits<{
  (e: 'update:content', value: string): void;
}>();

const props = defineProps<{
  content: string;
  quillOptions?: any;
}>();

const presetQuillOptions = {
  placeholder: 'Введите текст...',
  formats: ['header', 'bold', 'italic', 'underline', 'list', 'link'],
  modules: {
    toolbar: [
      [{ header: [false] }],
      ['bold', 'italic', 'underline'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['link'],
      ['clean'],
    ],
  },
};
</script>

<template>
  <div class="my-quill-wrapper">
    <QuillEditor
      v-model:content="props.content"
      theme="snow"
      :options="presetQuillOptions ? presetQuillOptions : props.quillOptions"
    />
  </div>
</template>

<style scoped></style>
