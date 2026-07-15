<template>
    <div class="space-y-4">
        <BuilderSelect
            label="Border style"
            :options="borderStyles"
            label-position="left"
            :value="element.getProp('item.borderStyle')"
            @change="element.setProps('item.borderStyle', $event)" />

        <BuilderColorInput
            label="Border color"
            label-position="left"
            :value="element.getProp('item.borderColor')"
            @change="element.setProps('item.borderColor', $event as string)" />

        <BuilderNumberInput
            label="Border width"
            label-position="left"
            :value="element.getProp('item.borderWidth')"
            @change="element.setProps('item.borderWidth', $event)" />

        <BuilderNumberInput
            label="Border radius"
            label-position="left"
            :value="element.getProp('item.borderRadius')"
            @change="element.setProps('item.borderRadius', $event)" />

        <BuilderColorInput
            label="Background color"
            label-position="left"
            :value="element.getProp('item.backgroundColor')"
            @change="
                element.setProps('item.backgroundColor', $event as string)
            " />

        <BuilderSlider
            :min="0"
            unit="px"
            label="Metabox padding"
            :value="element.getProp('metabox.padding')"
            @change="element.setProps('metabox.padding', $event as number)" />
    </div>
</template>

<script setup lang="ts">
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import BuilderNumberInput from '@modules/Builder/resources/components/form/builder-number-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const borderStyles = ['none', 'solid', 'dashed', 'dotted', 'groove', 'inset'];
</script>

<style scoped></style>
