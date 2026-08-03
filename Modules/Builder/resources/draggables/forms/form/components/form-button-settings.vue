<template>
    <div class="space-y-2">
        <BuilderSelect
            label="Button align"
            :options="buttonAlignments"
            option-is-object
            label-position="left"
            :value="element.getProp('button.align')"
            @change="element.setProps('button.align', $event)"
        />

        <BuilderInput
            label-position="left"
            :value="element.getProp('button.label')"
            @change="element.setProps('button.label', $event)"
            label="Label"
        />

        <BuilderSlider
            label="Border radius"
            unit="px"
            :value="element.getProp('button.borderRadius')"
            @change="element.setProps('button.borderRadius', $event)"
        />

        <BuilderSlider
            label="Padding X"
            unit="px"
            :value="element.getProp('button.paddingX')"
            @change="element.setProps('button.paddingX', $event)"
        />

        <BuilderSlider
            label="Padding Y"
            unit="px"
            :value="element.getProp('button.paddingY')"
            @change="element.setProps('button.paddingY', $event)"
        />

        <BuilderSlider
            label="Font size"
            unit="px"
            :value="element.getProp('button.fontSize')"
            @change="element.setProps('button.fontSize', $event)"
        />

        <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('button.fontWeight')"
            @change="element.setProps('button.fontWeight', $event)"
        />

        <BuilderColorInput
            label-position="left"
            label="Text color"
            input-class="w-full"
            @change="element.setProps('button.color', $event)"
            :value="element.getProp('button.color')"
        />

        <BuilderColorInput
            label-position="left"
            label="Hover color"
            input-class="w-full"
            @change="element.setProps('button.hoverColor', $event)"
            :value="element.getProp('button.hoverColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Background"
            input-class="w-full"
            @change="element.setProps('button.backgroundColor', $event)"
            :value="element.getProp('button.backgroundColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Hover background"
            input-class="w-full"
            @change="element.setProps('button.hoverBackgroundColor', $event)"
            :value="element.getProp('button.hoverBackgroundColor')"
        />

        <BuilderSlider
            label="Border width"
            unit="px"
            :value="element.getProp('button.borderWidth')"
            @change="element.setProps('button.borderWidth', $event)"
        />

        <BuilderColorInput
            label-position="left"
            label="Border"
            input-class="w-full"
            @change="element.setProps('button.borderColor', $event)"
            :value="element.getProp('button.borderColor')"
        />

        <BuilderColorInput
            label-position="left"
            label="Hover border"
            input-class="w-full"
            @change="element.setProps('button.hoverBorderColor', $event)"
            :value="element.getProp('button.hoverBorderColor')"
        />

    </div>
</template>

<script setup lang="ts">
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { fontWeights } from '@modules/Builder/resources/scripts/constants';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';

const { element } = defineProps<{ element: VelnoxAIElement }>();


const buttonAlignments = [
    {label: 'Left', value: 'left'},
    {label: 'Center', value: 'center'},
    {label: 'Right', value: 'right'},
    {label: 'Full width', value: 'full'},
]
</script>

<style scoped></style>
