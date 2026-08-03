<template>
    <div class="space-y-4">

        <USwitch 
            label="Show dropdown arrow"
            :model-value="element.getProp('dropdown.showArrow')"
            @update:model-value="element.setProps('dropdown.showArrow', $event)"
        />

        <BuilderColorInput
            label-position="left"
            label="Text color"
            input-class="w-full"
            @change="element.setProps('dropdown.color', $event)"
            :value="element.getProp('dropdown.color')"
        />

        <BuilderColorInput
            label-position="left"
            label="Hover text color"
            input-class="w-full"
            @change="element.setProps('dropdown.hoverColor', $event)"
            :value="element.getProp('dropdown.hoverColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Background"
            input-class="w-full"
            @change="element.setProps('dropdown.backgroundColor', $event)"
            :value="element.getProp('dropdown.backgroundColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Hover background"
            input-class="w-full"
            @change="element.setProps('dropdown.hoverBackgroundColor', $event)"
            :value="element.getProp('dropdown.hoverBackgroundColor')"
        />

        <!-- <BuilderSlider
            label="Width"
            unit="px"
            :max="500"
            :value="element.getProp('dropdown.width')"
            @change="element.setProps('dropdown.width', $event)"
        /> -->

        <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('dropdown.fontWeight')"
            @change="element.setProps('dropdown.fontWeight', $event)"
        />
        <BuilderSelect
            label="Text transform"
            :options="textTransforms"
            label-position="left"
            option-is-object
            :value="element.getProp('dropdown.textTransform')"
            @change="element.setProps('dropdown.textTransform', $event)"
        />

        <BuilderSlider
            label="Font size (px)"
            unit="px"
            :value="element.getProp('dropdown.fontSize')"
            @change="element.setProps('dropdown.fontSize', $event)"
        />

        <BuilderCombo
            is-font
            label-position="left"
            label="Font family"
            :options="FONTS"
            :value="element.getProp('dropdown.fontFamily')"
            @change="element.setProps('dropdown.fontFamily', $event)"
        />

        <BuilderSelect
            label-position="left"
            :options="borderStyles"
            @change="element.setProps('dropdown.borderStyle', $event)"
            :value="element.getProp('dropdown.borderStyle')"
            label="Border style"
        />

        <BuilderSlider
            label="Border width"
            unit="px"
            :value="Number(element.getProp('dropdown.borderWidth'))"
            @change="element.setProps('dropdown.borderWidth', $event)"
        />

        <BuilderColorInput
            label-position="left"
            label="Border color"
            input-class="w-full"
            @change="element.setProps('dropdown.borderColor', $event)"
            :value="element.getProp('dropdown.borderColor')"
        />

        <BuilderSlider
            label="Border radius"
            unit="px"
            :value="Number(element.getProp('dropdown.borderRadius'))"
            @change="element.setProps('dropdown.borderRadius', $event)"
        />

        <!-- <USwitch
            size="sm"
            @update:modelValue="element.setProps('dropdown.showArrow', $event)"
            :model-value="element.getProp('dropdown.showArrow')"
            label="Show dropdown arrow"
        />

        <template v-if="element.getProp('dropdown.showArrow')">
            <BuilderColorInput
                label-position="left"
                label="Arrow fill color"
                input-class="w-full"
                @change="element.setProps('dropdown.arrowFillColor', $event)"
                :value="element.getProp('dropdown.arrowFillColor')"
            />
        </template> -->

        <!-- <BuilderColorInput
            label-position="left"
            label="Background color"
            input-class="w-full"
            @change="element.setProps('dropdown.backgroundColor', $event)"
            :value="element.getProp('dropdown.backgroundColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Dropdown link"
            input-class="w-full"
            @change="element.setProps('dropdown.color', $event)"
            :value="element.getProp('dropdown.color')"
        /> -->
    </div>
</template>

<script setup lang="ts">
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { FONTS, textTransforms, fontWeights } from "@modules/Builder/resources/scripts/constants";
import BuilderCombo from '@modules/Builder/resources/components/form/builder-combo.vue';
const { element } = defineProps<{ element: VelnoxAIElement }>();

const borderStyles = ["none", "solid", "dashed", "dotted", "groove", "inset"];
</script>

<style scoped></style>
