<template>
  <!-- Rich Text Block -->
  <div v-if="element.type === 'rich-text'" 
       v-html="element.getContent('innerText')" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'prose dark:prose-invert')" />

  <!-- List Block -->
  <component :is="element.props.listType || 'ul'" 
             v-else-if="element.type === 'list'" 
             v-bind="elementAttributes"
             :class="cn(className, customClassNames, animationClass, 'list-inside pl-4', element.props.listType === 'ol' ? 'list-decimal' : 'list-disc')">
    <li v-for="(item, idx) in listItems" :key="idx">
      {{ item }}
    </li>
  </component>

  <!-- Code Block -->
  <pre v-else-if="element.type === 'code-block'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'p-4 bg-neutral-950 text-neutral-200 rounded font-mono text-sm overflow-x-auto')">
    <code>{{ content }}</code>
  </pre>

  <!-- Callout Block -->
  <div v-else-if="element.type === 'callout'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'flex gap-3 p-4 rounded-r border-l-4 border-primary bg-primary/10 w-full text-neutral-800 dark:text-neutral-200')">
    <UIcon :name="element.props.icon || 'ph:info-bold'" class="size-5 shrink-0 text-primary mt-0.5" />
    <div class="flex-1">{{ content }}</div>
  </div>

  <!-- Badge Block -->
  <span v-else-if="element.type === 'badge'" 
        v-bind="elementAttributes"
        :class="cn(className, customClassNames, animationClass, 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary')">
    {{ content }}
  </span>

  <!-- Table Block -->
  <table v-else-if="element.type === 'table'" 
         v-bind="elementAttributes"
         :class="cn(className, customClassNames, animationClass, 'min-w-full divide-y divide-neutral-300 dark:divide-neutral-700 border border-neutral-300 dark:border-neutral-700 text-xs text-left bg-white dark:bg-neutral-900')">
    <thead>
      <tr class="bg-neutral-100 dark:bg-neutral-800 text-neutral-900 dark:text-white">
        <th v-for="(h, idx) in parsedTable.headers" :key="idx" class="px-4 py-2 border-b border-neutral-300 dark:border-neutral-700 font-semibold">{{ h }}</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-850">
      <tr v-for="(row, rIdx) in parsedTable.rows" :key="rIdx" class="hover:bg-neutral-50 dark:hover:bg-neutral-800/30">
        <td v-for="(col, cIdx) in row" :key="cIdx" class="px-4 py-2 border-r border-neutral-200 dark:border-neutral-800 text-neutral-700 dark:text-neutral-300">{{ col }}</td>
      </tr>
      <tr v-if="parsedTable.rows.length === 0">
        <td :colspan="parsedTable.headers.length || 1" class="px-4 py-8 text-center text-neutral-500">
          No rows in table.
        </td>
      </tr>
    </tbody>
  </table>

  <!-- Breadcrumb Block -->
  <div v-else-if="element.type === 'breadcrumb'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'flex items-center gap-2 text-xs text-neutral-500 py-1')">
    <span>Home</span>
    <UIcon name="ph:caret-right" class="size-3" />
    <span>Category</span>
    <UIcon name="ph:caret-right" class="size-3" />
    <span class="text-neutral-900 dark:text-neutral-200 font-medium">{{ element.props.pageTitle || 'Page Title' }}</span>
  </div>

  <!-- Standard Text Blocks -->
  <component
    v-else
    :is="element.props.tag || 'p'"
    ref="elRef"
    v-bind="elementAttributes"
    :class="cn(className, customClassNames, animationClass, 'builder-element box-border outline-none')"
  >
    {{ content }}
  </component>
</template>

<script setup lang="ts">
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { computed } from 'vue';

const { element } = defineProps<{
  element: ZioraElement;
}>();

const { customClassNames, animationClass, className } = useElement(element);

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

const elementAttributes = computed(() => {
    const attrs: Record<string, any> = {};
    const customAttrs = element.getProp('custom.attributes') || {};
    return { ...attrs, ...customAttrs };
});
</script>

<style scoped></style>
