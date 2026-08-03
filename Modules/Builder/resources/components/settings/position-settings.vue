<template>
  <div class="space-y-1">
    <BuilderNumberInput
      label-position="left"
      label="z-index"
      :min="-100"
      :value="Number(getStyle('zIndex.value'))"
      :has-changed="hasChanged('zIndex.value')"
      @clear:hover="deleteHoverStyle('zIndex.value')"
      @change="setStyle('zIndex.value', $event)"
    />

    <BuilderSelect
      label-position="left"
      label="Position"
      :options="positions"
      :has-changed="hasChanged('position')"
      @clear:hover="deleteHoverStyle('position')"
      @change="setStyle('position', $event)"
      :value="String(getStyle('position'))"
    />

    <template
      v-for="item in positionEntries"
      :key="item.id"
    >
      <BuilderInputGroup
        label-position="left"
        input-type="number"
        v-if="element.hasStyle(item.id)"
        :options="unitOptions"
        :label="item.name"
        :min="-1000"
        :disabled="getStyle(`${item.id}.unit`) == 'auto'"
        :has-changed="hasChanged(`${item.id}.value`) || hasChanged(`${item.id}.unit`)"
        @clear:hover="deleteHoverStyle(item.id)"
        @change:input="setStyle(`${item.id}.value`, $event)"
        @change:select="setStyle(`${item.id}.unit`, $event)"
        :input-value="String(getStyle(`${item.id}.value`))"
        :select-value="String(getStyle(`${item.id}.unit`))"
      />
    </template>
  </div>
</template>

<script setup lang="ts">
import BuilderInputGroup from "@modules/Builder/resources/components/form/builder-input-group.vue";
import BuilderNumberInput from "@modules/Builder/resources/components/form/builder-number-input.vue";
import BuilderSelect from "@modules/Builder/resources/components/form/builder-select.vue";
import { CSSPositions } from "@modules/Builder/resources/scripts/enums";
import { useElement } from "@modules/Builder/resources/scripts/use-element";
import VelnoxAIElement from "@modules/Builder/resources/scripts/VelnoxAI-element";

const { element } = defineProps<{ element: VelnoxAIElement }>();

const { getStyle, hasChanged, deleteHoverStyle, setStyle } = useElement(element);

const unitOptions = ["px", "%", "auto", "rem", "em"];

const positions = [
  CSSPositions.Relative,
  CSSPositions.Absolute,
  CSSPositions.Sticky,
  CSSPositions.Static,
  CSSPositions.Fixed
];

const positionEntries = [
  { id: "top", name: "Top" },
  { id: "right", name: "Right" },
  { id: "bottom", name: "Bottom" },
  { id: "left", name: "Left" },
];
</script>

<style scoped></style>
