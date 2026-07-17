<template>
  <div class="space-y-4">
    <!-- Block Locking -->
    <div class="p-3 bg-neutral-800/40 rounded border border-neutral-700/50">
      <h3 class="text-xs font-semibold text-white mb-2 flex items-center gap-1.5">
        <UIcon name="ph:lock-key-bold" class="size-4 text-warning" />
        Block Locking
      </h3>
      <div class="flex items-center justify-between">
        <span class="text-[11px] text-neutral-400">Lock element placement & deletion</span>
        <input 
          type="checkbox" 
          :checked="element.props.isLocked" 
          @change="toggleLock" 
          class="rounded text-primary focus:ring-primary bg-neutral-900 border-neutral-700 size-4 cursor-pointer"
        />
      </div>
    </div>

    <!-- ARIA Settings -->
    <div class="space-y-3 p-3 bg-neutral-800/20 rounded border border-neutral-700/30">
      <h3 class="text-xs font-semibold text-white mb-1 flex items-center gap-1.5">
        <UIcon name="ph:accessibility-bold" class="size-4 text-primary" />
        Accessibility (ARIA)
      </h3>
      
      <BuilderInput
        label="Aria Label"
        placeholder="Descriptive label for screen readers"
        :value="ariaLabel"
        @change="setAriaProp('aria-label', $event)"
      />

      <BuilderInput
        label="Aria Role"
        placeholder="button, navigation, dialog, etc."
        :value="ariaRole"
        @change="setAriaProp('role', $event)"
      />

      <BuilderSelect
        label="Aria Hidden"
        :options="[
          { label: 'Not Hidden', value: '' },
          { label: 'Hidden (true)', value: 'true' },
          { label: 'Visible (false)', value: 'false' }
        ]"
        :value="ariaHidden"
        @change="setAriaProp('aria-hidden', $event)"
      />
    </div>

    <!-- Custom Data/Attributes Settings -->
    <div class="space-y-3 p-3 bg-neutral-800/20 rounded border border-neutral-700/30">
      <h3 class="text-xs font-semibold text-white mb-1 flex items-center gap-1.5">
        <UIcon name="ph:code-bold" class="size-4 text-success" />
        Custom Attributes
      </h3>
      <p class="text-[10px] text-neutral-500">Enter custom attributes in key=value format (one per line):</p>
      
      <BuilderTextarea
        label="Data & Custom Attributes"
        placeholder="data-id=element-101&#10;class=my-custom-class"
        :value="customAttributesText"
        @change="updateCustomAttributes"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderTextarea from '@modules/Builder/resources/components/form/builder-textarea.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { computed } from 'vue';

const { element } = defineProps<{ element: ZioraElement }>();

function toggleLock(e: Event) {
  const checked = (e.target as HTMLInputElement).checked;
  element.setProps('isLocked', checked);
}

// Compute ARIA fields from custom.attributes dictionary
const ariaLabel = computed(() => getAttr('aria-label'));
const ariaRole = computed(() => getAttr('role'));
const ariaHidden = computed(() => getAttr('aria-hidden'));

function getAttr(key: string): string {
  const attrs = element.getProp('custom.attributes') || {};
  return attrs[key] || '';
}

function setAriaProp(key: string, value: string) {
  const attrs = { ...(element.getProp('custom.attributes') || {}) };
  if (value) {
    attrs[key] = value;
  } else {
    delete attrs[key];
  }
  element.setProps('custom.attributes', attrs);
}

// Compute custom attributes text block excluding ARIA fields
const customAttributesText = computed(() => {
  const attrs = element.getProp('custom.attributes') || {};
  const lines: string[] = [];
  for (const [k, v] of Object.entries(attrs)) {
    if (k !== 'aria-label' && k !== 'role' && k !== 'aria-hidden') {
      lines.push(`${k}=${v}`);
    }
  }
  return lines.join('\n');
});

function updateCustomAttributes(text: string) {
  const lines = text.split('\n').map(l => l.trim()).filter(Boolean);
  const currentAttrs = element.getProp('custom.attributes') || {};
  
  // Retain only ARIA fields
  const newAttrs: Record<string, string> = {};
  if (currentAttrs['aria-label']) newAttrs['aria-label'] = currentAttrs['aria-label'];
  if (currentAttrs['role']) newAttrs['role'] = currentAttrs['role'];
  if (currentAttrs['aria-hidden']) newAttrs['aria-hidden'] = currentAttrs['aria-hidden'];
  
  // Parse and set the new lines
  for (const line of lines) {
    const parts = line.split('=');
    const key = parts[0]?.trim();
    const val = parts.slice(1).join('=').trim();
    if (key) {
      newAttrs[key] = val;
    }
  }
  element.setProps('custom.attributes', newAttrs);
}
</script>
