<template>
  <!-- Button Block -->
  <a v-if="element.type === 'button'" 
     :href="element.props.href || '#'" 
     :target="element.props.target || '_self'"
     v-bind="elementAttributes"
     :class="cn(className, customClassNames, animationClass, 'inline-flex items-center justify-center px-4 py-2 rounded text-sm font-medium transition duration-200 border border-transparent bg-primary text-white hover:bg-primary/90')">
    <UIcon v-if="element.props.icon" :name="element.props.icon" class="size-4 mr-1.5 shrink-0" />
    <span>{{ element.getContent('innerText') || 'Button Text' }}</span>
  </a>

  <!-- Button Group Block -->
  <div v-else-if="element.type === 'button-group'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'flex flex-row flex-wrap gap-2 items-center')">
    <BaseRecursiveElement v-for="child in element.children" :key="child.id" :element="child" />
  </div>

  <!-- Search Bar Block -->
  <form v-else-if="element.type === 'search-bar'" 
        action="/search" 
        method="GET"
        v-bind="elementAttributes"
        :class="cn(className, customClassNames, animationClass, 'flex items-center gap-1.5 p-1 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 rounded w-full')">
    <UIcon name="ph:magnifying-glass" class="size-4 text-neutral-400 ml-2" />
    <input type="text" name="q" :placeholder="element.getProp('placeholder') || 'Search...'" class="bg-transparent text-xs text-neutral-800 dark:text-white border-0 outline-none flex-1 py-1" />
    <button type="submit" class="px-3 py-1 bg-primary text-white text-xs font-medium rounded-sm">Search</button>
  </form>

  <!-- Modal Block -->
  <div v-else-if="element.type === 'modal'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass)">
    <UModal v-model:open="isOpen">
      <template #default>
        <button type="button" @click="isOpen = true" class="px-4 py-2 bg-primary text-white rounded text-sm font-medium">
          {{ element.getProp('triggerLabel') || 'Open Modal' }}
        </button>
      </template>
      <template #content>
        <div class="p-6 bg-white dark:bg-neutral-900 rounded-lg shadow-xl max-w-lg mx-auto w-full relative">
          <button @click="isOpen = false" class="absolute top-3 right-3 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200">
            <UIcon name="ph:x-bold" class="size-5" />
          </button>
          <div class="mt-2 space-y-4">
            <BaseRecursiveElement v-for="child in element.children" :key="child.id" :element="child" />
          </div>
        </div>
      </template>
    </UModal>
  </div>

  <!-- Drawer Block -->
  <div v-else-if="element.type === 'drawer'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass)">
    <UDrawer v-model:open="isDrawerOpen">
      <template #default>
        <button type="button" @click="isDrawerOpen = true" class="px-4 py-2 bg-primary text-white rounded text-sm font-medium">
          {{ element.getProp('triggerLabel') || 'Open Drawer' }}
        </button>
      </template>
      <template #content>
        <div class="p-6 bg-white dark:bg-neutral-900 h-full w-80 relative flex flex-col shadow-2xl">
          <button @click="isDrawerOpen = false" class="absolute top-4 right-4 text-neutral-400 hover:text-neutral-600">
            <UIcon name="ph:x-bold" class="size-5" />
          </button>
          <div class="mt-8 flex-1 overflow-y-auto space-y-4">
            <BaseRecursiveElement v-for="child in element.children" :key="child.id" :element="child" />
          </div>
        </div>
      </template>
    </UDrawer>
  </div>

  <!-- Tooltip Block -->
  <div v-else-if="element.type === 'tooltip'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'inline-block')">
    <UTooltip :text="element.getProp('tooltipText') || 'Tooltip content'" :kbds="[]">
      <span class="px-3 py-1.5 border border-neutral-300 dark:border-neutral-700 bg-neutral-100 dark:bg-neutral-800 text-xs rounded text-neutral-900 dark:text-white cursor-help">
        {{ element.getProp('triggerText') || 'Hover me' }}
      </span>
    </UTooltip>
  </div>

  <!-- Popover Block -->
  <div v-else-if="element.type === 'popover'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'inline-block')">
    <UPopover>
      <template #default>
        <button type="button" class="px-4 py-2 bg-neutral-100 dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-xs text-neutral-900 dark:text-white rounded">
          {{ element.getProp('triggerText') || 'Click me' }}
        </button>
      </template>
      <template #content>
        <div class="p-4 bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded shadow-xl w-64 max-w-sm">
          <BaseRecursiveElement v-for="child in element.children" :key="child.id" :element="child" />
        </div>
      </template>
    </UPopover>
  </div>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { ref, computed } from 'vue';

const { element } = defineProps<{
  element: ZioraElement;
}>();

const { customClassNames, animationClass, className } = useElement(element);

const isOpen = ref(false);
const isDrawerOpen = ref(false);

const elementAttributes = computed(() => {
    const attrs: Record<string, any> = {};
    const customAttrs = element.getProp('custom.attributes') || {};
    return { ...attrs, ...customAttrs };
});
</script>

<style scoped></style>
