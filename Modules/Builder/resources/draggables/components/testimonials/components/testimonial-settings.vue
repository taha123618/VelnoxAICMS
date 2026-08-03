<template>
    <div class="space-y-4">
        <BuilderSelect
            label="Variant"
            :options="variantOptions"
            label-position="left"
            :value="element.getProp('variant')"
            @change="element.setProps('variant', $event)" />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('autoplay', $event)"
            :model-value="element.getProp('autoplay')"
            label="Autoplay" />

        <BuilderSlider
            v-if="element.getProp('autoplay')"
            :max="6000"
            :step="500"
            :min="1000"
            unit="ms"
            label="Autoplay duration"
            :value="element.getProp('duration')"
            @change="element.setProps('duration', $event)" />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('arrows', $event)"
            :model-value="element.getProp('arrows')"
            label="Show arrows" />

        <!-- <USwitch
            size="sm"
            @update:modelValue="element.setProps('loop', $event)"
            :model-value="element.getProp('loop')"
            label="Loop" /> -->

        <!-- <USwitch
            size="sm"
            v-if="element.getProp('variant') == 'horizontal'"
            @update:modelValue="element.setProps('dots', $event)"
            :model-value="element.getProp('dots')"
            label="Show dots" /> -->
    </div>
</template>

<script setup lang="ts">
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const variantOptions = ['vertical', 'horizontal'];
</script>

<style scoped></style>
