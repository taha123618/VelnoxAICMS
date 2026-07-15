<template>
    <div class="space-y-4">
         <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('heading.fontWeight')"
            @change="element.setProps('heading.fontWeight', $event)"
        />
        
        <BuilderSlider
            :min="6"
            unit="px"
            label="Name font size"
            :value="element.getProp('heading.fontSize')"
            @change="element.setProps('heading.fontSize', $event)" />

        <BuilderColorInput
            label="Name color"
            label-position="left"
            :value="element.getProp('heading.color')"
            @change="element.setProps('heading.color', $event)" />
    </div>
</template>

<script setup lang="ts">
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { fontWeights } from '@modules/Builder/resources/scripts/constants';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';


const { element } = defineProps<{
    element: ZioraElement;
}>();

</script>

<style scoped></style>
