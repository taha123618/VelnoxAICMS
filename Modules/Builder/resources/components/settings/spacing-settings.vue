<template>
  <div class="space-y-4">
    <BuilderFourInputGroup
      :unit-options="marginUnitOptions"
      :has-changed="hasChanged('margin')"
      :is-decimal="(getStyle('margin') as Record<string, any>)?.unit !== 'px'"
      @clear:hover="deleteHoverStyle('margin')"
      @change="setStyle('margin', $event)"
      :input-value="(getStyle('margin') as Record<string, any>)"
      label="Margin"
    />

    <BuilderFourInputGroup
      :unit-options="paddingUnitOptions"
      :is-decimal="(getStyle('padding') as Record<string, any>)?.unit !== 'px'"
      :has-changed="hasChanged('padding')"
      @clear:hover="deleteHoverStyle('padding')"
      @change="setStyle('padding', $event)"
      :input-value="(getStyle('padding') as Record<string, any>)"
      label="Padding"
    />
  </div>
</template>

<script setup lang="ts">
import BuilderFourInputGroup from "@modules/Builder/resources/components/form/builder-four-input-group.vue";
import { useElement } from "@modules/Builder/resources/scripts/use-element";
import ZioraElement from "@modules/Builder/resources/scripts/ziora-element";
const { element } = defineProps<{ element: ZioraElement }>();

const { getStyle, hasChanged, deleteHoverStyle, setStyle } = useElement(element);

const marginUnitOptions = ["px", "rem", "em", "custom"];

const paddingUnitOptions = ["px", "rem", "em"];

</script>

<style scoped></style>
