<template>
  <div class="space-y-4">
    <VisibilitySettings
      :options="['flex', 'none']"
      :element="element"
    />
    <BuilderToggleGroup
      v-if="element.hasStyle('flexDirection')"
      label-position="top"
      :value="getStyle('flexDirection')"
      @change="setStyle('flexDirection', $event)"
      :has-changed="hasChanged('flexDirection')"
      @clear:hover="deleteHoverStyle('flexDirection')"
      option-is-object
      label="Flex direction"
      :options="flexDirections"
    />

    <BuilderToggleGroup
      v-if="element.hasStyle('justifyContent')"
      label-position="top"
      :value="getStyle('justifyContent')"
      @change="setStyle('justifyContent', $event)"
      :has-changed="hasChanged('justifyContent')"
      @clear:hover="deleteHoverStyle('justifyContent')"
      option-is-object
      label="Justify content"
      :options="flexJustify"
    />

    <BuilderToggleGroup
      v-if="element.hasStyle('alignItems')"
      label-position="top"
      :value="getStyle('alignItems')"
      :has-changed="hasChanged('alignItems')"
      @clear:hover="deleteHoverStyle('alignItems')"
      @change="setStyle('alignItems', $event)"
      option-is-object
      label="Align items"
      :options="flexAlign"
    />

    <div
      class="grid grid-cols-2 gap-x-2"
      v-if="element.hasStyle('columnGap') || element.hasStyle('rowGap')"
    >
      <BuilderNumberInput
        v-if="element.hasStyle('columnGap')"
        label="Col gaps"
        :has-changed="hasChanged('columnGap')"
        @clear:hover="deleteHoverStyle('columnGap')"
        :value="Number(getStyle('columnGap'))"
        @change="setStyle('columnGap', $event)"
      />

      <BuilderNumberInput
        v-if="element.hasStyle('rowGap')"
        label="Row gaps"
        :value="Number(getStyle('rowGap'))"
        :has-changed="hasChanged('rowGap')"
        @clear:hover="deleteHoverStyle('rowGap')"
        @change="setStyle('rowGap', $event)"
      />
    </div>

    <BuilderToggleGroup
      v-if="element.hasStyle('flexWrap')"
      label-position="left"
      :value="getStyle('flexWrap')"
      :has-changed="hasChanged('flexWrap')"
      @clear:hover="deleteHoverStyle('flexWrap')"
      @change="setStyle('flexWrap', $event)"
      option-is-object
      label="Flex wrap"
      :options="flexWrap"
    />

    <template v-if="parentIsGrid">
      <GridChildSettings :element="element" />
    </template>
  </div>
</template>

<script setup lang="ts">
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import VisibilitySettings from '@modules/Builder/resources/components/settings/visibility-settings.vue';
import BuilderNumberInput from '@modules/Builder/resources/components/form/builder-number-input.vue';
import BuilderToggleGroup from '@modules/Builder/resources/components/form/builder-toggle-group.vue';
import GridChildSettings from '@modules/Builder/resources/components/settings/grid-child-settings.vue';

const { element } = defineProps<{ element: VelnoxAIElement }>();

const { parentIsGrid, hasChanged, deleteHoverStyle, getStyle, setStyle } = useElement(element);

const flexWrap = [
  { label: "No wrap", value: "nowrap", icon: "ph:arrow-line-right" },
  { label: "Wrap", value: "wrap", icon: "ph:arrow-u-down-left" },
];

const flexDirections = [
  { label: "Row", value: "row", icon: "ph:arrow-right" },
  { label: "Row reverse", value: "row-reverse", icon: "ph:arrow-left" },
  { label: "Column", value: "column", icon: "ph:arrow-down" },
  { label: "Column reverse", value: "column-reverse", icon: "ph:arrow-up" },
];

const flexAlign = [
  { label: "Start", value: "flex-start", icon: "ph:align-left" },
  { label: "Center", value: "center", icon: "ph:align-center-horizontal" },
  { label: "End", value: "flex-end", icon: "ph:align-right" },
  {
    label: "Stretch",
    value: "stretch",
    icon: "material-symbols-light:align-justify-stretch",
  },
  {
    label: "Baseline",
    value: "baseline",
    icon: "ic:baseline-format-align-justify",
  },
];

const flexJustify = [
  { label: "Start", value: "flex-start", icon: "ph:align-left" },
  { label: "Center", value: "center", icon: "ph:align-center-horizontal" },
  { label: "End", value: "flex-end", icon: "ph:align-right" },
  {
    label: "Space between",
    value: "space-between",
    icon: "lucide:align-horizontal-space-between",
  },
  {
    label: "Space around",
    value: "space-around",
    icon: "lucide:align-horizontal-space-around",
  },
  {
    label: "Space evenly",
    value: "space-evenly",
    icon: "fluent:align-space-evenly-horizontal-20-regular",
  },
];
</script>

<style scoped></style>
