<template>
  <UModal v-model:open="isOpen">
    <template #content>
      <div class="w-full max-w-lg bg-neutral-900 border border-neutral-700/50 rounded-lg shadow-2xl overflow-hidden flex flex-col h-[400px]">
        <!-- Search Input -->
        <div class="flex items-center gap-2 px-4 py-3 border-b border-neutral-800 bg-neutral-950">
          <UIcon name="ph:magnifying-glass-bold" class="size-5 text-neutral-400 shrink-0" />
          <input
            ref="inputRef"
            type="text"
            v-model="searchQuery"
            placeholder="Search blocks... (e.g. Columns, Video, Form)"
            class="w-full bg-transparent text-sm text-white border-0 outline-none placeholder-neutral-500"
            @keydown.down.prevent="onKeyDown"
            @keydown.up.prevent="onKeyUp"
            @keydown.enter.prevent="onKeyEnter"
          />
          <kbd class="text-[10px] bg-neutral-800 text-neutral-400 px-1.5 py-0.5 rounded font-mono shrink-0">ESC</kbd>
        </div>

        <!-- Matching List -->
        <div class="flex-1 overflow-y-auto p-2 space-y-1">
          <div v-if="filteredElements.length === 0" class="flex flex-col items-center justify-center h-full text-neutral-500 space-y-2">
            <UIcon name="ph:seal-question-bold" class="size-8 text-neutral-600" />
            <span class="text-xs">No matching blocks found.</span>
          </div>

          <div
            v-for="(el, idx) in filteredElements"
            :key="el.id"
            :class="[
              'flex items-center justify-between p-2.5 rounded cursor-pointer transition duration-150',
              idx === activeIndex ? 'bg-primary text-white' : 'hover:bg-neutral-800 text-neutral-300'
            ]"
            @click="insertBlock(el)"
            @mouseenter="activeIndex = idx"
          >
            <div class="flex items-center gap-3">
              <UIcon :name="el.icon || 'ph:cube-bold'" class="size-5 shrink-0" :class="idx === activeIndex ? 'text-white' : 'text-neutral-400'" />
              <div>
                <p class="text-xs font-semibold">{{ el.name }}</p>
                <p class="text-[10px] capitalize" :class="idx === activeIndex ? 'text-white/80' : 'text-neutral-500'">{{ el.category || 'element' }}</p>
              </div>
            </div>
            <UIcon name="ph:plus-circle-bold" class="size-4 opacity-0 group-hover:opacity-100" />
          </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-2 border-t border-neutral-800 bg-neutral-950 flex items-center justify-between text-[10px] text-neutral-500 font-mono">
          <span class="flex items-center gap-1">
            <UIcon name="ph:arrow-down" class="size-3" />
            <UIcon name="ph:arrow-up" class="size-3" />
            Navigate
          </span>
          <span>Press Enter to insert</span>
        </div>
      </div>
    </template>
  </UModal>
</template>

<script setup lang="ts">
import { elementGroups } from '@modules/Builder/resources/draggables';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { InsertLocation } from '@modules/Builder/resources/scripts/enums';
import { findParentFromId } from '@modules/Builder/resources/scripts/factory';
import { computed, ref, watch, nextTick } from 'vue';

const props = defineProps<{
  open: boolean;
}>();

const emit = defineEmits<{
  (e: 'update:open', val: boolean): void;
}>();

const store = useZiora();
const searchQuery = ref('');
const activeIndex = ref(0);
const inputRef = ref<HTMLInputElement | null>(null);

const isOpen = computed({
  get: () => props.open,
  set: (val) => emit('update:open', val)
});

// Extract all flat elements
const allElements = computed(() => {
  return elementGroups.flatMap(group => group.components);
});

// Filter matching elements
const filteredElements = computed(() => {
  const q = searchQuery.value.toLowerCase().trim();
  if (!q) return allElements.value;
  return allElements.value.filter(el => 
    el.name.toLowerCase().includes(q) || 
    el.type.toLowerCase().includes(q) ||
    el.category?.toLowerCase().includes(q)
  );
});

watch(filteredElements, () => {
  activeIndex.value = 0;
});

watch(isOpen, (newVal) => {
  if (newVal) {
    searchQuery.value = '';
    activeIndex.value = 0;
    nextTick(() => {
      inputRef.value?.focus();
    });
  }
});

function onKeyDown() {
  if (activeIndex.value < filteredElements.value.length - 1) {
    activeIndex.value++;
  }
}

function onKeyUp() {
  if (activeIndex.value > 0) {
    activeIndex.value--;
  }
}

function onKeyEnter() {
  const el = filteredElements.value[activeIndex.value];
  if (el) {
    insertBlock(el);
  }
}

function insertBlock(config: any) {
  const newElement = ZioraElement.newFromObject(config);
  
  // Decide where to insert
  let targetParentId = 'el__body';
  let insertAt: InsertLocation | null = null;
  
  if (store.selectedElement) {
    if (store.selectedElement.canDrop) {
      targetParentId = store.selectedElement.id;
    } else {
      // Find parent of selected element
      const parent = findParentFromId(store.elements, store.selectedElement.id) as ZioraElement | null;
      if (parent) {
        targetParentId = parent.id;
        insertAt = InsertLocation.After;
      }
    }
  }
  
  store.addNewElement(targetParentId, newElement, insertAt);
  store.setSelectedElement(newElement);
  
  isOpen.value = false;
}
</script>
