<template>
  <div class="space-y-2">
    <BuilderColorInput
      label-position="left"
      label="Text color"
      @change="setStyle('color', $event)"
      :has-changed="hasChanged('color')"
      @clear:hover="deleteHoverStyle('color')"
      :value="String(getStyle('color'))"
      v-if="element.hasStyle('color')"
    />

    <BuilderToggleGroup
      v-if="element.hasStyle('textAlign')"
      label-position="left"
      :value="getStyle('textAlign')"
      :has-changed="hasChanged('textAlign')"
      @clear:hover="deleteHoverStyle('textAlign')"
      @change="setStyle('textAlign', $event)"
      option-is-object
      label="Text align"
      :options="textAlign"
    />

    <UFormField
      :ui="{
        label: 'block font-normal VelnoxAI-label',
      }"
      label="Text shadow"
      class="grid grid-cols-2 items-center"
    >
      <UPopover
        arrow
        :ui="{
          content: 'dark p-6 border border-neutral-700 max-w-sm max-h-96 overflow-y-auto min-w-xs',
        }"
        v-if="element.hasStyle('textShadow')"
      >
        <UButton
          :ui="{
            leadingIcon: 'size-4',
          }"
          size="xs"
          icon="ph:note-pencil"
          color="neutral"
          variant="subtle"
        />
        <template #content>
          <div class="space-y-4">
            <BuilderColorInput
              label-position="left"
              label="Shadow color"
              :has-changed="hasChanged('textShadow.color')"
              @clear:hover="deleteHoverStyle('textShadow.color')"
              @change="setStyle('textShadow.color', $event)"
              :value="String(getStyle('textShadow.color'))"
              v-if="element.hasStyle('textShadow')"
            />

            <BuilderSlider
              v-for="item in textShadows"
              :key="item.value"
              :label="item.label"
              :max="20"
              :step="1"
              :value="Number(getStyle(`textShadow.${item.value}`))"
              :has-changed="hasChanged(`textShadow.${item.value}`)"
              @clear:hover="deleteHoverStyle(`textShadow.${item.value}`)"
              @change="setStyle(`textShadow.${item.value}`, $event)"
            />
          </div>
        </template>
      </UPopover>
    </UFormField>

    <UFormField
      label="Typography"
      :ui="{
        label: 'block font-normal VelnoxAI-label',
      }"
      class="grid grid-cols-2 items-center"
    >
      <UPopover
        arrow
        :ui="{
          content: 'dark p-6 border border-neutral-700 max-w-sm max-h-96 overflow-y-auto min-w-xs',
        }"
      >
        <UButton
          :ui="{
            leadingIcon: 'size-4',
          }"
          size="xs"
          icon="ph:note-pencil"
          color="neutral"
          variant="subtle"
        />
        <template #content>
          <div class="space-y-4">
            <BuilderCombo
              is-font
              label-position="left"
              label="Font family"
              :options="FONTS"
              :value="(getStyle('fontFamily') as string)"
              :has-changed="hasChanged('fontFamily')"
              @clear:hover="deleteHoverStyle('fontFamily')"
              @change="setStyle('fontFamily', $event)"
            />

            <div class="grid grid-cols-2 gap-2">
              <BuilderInputGroup
                label-position="top"
                v-if="element.hasStyle('fontSize')"
                :options="fontSizeUnits"
                label="Font size"
                :is-decimal="getStyle('fontSize.unit') !== 'px'"
                @change:input="setStyle('fontSize.value', $event)"
                @change:select="setStyle('fontSize.unit', $event)"
                :has-changed="hasChanged('fontSize.value') || hasChanged('fontSize.unit')"
                @clear:hover="deleteHoverStyle('fontSize')"
                :input-value="Number(getStyle('fontSize.value'))"
                :select-value="String(getStyle('fontSize.unit'))"
              />

              <BuilderInputGroup
                label-position="top"
                v-if="element.hasStyle('lineHeight')"
                :options="lineHeightUnits"
                label="Line height"
                :is-decimal="getStyle('lineHeight.unit') !== 'px'"
                :has-changed="hasChanged('lineHeight.unit') || hasChanged('lineHeight.value')"
                @clear:hover="deleteHoverStyle('lineHeight')"
                @change:input="setStyle('lineHeight.value', $event)"
                @change:select="setStyle('lineHeight.unit', $event)"
                :input-value="Number(getStyle('lineHeight.value'))"
                :select-value="String(getStyle('lineHeight.unit'))"
              />
            </div>

            <BuilderSlider
              label="Letter spacing"
              :max="100"
              :min="-10"
              :step="1"
              :value="Number(getStyle('letterSpacing'))"
              :has-changed="hasChanged('letterSpacing')"
              @clear:hover="deleteHoverStyle('letterSpacing')"
              @change="setStyle('letterSpacing', $event)"
            />

            <div class="grid grid-cols-2 gap-2">
              <BuilderSelect
                label="Font weight"
                :options="fontWeights"
                option-is-object
                :value="String(getStyle('fontWeight'))"
                :has-changed="hasChanged('fontWeight')"
                @clear:hover="deleteHoverStyle('fontWeight')"
                @change="setStyle('fontWeight', $event)"
              />

              <BuilderSelect
                label="Text transform"
                :options="textTransforms"
                option-is-object
                :value="String(getStyle('textTransform'))"
                :has-changed="hasChanged('textTransform')"
                @clear:hover="deleteHoverStyle('textTransform')"
                @change="setStyle('textTransform', $event)"
              />

              <BuilderSelect
                label="Font style"
                :options="fontStyles"
                option-is-object
                :value="String(getStyle('fontStyle'))"
                :has-changed="hasChanged('fontStyle')"
                @clear:hover="deleteHoverStyle('fontStyle')"
                @change="setStyle('fontStyle', $event)"
              />

              <BuilderSelect
                label="Font variant"
                :options="fontVariants"
                option-is-object
                :value="String(getStyle('fontVariant'))"
                :has-changed="hasChanged('fontVariant')"
                @clear:hover="deleteHoverStyle('fontVariant')"
                @change="setStyle('fontVariant', $event)"
              />

              <BuilderSelect
                label="Text decoration"
                :options="textDecorations"
                option-is-object
                :value="String(getStyle('textDecoration'))"
                :has-changed="hasChanged('textDecoration')"
                @clear:hover="deleteHoverStyle('textDecoration')"
                @change="setStyle('textDecoration', $event)"
              />

              <BuilderColorInput
                label-position="top"
                label="Text stroke color"
                :has-changed="hasChanged('webkitTextStrokeColor')"
                @clear:hover="deleteHoverStyle('webkitTextStrokeColor')"
                @change="setStyle('webkitTextStrokeColor', $event)"
                :value="String(getStyle('webkitTextStrokeColor'))"
                v-if="element.hasStyle('webkitTextStrokeColor')"
              />
            </div>

            <BuilderSlider
              label="Text stroke width"
              unit="px"
              :value="Number(getStyle('webkitTextStrokeWidth'))"
              :has-changed="hasChanged('webkitTextStrokeWidth')"
              @clear:hover="deleteHoverStyle('webkitTextStrokeWidth')"
              @change="setStyle('webkitTextStrokeWidth', $event)"
            />
          </div>
        </template>
      </UPopover>
    </UFormField>
  </div>
</template>

<script setup lang="ts">
import BuilderColorInput from "@modules/Builder/resources/components/form/builder-color-input.vue";
import BuilderCombo from "@modules/Builder/resources/components/form/builder-combo.vue";
import BuilderInputGroup from "@modules/Builder/resources/components/form/builder-input-group.vue";
import BuilderSelect from "@modules/Builder/resources/components/form/builder-select.vue";
import BuilderSlider from "@modules/Builder/resources/components/form/builder-slider.vue";
import BuilderToggleGroup from "@modules/Builder/resources/components/form/builder-toggle-group.vue";
import { useElement } from "@modules/Builder/resources/scripts/use-element";
import { FONTS, fontWeights, textTransforms } from "@modules/Builder/resources/scripts/constants";
import VelnoxAIElement from "@modules/Builder/resources/scripts/VelnoxAI-element";

const { element } = defineProps<{ element: VelnoxAIElement }>();

const { getStyle, deleteHoverStyle, hasChanged, setStyle } = useElement(element);

const fontSizeUnits = ["px", "rem", "em"];

const lineHeightUnits = ["px", "rem", "em"];

// const fontWeights = [
//   { value: "100", label: "Thin" },
//   { value: "200", label: "Extra-light" },
//   { value: "300", label: "Light" },
//   { value: "400", label: "Normal" },
//   { value: "500", label: "Medium" },
//   { value: "600", label: "Semi-bold" },
//   { value: "700", label: "Bold" },
//   { value: "800", label: "Extra-bold" },
//   { value: "900", label: "Thick" },
// ];

// const textTransforms = [
//   { value: "none", label: "None" },
//   { value: "capitalize", label: "Capitalize" },
//   { value: "lowercase", label: "Lowercase" },
//   { value: "uppercase", label: "Uppercase" },
// ];

const fontStyles = [
  { value: "normal", label: "Normal" },
  { value: "italic", label: "Italic" },
];

const textDecorations = [
  { value: "none", label: "None" },
  { value: "underline", label: "Underline" },
  { value: "overline", label: "Overline" },
  { value: "line-through", label: "Line-through" },
];

const fontVariants = [
  { value: "normal", label: "Normal" },
  { value: "small-caps", label: "Small caps" },
  { value: "all-small-caps", label: "All small caps" },
  { value: "petite-caps", label: "Petite caps" },
  { value: "all-petite-caps", label: "All petite caps" },
  { value: "unicase", label: "Unicase" },
  { value: "titling-caps", label: "Titling caps" },
];
const textAlign = [
  { label: "Left", value: "left", icon: "ph:text-align-left" },
  { label: "Center", value: "center", icon: "ph:text-align-center" },
  { label: "Right", value: "right", icon: "ph:text-align-right" },
  { label: "Justify", value: "justify", icon: "ph:text-align-justify" },
];

const textShadows = [
  { value: "blur", label: "Blur" },
  { value: "x", label: "Horizontal" },
  { value: "y", label: "Vertical" },
];
</script>

<style scoped></style>
