<template>
  <div class="space-y-1">
    <template
      v-for="item in dimensions"
      :key="item.id"
    >
      <BuilderInputGroup
        label-position="left"
        select-class="w-auto"
        :show-icons="false"
        :max="10000"
        :disabled="getStyle(`${item.id}.unit`) == 'auto'"
        v-if="element.hasStyle(item.id)"
        :options="item.options"
        :label="item.name"
        :has-changed="hasChanged(`${item.id}.value`) || hasChanged(`${item.id}.unit`)"
        @clear:hover="deleteHoverStyle(item.id)"
        @change:input="setStyle(`${item.id}.value`, $event)"
        @change:select="setStyle(`${item.id}.unit`, $event)"
        :input-value="(getStyle(`${item.id}.value`) as string | number)"
        :select-value="String(getStyle(`${item.id}.unit`))"
      />
    </template>
  </div>
</template>

<script setup lang="ts">
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import BuilderInputGroup from '@modules/Builder/resources/components/form/builder-input-group.vue';

const { element } = defineProps<{ element: VelnoxAIElement }>();

const { getStyle, hasChanged, deleteHoverStyle, setStyle } = useElement(element);

const widthUnitOptions = ["auto", "%", "px", "rem", "vw", "em", "custom"];
const heightUnitOptions = ["auto", "%", "px", "rem", "vh", "em", "custom"];

const maxWidthUnitOptions = ["%", "px", "rem", "vw", "em", "custom"];
const maxHeightUnitOptions = ["%", "px", "rem", "vh", "em", "custom"];

const dimensions = [
  { id: "height", name: "Height", options: heightUnitOptions },
  { id: "minHeight", name: "Min. height", options: heightUnitOptions },
  { id: "maxHeight", name: "Max. height", options: maxHeightUnitOptions },
  { id: "width", name: "Width", options: widthUnitOptions },
  { id: "minWidth", name: "Min width", options: widthUnitOptions },
  { id: "maxWidth", name: "Max width", options: maxWidthUnitOptions },
];
</script>

<style scoped></style>
