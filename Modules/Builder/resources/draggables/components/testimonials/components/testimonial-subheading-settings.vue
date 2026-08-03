<template>
    <div class="space-y-4">
        <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('subHeading.fontWeight')"
            @change="element.setProps('subHeading.fontWeight', $event)"
        />
        
        <BuilderSlider
            :min="6"
            unit="px"
            label="Name font size"
            :value="element.getProp('subHeading.fontSize')"
            @change="element.setProps('subHeading.fontSize', $event)" />

        <BuilderColorInput
            label="Name color"
            label-position="left"
            :value="element.getProp('subHeading.color')"
            @change="element.setProps('subHeading.color', $event)" />
    </div>
</template>

<script setup lang="ts">
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import { fontWeights } from '@modules/Builder/resources/scripts/constants';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

</script>

<style scoped></style>
