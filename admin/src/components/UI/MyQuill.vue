<script setup lang="ts">
import { QuillEditor } from '@rafaeljunioxavier/vue-quill-fix';
import '@rafaeljunioxavier/vue-quill-fix/dist/vue-quill.snow.css';
import { ref, watch } from 'vue';

const props = defineProps<{
  content: string | null;
  quillOptions?: any;
}>();

const content = ref(props.content);

const emit = defineEmits<{
  (e: 'update:content', value: string): void;
}>();

const presetQuillOptions = {
  placeholder: 'Введите текст...',
  formats: ['header', 'bold', 'italic', 'underline', 'list', 'link'],
  modules: {
    toolbar: [
      [{ header: [false] }],
      ['bold', 'italic', 'underline'],
      [{ list: 'bullet' }, { list: 'ordered' }],
      ['link'],
      ['clean'],
    ],
  },
};

watch(content, (newVal) => {
  emit('update:content', newVal ?? '');
});
</script>

<template>
  <div class="my-quill-wrapper">
    <QuillEditor
      v-model:content="content"
      theme="snow"
      :options="presetQuillOptions ? presetQuillOptions : props.quillOptions"
      content-type="html"
    />
  </div>
</template>

<style scoped>
.my-quill-wrapper :deep(.ql-editor) {
  list-style: none;
  padding-left: 1.5em;

  /* Маркеры для bullet-списков */
  li[data-list='bullet']::before {
    content: '•';
    margin-right: 0.5em;
    color: inherit;
  }

  /* Нумерация для обычных <li> (без data-list) — ordered списки */
  li:not([data-list])::before {
    content: counter(ql-list, decimal) '. ';
    margin-right: 0.5em;
  }
}
</style>
