<template>
  <BaseElementWrapper :element="element">
    <BaseRecursiveElement
      v-for="child in element.children"
      :key="child.id"
      :element="child"
    />
  </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
const { element } = defineProps<{
  element: ZioraElement;
}>();
</script>

<style scoped>
</style>
