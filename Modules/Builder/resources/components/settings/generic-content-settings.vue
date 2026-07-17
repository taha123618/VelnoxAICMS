<template>
  <div class="space-y-4">
    <!-- Rich Text Settings -->
    <div v-if="element.type === 'rich-text'">
      <BuilderTiptap
        label="HTML Content"
        placeholder="Enter rich text..."
        :value="element.getContent('innerText') || ''"
        @change="element.setContent('innerText', $event)"
      />
    </div>

    <!-- List Settings -->
    <div v-else-if="element.type === 'list'" class="space-y-3">
      <BuilderSelect
        label="List Style"
        :options="[
          { label: 'Unordered (Bullets)', value: 'ul' },
          { label: 'Ordered (Numbers)', value: 'ol' }
        ]"
        :value="element.getProp('listType') || 'ul'"
        @change="element.setProps('listType', $event)"
      />
      <BuilderTextarea
        label="List Items (One per line)"
        placeholder="Item 1&#10;Item 2&#10;Item 3"
        :value="listText"
        @change="updateListItems"
      />
    </div>

    <!-- Quote Settings -->
    <div v-else-if="element.type === 'quote'" class="space-y-3">
      <BuilderTextarea
        label="Quote Text"
        :value="element.getContent('innerText') || ''"
        @change="element.setContent('innerText', $event)"
      />
      <BuilderInput
        label="Author / Citation"
        :value="element.getProp('author') || ''"
        @change="element.setProps('author', $event)"
      />
    </div>

    <!-- Code Block Settings -->
    <div v-else-if="element.type === 'code-block'" class="space-y-3">
      <BuilderTextarea
        label="Source Code"
        placeholder="Paste code here..."
        :value="element.getContent('innerText') || ''"
        @change="element.setContent('innerText', $event)"
      />
    </div>

    <!-- Callout Settings -->
    <div v-else-if="element.type === 'callout'" class="space-y-3">
      <BuilderInput
        label="Icon Name"
        placeholder="ph:info-bold"
        :value="element.getProp('icon') || 'ph:info-bold'"
        @change="element.setProps('icon', $event)"
      />
      <BuilderTextarea
        label="Callout Text"
        :value="element.getContent('innerText') || ''"
        @change="element.setContent('innerText', $event)"
      />
    </div>

    <!-- Badge Settings -->
    <div v-else-if="element.type === 'badge'">
      <BuilderInput
        label="Badge Label"
        :value="element.getContent('innerText') || ''"
        @change="element.setContent('innerText', $event)"
      />
    </div>

    <!-- Table Settings -->
    <div v-else-if="element.type === 'table'">
      <BuilderTextarea
        label="Table CSV Data (Headers first, then rows)"
        placeholder="Header 1, Header 2&#10;Val 1, Val 2&#10;Val 3, Val 4"
        :value="element.getContent('innerText') || ''"
        @change="element.setContent('innerText', $event)"
      />
    </div>

    <!-- Breadcrumb Settings -->
    <div v-else-if="element.type === 'breadcrumb'">
      <BuilderInput
        label="Current Page Title"
        :value="element.getProp('pageTitle') || ''"
        @change="element.setProps('pageTitle', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderTextarea from '@modules/Builder/resources/components/form/builder-textarea.vue';
import BuilderTiptap from '@modules/Builder/resources/components/form/editor/builder-tiptap.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { computed } from 'vue';

const { element } = defineProps<{ element: ZioraElement }>();

const listText = computed(() => {
  const raw = element.getContent('listItems');
  if (Array.isArray(raw)) return raw.join('\n');
  if (typeof raw === 'string') return raw;
  return 'List Item 1\nList Item 2\nList Item 3';
});

function updateListItems(value: string) {
  const items = value.split('\n').map(x => x.trim()).filter(Boolean);
  element.setContent('listItems', items as any);
  element.setContent('innerText', items.join('\n'));
}
</script>
