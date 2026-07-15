<template>
    <div class="space-y-4">
        <USwitch
            size="sm"
            @update:modelValue="element.setProps('category.show', $event)"
            :model-value="element.getProp('category.show')"
            label="Show category name"
        />
        
        <USwitch
            size="sm"
            @update:modelValue="element.setProps('title.show', $event)"
            :model-value="element.getProp('title.show')"
            label="Show title"
        />

        <BuilderSlider
            :min="6"
            unit="px"
            label="Font size"
            :value="element.getProp('title.fontSize')"
            @change="element.setProps('title.fontSize', $event as number)"
        />

        <BuilderSelect
            label="Font weight"
            :options="fontWeights"
            option-is-object
            label-position="left"
            :value="element.getProp('title.fontWeight')"
            @change="element.setProps('title.fontWeight', $event as number)"
        />

        <BuilderColorInput
            label="Color"
            label-position="left"
            :value="element.getProp('title.color')"
            @change="element.setProps('title.color', $event as string)"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import { fontWeights } from '@modules/Builder/resources/scripts/constants';

const { element } = defineProps<{
    element: ZioraElement;
}>();
</script>

<style scoped></style>
