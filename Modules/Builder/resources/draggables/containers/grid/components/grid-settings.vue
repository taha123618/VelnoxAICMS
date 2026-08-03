<template>
  <div class="space-y-4">
    <VisibilitySettings
      :options="['grid', 'none']"
      :element="element"
    />
    <div class="grid grid-cols-2 gap-2">
      <BuilderNumberInput
        label="Columns"
        :min="1"
        :value="Number(getStyle('gridTemplateColumns.value'))"
        :has-changed="hasChanged('gridTemplateColumns.value')"
        @clear:hover="deleteHoverStyle('gridTemplateColumns.value')"
        @change="setStyle('gridTemplateColumns.value', $event)"
      />

      <BuilderNumberInput
        label="Rows"
        :min="1"
        :value="Number(getStyle('gridTemplateRows.value'))"
        :has-changed="hasChanged('gridTemplateRows.value')"
        @clear:hover="deleteHoverStyle('gridTemplateRows.value')"
        @change="setStyle('gridTemplateRows.value', $event)"
      />
    </div>

    <div class="grid grid-cols-2 gap-x-2">
      <BuilderNumberInput
        label="Col gaps"
        :value="Number(getStyle('columnGap') || 0)"
        :has-changed="hasChanged('columnGap')"
        @clear:hover="deleteHoverStyle('columnGap')"
        @change="setStyle('columnGap', $event)"
      />

      <BuilderNumberInput
        label="Row gaps"
        :value="Number(getStyle('rowGap') || 0)"
        :has-changed="hasChanged('rowGap')"
        @clear:hover="deleteHoverStyle('rowGap')"
        @change="setStyle('rowGap', $event)"
      />
    </div>

    <BuilderToggleGroup
      label-position="left"
      :value="getStyle('justifyItems')"
      :has-changed="hasChanged('justifyItems')"
      @clear:hover="deleteHoverStyle('justifyItems')"
      @change="setStyle('justifyItems', $event)"
      option-is-object
      label="Justify items"
      :options="gridLayout"
    />

    <BuilderToggleGroup
      label-position="left"
      :value="getStyle('alignItems')"
      :has-changed="hasChanged('alignItems')"
      @clear:hover="deleteHoverStyle('alignItems')"
      @change="setStyle('alignItems', $event)"
      option-is-object
      label="Align items"
      :options="gridLayout"
    />
  </div>
</template>

<script setup lang="ts">
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import VisibilitySettings from '@modules/Builder/resources/components/settings/visibility-settings.vue';
import BuilderNumberInput from '@modules/Builder/resources/components/form/builder-number-input.vue';
import BuilderToggleGroup from '@modules/Builder/resources/components/form/builder-toggle-group.vue';
import { gridLayout } from '@modules/Builder/resources/scripts/constants';

const { element } = defineProps<{ element: VelnoxAIElement }>();

const { getStyle, hasChanged, deleteHoverStyle, setStyle } = useElement(element);


</script>

<style scoped></style>
