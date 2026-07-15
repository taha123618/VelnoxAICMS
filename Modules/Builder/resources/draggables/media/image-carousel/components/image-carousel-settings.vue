<template>
    <div class="space-y-4">
        <USwitch
            size="sm"
            @update:modelValue="element.setProps('arrows', $event)"
            :model-value="element.getProp('arrows')"
            label="Show arrows" />
            
        <USwitch
            size="sm"
            @update:modelValue="element.setProps('dots', $event)"
            :model-value="element.getProp('dots')"
            label="Show dots" />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('loop', $event)"
            :model-value="element.getProp('loop')"
            label="Loop items" />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('autoScroll', $event)"
            :model-value="element.getProp('autoScroll')"
            label="Autoscroll" />

        <USwitch
            size="sm"
            @update:modelValue="element.setProps('autoplay.active', $event)"
            :model-value="element.getProp('autoplay.active')"
            label="Autoplay" />

        <BuilderSlider
            v-if="element.getProp('autoplay.active')"
            :max="4000"
            :step="500"
            :min="1000"
            unit="ms"
            label="Autoplay delay"
            :value="element.getProp('autoplay.delay')"
            @change="element.setProps('autoplay.delay', $event as number)" />

        <BuilderSlider
            :max="10"
            :min="1"
            label="Items in view"
            :value="element.getProp('itemsInView')"
            @change="element.setProps('itemsInView', $event as number)" />

        <BuilderToggleGroup
            option-is-object
            label-position="top"
            :value="element.getProp('item.align')"
            @change="element.setProps('item.align', $event)"
            label="Align items"
            :options="flexAlign" />

        <BuilderSlider
            unit="px"
            label="Image border radius"
            :value="element.getProp('item.borderRadius')"
            @change="element.setProps('item.borderRadius', $event as number)" />

        <template
            v-for="item in dimensions"
            :key="item.id">
            <BuilderInputGroup
                label-position="left"
                select-class="w-auto"
                :show-icons="false"
                :max="10000"
                :disabled="element.getProp(`${item.id}.unit`) == 'auto'"
                :options="item.options"
                :label="item.name"
                @change:input="element.setProps(`${item.id}.value`, $event)"
                @change:select="element.setProps(`${item.id}.unit`, $event)"
                :input-value="element.getProp(`${item.id}.value`)"
                :select-value="element.getProp(`${item.id}.unit`)" />
        </template>
    </div>
</template>

<script setup lang="ts">
import BuilderInputGroup from '@modules/Builder/resources/components/form/builder-input-group.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import BuilderToggleGroup from '@modules/Builder/resources/components/form/builder-toggle-group.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const flexAlign = [
    { label: 'Start', value: 'flex-start', icon: 'ph:align-left' },
    { label: 'Center', value: 'center', icon: 'ph:align-center-horizontal' },
    { label: 'End', value: 'flex-end', icon: 'ph:align-right' },
];

// const orientations = [
//     { label: 'Horizontal', value: 'horizontal' },
//     { label: 'Vertical', value: 'vertical' },
// ];

const heightUnitOptions = ['auto', '%', 'px', 'rem', 'vh', 'em'];
const widthUnitOptions = ['auto', '%', 'px', 'rem', 'vw', 'em'];
const dimensions = [
    { id: 'item.height', name: 'Image height', options: heightUnitOptions },
    { id: 'item.width', name: 'Image Width', options: widthUnitOptions },
];
</script>

<style scoped></style>
