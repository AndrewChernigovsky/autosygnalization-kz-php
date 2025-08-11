<script setup lang="ts">
import mainNavStore from '../../stores/mainNavStore';
import MyRadioInput from '../UI/MyRadioInput.vue';

const props = defineProps<{
  navItem?: any; // Для редактирования существующих элементов
}>();

const store = mainNavStore();

// Если передан navItem (режим редактирования), используем его link
// Иначе используем newNavItem.link (режим создания)
const getCurrentLink = () => {
  return props.navItem ? props.navItem.link : store.newNavItem.link;
};

const setCurrentLink = (link: string) => {
  if (props.navItem) {
    props.navItem.link = link;
  } else {
    store.newNavItem.link = link;
  }
};
</script>

<template>
  <ul
    id="pages-list"
    class="addlink-pages-list list-style-none m-0 p-0"
    :class="store.isValid ? '' : 'not-valid'"
  >
    <li
      v-for="page in store.availablePages"
      :key="page.link"
      class="addlink-pages-item"
    >
      <label
        class="addlink-pages-label"
        :class="{
          active: getCurrentLink() === page.link,
        }"
        @click="setCurrentLink(page.link)"
      >
        <h3 class="subtitle m-0">{{ page.title }}</h3>
        <MyRadioInput
          variant="primary"
          :name="props.navItem ? `page-${props.navItem.id}` : 'page'"
          :model-value="getCurrentLink()"
          :value="page.link"
          :checked="getCurrentLink() === page.link"
          @update:model-value="setCurrentLink"
        />
      </label>
    </li>
  </ul>
</template>

<style scoped>
.addlink-pages-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  padding: 20px;
}

.addlink-pages-label {
  display: block;
  position: relative;
  padding: 20px;
  color: #fff;
  transition: all 0.3s ease;

  &:hover {
    opacity: 0.7;
  }

  &:active {
    opacity: 0.3;
  }

  & .subtitle {
    position: relative;
    z-index: 2;
    font-size: 16px;
  }

  &.active {
    opacity: 1;
    .subtitle {
      color: #000;
    }
  }
}
</style>
