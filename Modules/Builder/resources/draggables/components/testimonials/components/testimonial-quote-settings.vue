<template>
    <div class="space-y-4">
        <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('body.fontWeight')"
            @change="element.setProps('body.fontWeight', $event)"
        />
      
        <BuilderSlider
            :min="6"
            unit="px"
            label="Quote font size"
            :value="element.getProp('body.fontSize')"
            @change="element.setProps('body.fontSize', $event)" />

        <BuilderColorInput
            label="Quote color"
            label-position="left"
            :value="element.getProp('body.color')"
            @change="element.setProps('body.color', $event)" />
    </div>
</template>

<script setup lang="ts">
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { fontWeights } from '@modules/Builder/resources/scripts/constants';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

</script>

<style scoped></style>
