<template>
    <div class="space-y-4">
        <BuilderSelect
            label-position="left"
            :options="orientations"
            @change="element.setProps('orientation', $event)"
            :value="element.getProp('orientation')"
            label="Orientation"
        />

        <BuilderColorInput
            label-position="left"
            label="Text color"
            input-class="w-full"
            @change="element.setProps('trigger.color', $event)"
            :value="element.getProp('trigger.color')"
        />

        <BuilderColorInput
            label-position="left"
            label="Active text color"
            input-class="w-full"
            @change="element.setProps('trigger.activeColor', $event)"
            :value="element.getProp('trigger.activeColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Active background"
            input-class="w-full"
            @change="element.setProps('trigger.backgroundColor', $event)"
            :value="element.getProp('trigger.backgroundColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Indicator color"
            input-class="w-full"
            @change="element.setProps('indicator.backgroundColor', $event)"
            :value="element.getProp('indicator.backgroundColor')"
        />

        <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('trigger.fontWeight')"
            @change="element.setProps('trigger.fontWeight', $event)"
        />

        <BuilderSlider
            label="Font size"
            unit="px"
            :value="element.getProp('trigger.fontSize')"
            @change="element.setProps('trigger.fontSize', $event)"
        />

        <BuilderCombo
            is-font
            label-position="left"
            label="Font family"
            :options="FONTS"
            :value="element.getProp('trigger.fontFamily')"
            @change="element.setProps('trigger.fontFamily', $event)"
        />

        
    </div>
</template>

<script setup lang="ts">
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { FONTS, fontWeights } from "@modules/Builder/resources/scripts/constants";
import BuilderCombo from '@modules/Builder/resources/components/form/builder-combo.vue';
const { element } = defineProps<{ element: ZioraElement }>();

const orientations = ["vertical", "horizontal"];
// const variants = ["link", "pill"];
// const sizes = ["xs", "md", "sm", "lg", "xl"];
</script>

<style scoped></style>
