<template>
  <BaseElementWrapper :element="element">
    <!-- Empty state helper for design mode -->
    <div v-if="element.children.length === 0" class="flex items-center justify-center p-6 border border-dashed border-neutral-700 bg-neutral-900/30 text-neutral-500 rounded text-xs w-full min-h-[60px]">
      <div class="flex items-center gap-1.5">
        <UIcon :name="element.icon || 'ph:plus'" class="size-4" />
        <span>Empty {{ element.name }}. Drag elements here.</span>
      </div>
    </div>
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

defineProps<{
  element: ZioraElement;
}>();
</script>

<style scoped>
</style>
