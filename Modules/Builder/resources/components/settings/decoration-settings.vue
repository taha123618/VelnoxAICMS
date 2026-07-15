<template>
  <div class="space-y-4">
    <BuilderColorInput
      label-position="left"
      label="Shadow color"
      :has-changed="hasChanged('boxShadow.color')"
      @clear:hover="deleteHoverStyle('boxShadow.color')"
      @change="setStyle('boxShadow.color', $event)"
      :value="String(getStyle('boxShadow.color'))"
      v-if="element.hasStyle('boxShadow')"
    />

    <BuilderSlider
      v-for="item in shadowProps"
      :key="item.value"
      :label="item.label"
      :max="20"
      :step="1"
      :has-changed="hasChanged(`boxShadow.${item.value}`)"
      @clear:hover="deleteHoverStyle(`boxShadow.${item.value}`)"
      :value="Number(getStyle(`boxShadow.${item.value}`))"
      @change="setStyle(`boxShadow.${item.value}`, $event)"
    />
  </div>
</template>

<script setup lang="ts">
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{ element: ZioraElement }>();

const { getStyle, deleteHoverStyle, hasChanged, setStyle } =
  useElement(element);

const shadowProps = [
  { value: "blur", label: "Blur" },
  { value: "spread", label: "Spread" },
  { value: "x", label: "Horizontal" },
  { value: "y", label: "Vertical" },
];
</script>

<style scoped></style>
