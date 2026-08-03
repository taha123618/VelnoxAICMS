<template>
    <div class="space-y-4">
        <BuilderColorInput
            label-position="left"
            label="Text color"
            input-class="w-full"
            @change="element.setProps('menuListLink.color', $event)"
            :value="element.getProp('menuListLink.color')"
        />

        <BuilderColorInput
            label-position="left"
            label="Hover color"
            input-class="w-full"
            @change="element.setProps('menuListLink.hoverColor', $event)"
            :value="element.getProp('menuListLink.hoverColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Hover bg"
            input-class="w-full"
            @change="element.setProps('menuListLink.hoverBackgroundColor', $event)"
            :value="element.getProp('menuListLink.hoverBackgroundColor')"
        />

        <BuilderSelect
            label="Text transform"
            :options="textTransforms"
            label-position="left"
            option-is-object
            :value="element.getProp('menuListLink.textTransform')"
            @change="element.setProps('menuListLink.textTransform', $event)"
        />

        <BuilderCombo
            is-font
            label-position="left"
            label="Font family"
            :options="FONTS"
            :value="element.getProp('menuListLink.fontFamily')"
            @change="element.setProps('menuListLink.fontFamily', $event)"
        />

        <BuilderSlider
            label="Font size (px)"
            unit="px"
            :value="Number(element.getProp('menuListLink.fontSize'))"
            @change="element.setProps('menuListLink.fontSize', $event)"
        />

        <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('menuListLink.fontWeight')"
            @change="element.setProps('menuListLink.fontWeight', $event)"
        />

        <BuilderSlider
            label="Padding X"
            unit="px"
            :value="element.getProp('menuListLink.paddingX')"
            @change="element.setProps('menuListLink.paddingX', $event)"
        />

        <BuilderSlider
            label="Padding Y"
            unit="px"
            :value="element.getProp('menuListLink.paddingY')"
            @change="element.setProps('menuListLink.paddingY', $event)"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderCombo from '@modules/Builder/resources/components/form/builder-combo.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import { textTransforms, fontWeights, FONTS } from '@modules/Builder/resources/scripts/constants';
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{ element: VelnoxAIElement }>();

</script>

<style scoped></style>
