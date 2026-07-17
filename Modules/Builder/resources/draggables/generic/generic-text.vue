<template>
  <BaseElementWrapper :element="element">
    <!-- Rich Text Block -->
    <div v-if="element.type === 'rich-text'" 
         v-html="element.getContent('innerText') || 'Edit this rich text in settings panel.'" 
         class="w-full prose dark:prose-invert" />

    <!-- List Block -->
    <component :is="element.props.listType || 'ul'" 
               v-else-if="element.type === 'list'" 
               class="list-inside pl-4"
               :class="element.props.listType === 'ol' ? 'list-decimal' : 'list-disc'">
      <li v-for="(item, idx) in listItems" :key="idx" 
          :contenteditable="isSelected" 
          class="outline-none" 
          @blur="updateListItem(idx, $event)">
        {{ item }}
      </li>
    </component>

    <!-- Code Block -->
    <pre v-else-if="element.type === 'code-block'" 
         class="p-4 bg-neutral-950 text-neutral-200 rounded font-mono text-sm overflow-x-auto w-full">
      <code :contenteditable="isSelected" class="outline-none block w-full h-full" @blur="update">{{ content }}</code>
    </pre>

    <!-- Callout Block -->
    <div v-else-if="element.type === 'callout'" 
         class="flex gap-3 p-4 rounded-r border-l-4 border-primary bg-primary/10 w-full text-neutral-800 dark:text-neutral-200">
      <UIcon :name="element.props.icon || 'ph:info-bold'" class="size-5 shrink-0 text-primary mt-0.5" />
      <div ref="editorRef" :contenteditable="isSelected" class="outline-none flex-1" @blur="handleBlur" @input="update">
        {{ content }}
      </div>
    </div>

    <!-- Badge Block -->
    <span v-else-if="element.type === 'badge'" 
          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
      <span ref="editorRef" :contenteditable="isSelected" class="outline-none" @blur="handleBlur" @input="update">
        {{ content }}
      </span>
    </span>

    <!-- Table Block -->
    <table v-else-if="element.type === 'table'" class="min-w-full divide-y divide-neutral-700 border border-neutral-700 text-xs text-left bg-neutral-900/50">
      <thead>
        <tr class="bg-neutral-800 text-white">
          <th v-for="(h, idx) in parsedTable.headers" :key="idx" class="px-4 py-2 border-b border-neutral-700 font-semibold">{{ h }}</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-neutral-800">
        <tr v-for="(row, rIdx) in parsedTable.rows" :key="rIdx" class="hover:bg-neutral-900/30">
          <td v-for="(col, cIdx) in row" :key="cIdx" class="px-4 py-2 border-r border-neutral-700 text-neutral-300">{{ col }}</td>
        </tr>
        <tr v-if="parsedTable.rows.length === 0">
          <td :colspan="parsedTable.headers.length || 1" class="px-4 py-8 text-center text-neutral-500">
            No rows in table. Enter CSV rows in settings panel.
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Breadcrumb Block -->
    <div v-else-if="element.type === 'breadcrumb'" class="flex items-center gap-2 text-xs text-neutral-500 py-1">
      <span>Home</span>
      <UIcon name="ph:caret-right" class="size-3" />
      <span>Category</span>
      <UIcon name="ph:caret-right" class="size-3" />
      <span class="text-neutral-900 dark:text-neutral-200 font-medium">{{ element.props.pageTitle || 'Page Title' }}</span>
    </div>

    <!-- Standard Text Blocks (Heading, Paragraph, Quote, etc.) -->
    <component
      v-else
      ref="editorRef"
      :is="element.props.tag || 'p'"
      :contenteditable="isSelected"
      class="outline-none w-full"
      @input="update"
      @keyup="update"
      @blur="handleBlur"
      @paste.prevent="handlePaste"
    >
      {{ content }}
    </component>
  </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import { computed, ref, onMounted, watch } from 'vue';

const { element } = defineProps<{
  element: ZioraElement;
}>();

const editorRef = ref<HTMLElement | null>(null);
const store = useZiora();

const content = computed<string>(() => {
  return (element.getContent('innerText') || '') as string;
});

const listItems = computed<string[]>(() => {
  const raw = element.getContent('listItems');
  if (Array.isArray(raw)) return raw;
  if (typeof raw === 'string') return raw.split('\n');
  return ['List Item 1', 'List Item 2', 'List Item 3'];
});

const parsedTable = computed(() => {
  const raw = content.value || '';
  const lines = raw.split('\n').map(l => l.trim()).filter(Boolean);
  if (lines.length === 0) return { headers: [], rows: [] };
  const headers = lines[0].split(',').map(h => h.trim());
  const rows = lines.slice(1).map(line => line.split(',').map(c => c.trim()));
  return { headers, rows };
});

const { isSelected } = useElement(element);

function handleBlur() {
  store.clearSelectedElement();
  update();
}

function update(e?: any) {
  let text = '';
  if (e && e.target) {
    text = e.target.innerText;
  } else if (editorRef.value) {
    text = editorRef.value.innerText;
  } else {
    return;
  }
  element.setContent('innerText', text);
}

function updateListItem(idx: number, event: FocusEvent) {
  const text = (event.target as HTMLElement).innerText;
  const items = [...listItems.value];
  items[idx] = text;
  element.setContent('listItems', items as any);
  element.setContent('innerText', items.join('\n'));
}

function handlePaste(event: ClipboardEvent) {
  let text = event.clipboardData?.getData('text/plain');
  if (!text) return;
  text = text.replace(/\r\n/g, ' ').replace(/\n/g, ' ').replace(/\r/g, ' ');

  const selection = window.getSelection();
  if (selection && selection.rangeCount > 0) {
    const range = selection.getRangeAt(0);
    range.deleteContents();
    const textNode = document.createTextNode(text);
    range.insertNode(textNode);
    range.setStartAfter(textNode);
    range.setEndAfter(textNode);
    selection.removeAllRanges();
    selection.addRange(range);
  }
  update();
}

function syncContent() {
  if (editorRef.value && editorRef.value.innerText !== content.value) {
    editorRef.value.innerText = content.value;
  }
}

watch(content, () => {
  syncContent();
});

onMounted(() => {
  syncContent();
});
</script>
