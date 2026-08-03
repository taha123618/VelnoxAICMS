<template>
    <div class="space-y-2">
        <BuilderColorInput
            label-position="left"
            label="Label color"
            input-class="w-full"
            @change="element.setProps('label.color', $event)"
            :value="element.getProp('label.color')"
        />

        <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('label.fontWeight')"
            @change="element.setProps('label.fontWeight', $event)"
        />

        <BuilderSlider
            label="Font size"
            unit="px"
            :value="element.getProp('label.fontSize')"
            @change="element.setProps('label.fontSize', $event)"
        />

    </div>
</template>

<script setup lang="ts">

import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { fontWeights } from '@modules/Builder/resources/scripts/constants';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';

const { element } = defineProps<{ element: VelnoxAIElement }>();

</script>

<style scoped></style>
