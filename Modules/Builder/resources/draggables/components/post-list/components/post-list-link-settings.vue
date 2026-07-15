<template>
    <div class="space-y-4">
        <USwitch
            size="sm"
            @update:modelValue="element.setProps('link.show', $event)"
            :model-value="element.getProp('link.show')"
            label="Show link"
        />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('link.autoLayout', $event)"
            :model-value="element.getProp('link.autoLayout')"
            label="Auto layout"
        />

        <BuilderInput
            label="Text"
            label-position="left"
            :value="element.getProp('link.text')"
            @change="element.setProps('link.text', $event as string)"
        />

        <BuilderSlider
            :min="6"
            unit="px"
            label="Font size"
            :value="element.getProp('link.fontSize')"
            @change="element.setProps('link.fontSize', $event as number)"
        />

        <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('link.fontWeight')"
            @change="element.setProps('link.fontWeight', $event)"
        />

        <BuilderColorInput
            label="Color"
            label-position="left"
            :value="element.getProp('link.color')"
            @change="element.setProps('link.color', $event)"
        />

        <BuilderNumberInput
            label="Border radius"
            label-position="left"
            :value="element.getProp('link.borderRadius')"
            @change="element.setProps('link.borderRadius', $event)"
        />

        <BuilderColorInput
            label="Background color"
            label-position="left"
            :value="element.getProp('link.backgroundColor')"
            @change="element.setProps('link.backgroundColor', $event)"
        />

        <BuilderFourInputGroup
            :unit-options="paddingUnitOptions"
            @change="element.setProps('link.padding', $event)"
            :input-value="element.getProp('link.padding')"
            label="Padding"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderFourInputGroup from '@modules/Builder/resources/components/form/builder-four-input-group.vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderNumberInput from '@modules/Builder/resources/components/form/builder-number-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { fontWeights } from '@modules/Builder/resources/scripts/constants';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const paddingUnitOptions = ['px', 'rem', 'em'];
</script>

<style scoped></style>
