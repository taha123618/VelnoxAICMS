<template>
  <BaseElementWrapper :element="element">
    <!-- Button Element -->
    <a v-if="element.type === 'button'" 
       href="#" 
       class="inline-flex items-center justify-center px-4 py-2 rounded text-sm font-medium transition duration-200 border border-transparent bg-primary text-white hover:bg-primary-hover">
      <UIcon v-if="element.props.icon" :name="element.props.icon" class="size-4 mr-1.5 shrink-0" />
      <span>{{ element.getContent('innerText') || 'Button Text' }}</span>
    </a>

    <!-- Button Group Block -->
    <div v-else-if="element.type === 'button-group'" class="flex flex-row flex-wrap gap-2 items-center">
      <div v-if="element.children.length === 0" class="text-xs text-neutral-500 border border-dashed border-neutral-700 p-2 rounded">
        Button Group (Drag buttons here)
      </div>
      <BaseRecursiveElement v-for="child in element.children" :key="child.id" :element="child" />
    </div>

    <!-- Search Bar -->
    <div v-else-if="element.type === 'search-bar'" class="flex items-center gap-1.5 p-1 border border-neutral-700 bg-neutral-900 rounded w-full max-w-md">
      <UIcon name="ph:magnifying-glass" class="size-4 text-neutral-400 ml-2" />
      <input type="text" disabled :placeholder="element.getProp('placeholder') || 'Search...'" class="bg-transparent text-xs text-white border-0 outline-none flex-1 py-1" />
      <button type="button" class="px-3 py-1 bg-primary text-white text-xs font-medium rounded-sm">Search</button>
    </div>

    <!-- Modal Block (Design Mode) -->
    <div v-else-if="element.type === 'modal'" class="border border-dashed border-primary bg-primary/5 p-3 rounded w-full">
      <div class="flex items-center justify-between border-b border-primary/20 pb-2 mb-2">
        <span class="text-xs font-semibold text-primary flex items-center gap-1">
          <UIcon name="ph:envelope-simple-open" /> Modal Trigger Button & Popup
        </span>
        <button type="button" class="px-2 py-0.5 bg-primary/20 text-primary text-[10px] rounded hover:bg-primary/30">
          Preview Popup
        </button>
      </div>
      <button type="button" class="mb-3 px-3 py-1.5 bg-neutral-800 text-white rounded text-xs font-medium border border-neutral-700">
        {{ element.getProp('triggerLabel') || 'Open Modal' }}
      </button>
      <div class="p-3 bg-neutral-900 border border-neutral-800 rounded">
        <p class="text-[10px] text-neutral-400 mb-2 uppercase tracking-wide font-bold">Popup Children Layout (Drag components here):</p>
        <BaseRecursiveElement v-for="child in element.children" :key="child.id" :element="child" />
      </div>
    </div>

    <!-- Drawer Block (Design Mode) -->
    <div v-else-if="element.type === 'drawer'" class="border border-dashed border-warning bg-warning/5 p-3 rounded w-full">
      <div class="flex items-center justify-between border-b border-warning/20 pb-2 mb-2">
        <span class="text-xs font-semibold text-warning flex items-center gap-1">
          <UIcon name="ph:sidebar-simple" /> Drawer Slide-out Panel
        </span>
      </div>
      <button type="button" class="mb-3 px-3 py-1.5 bg-neutral-800 text-white rounded text-xs font-medium border border-neutral-700">
        {{ element.getProp('triggerLabel') || 'Slide Drawer' }}
      </button>
      <div class="p-3 bg-neutral-900 border border-neutral-800 rounded">
        <p class="text-[10px] text-neutral-400 mb-2 uppercase tracking-wide font-bold">Slide-out Children (Drag components here):</p>
        <BaseRecursiveElement v-for="child in element.children" :key="child.id" :element="child" />
      </div>
    </div>

    <!-- Tooltip Block (Design Mode) -->
    <div v-else-if="element.type === 'tooltip'" class="inline-flex flex-col gap-2 p-2 border border-neutral-800 bg-neutral-950/60 rounded">
      <span class="px-2.5 py-1 bg-neutral-800 text-xs border border-neutral-700 rounded text-white inline-block">
        {{ element.getProp('triggerText') || 'Hover me' }}
      </span>
      <div class="p-2 bg-primary/10 border border-primary/30 rounded text-[10px] text-primary">
        Tooltip Bubble: {{ element.getProp('tooltipText') || 'Tooltip content' }}
      </div>
    </div>

    <!-- Popover Block (Design Mode) -->
    <div v-else-if="element.type === 'popover'" class="border border-neutral-800 p-2 rounded bg-neutral-950/40 w-full">
      <button type="button" class="px-3 py-1 bg-neutral-800 border border-neutral-700 text-white rounded text-xs">
        {{ element.getProp('triggerText') || 'Click me' }}
      </button>
      <div class="mt-2 p-3 bg-neutral-900 border border-neutral-800 rounded">
        <p class="text-[10px] text-neutral-400 mb-2 font-bold">Popover Content Layout (Drag elements here):</p>
        <BaseRecursiveElement v-for="child in element.children" :key="child.id" :element="child" />
      </div>
    </div>

    <div v-else class="text-xs text-red-500">Unresolved interactive component.</div>
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

<style scoped></style>
