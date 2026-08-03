<template>
  <div class="space-y-4">
    <BuilderFourInputGroup
      input-type="select"
      :options="borderStyles"
      :rows="2"
      :has-changed="hasChanged('borderStyle')"
      @clear:hover="deleteHoverStyle('borderStyle')"
      @change="setStyle('borderStyle', $event)"
      :input-value="(getStyle('borderStyle') as Record<string, any>)"
      label="Border style"
    />

    <BuilderFourInputGroup
      :unitOptions="borderWidthUnits"
      :has-changed="hasChanged('borderWidth')"
      @clear:hover="deleteHoverStyle('borderWidth')"
      @change="setStyle('borderWidth', $event)"
      :input-value="(getStyle('borderWidth') as Record<string, any>)"
      label="Border width"
    />

    <BuilderFourInputGroup
      input-type="color"
      :rows="2"
      :has-changed="hasChanged('borderColor')"
      @clear:hover="deleteHoverStyle('borderColor')"
      @change="setStyle('borderColor', $event)"
      :input-value="(getStyle('borderColor') as Record<string, any>)"
      label="Border color"
    />

    <BuilderFourInputGroup
      :unitOptions="borderRadiusUnits"
      :has-changed="hasChanged('borderRadius')"
      @clear:hover="deleteHoverStyle('borderRadius')"
      @change="setStyle('borderRadius', $event)"
      :input-value="(getStyle('borderRadius') as Record<string, any>)"
      label="Border radius"
    />
  </div>
</template>

<script setup lang="ts">
import BuilderFourInputGroup from '@modules/Builder/resources/components/form/builder-four-input-group.vue';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';


const { element } = defineProps<{ element: VelnoxAIElement }>();

const { getStyle, deleteHoverStyle, hasChanged, setStyle } = useElement(element);

const borderWidthUnits = ["px", "rem", "em"];
const borderRadiusUnits = ["px", "%", "rem", "em"];
const borderStyles = ["none", "solid", "dashed", "dotted", "groove", "inset"];
</script>

<style scoped></style>
