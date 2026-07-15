<template>
    <div class="space-y-4">
        <USwitch
            size="sm"
            @update:modelValue="element.setProps('excerpt.show', $event)"
            :model-value="element.getProp('excerpt.show')"
            label="Show excerpt"
        />

        <BuilderSlider
            :min="6"
            unit="px"
            label="Font size"
            :value="element.getProp('excerpt.fontSize')"
            @change="element.setProps('excerpt.fontSize', $event as number)"
        />

        <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('excerpt.fontWeight')"
            @change="element.setProps('excerpt.fontWeight', $event as number)"
        />

        <BuilderColorInput
            label="Color"
            label-position="left"
            :value="element.getProp('excerpt.color')"
            @change="element.setProps('excerpt.color', $event as string)"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { fontWeights } from '@modules/Builder/resources/scripts/constants';
const { element } = defineProps<{
    element: ZioraElement;
}>();
</script>

<style scoped></style>
