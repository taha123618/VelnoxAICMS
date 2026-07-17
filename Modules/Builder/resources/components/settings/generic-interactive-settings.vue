<template>
  <div class="space-y-4">
    <!-- Button Settings -->
    <div v-if="element.type === 'button'" class="space-y-3">
      <BuilderInput
        label="Button Text"
        :value="element.getContent('innerText') || ''"
        @change="element.setContent('innerText', $event)"
      />
      <BuilderInput
        label="Link URL (Href)"
        placeholder="https://example.com"
        :value="element.props.href || ''"
        @change="element.setProps('href', $event)"
      />
      <BuilderSelect
        label="Target Link behavior"
        :options="[
          { label: 'Same Tab (_self)', value: '_self' },
          { label: 'New Tab (_blank)', value: '_blank' }
        ]"
        :value="element.props.target || '_self'"
        @change="element.setProps('target', $event)"
      />
      <BuilderInput
        label="Icon Name (Prefix)"
        placeholder="ph:arrow-right-bold"
        :value="element.props.icon || ''"
        @change="element.setProps('icon', $event)"
      />
    </div>

    <!-- Search Bar Settings -->
    <div v-else-if="element.type === 'search-bar'" class="space-y-3">
      <BuilderInput
        label="Input Placeholder"
        placeholder="Search..."
        :value="element.getProp('placeholder') || ''"
        @change="element.setProps('placeholder', $event)"
      />
    </div>

    <!-- Modal Settings -->
    <div v-else-if="element.type === 'modal'" class="space-y-3">
      <BuilderInput
        label="Trigger Button Label"
        placeholder="Open Modal"
        :value="element.getProp('triggerLabel') || ''"
        @change="element.setProps('triggerLabel', $event)"
      />
    </div>

    <!-- Drawer Settings -->
    <div v-else-if="element.type === 'drawer'" class="space-y-3">
      <BuilderInput
        label="Trigger Button Label"
        placeholder="Open Drawer"
        :value="element.getProp('triggerLabel') || ''"
        @change="element.setProps('triggerLabel', $event)"
      />
    </div>

    <!-- Tooltip Settings -->
    <div v-else-if="element.type === 'tooltip'" class="space-y-3">
      <BuilderInput
        label="Trigger Text"
        placeholder="Hover me"
        :value="element.getProp('triggerText') || ''"
        @change="element.setProps('triggerText', $event)"
      />
      <BuilderInput
        label="Tooltip Text"
        placeholder="Tooltip info text"
        :value="element.getProp('tooltipText') || ''"
        @change="element.setProps('tooltipText', $event)"
      />
    </div>

    <!-- Popover Settings -->
    <div v-else-if="element.type === 'popover'" class="space-y-3">
      <BuilderInput
        label="Trigger Text"
        placeholder="Click me"
        :value="element.getProp('triggerText') || ''"
        @change="element.setProps('triggerText', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

defineProps<{ element: ZioraElement }>();
</script>
