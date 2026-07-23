<template>
    <div class="space-y-2">
        <BuilderSelect
            label-position="left"
            label="Display"
            :options="options"
            :has-changed="hasChanged('display')"
            @clear:hover="deleteHoverStyle('display')"
            @change="setStyle('display', $event)"
            :value="String(getStyle('display'))"
        />
        <BuilderToggleGroup
            v-if="element.hasStyle('visibility')"
            :has-changed="hasChanged('visibility')"
            @clear:hover="deleteHoverStyle('visibility')"
            label-position="left"
            :value="getStyle('visibility')"
            @change="setStyle('visibility', $event)"
            option-is-object
            label="Visibility"
            :options="visibilities"
        />

        <BuilderSlider
            label="Opacity"
            unit="%"
            :max="100"
            :step="1"
            :value="getStyle('opacity.value') as unknown as number || 100"
            :has-changed="hasChanged('opacity.value')"
            @clear:hover="deleteHoverStyle('opacity.value')"
            @change="setStyle('opacity.value', $event)"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import BuilderToggleGroup from '@modules/Builder/resources/components/form/builder-toggle-group.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import { useElement } from '@modules/Builder/resources/scripts/use-element';


const { element } = defineProps<{ element: ZioraElement, options?: Record<string, any> }>();

const { getStyle,hasChanged, deleteHoverStyle, setStyle } = useElement(element);

const visibilities = [
    { label: 'Visible', value: 'visible', icon: 'ph:eye' },
    { label: 'Hidden', value: 'hidden', icon: 'ph:eye-slash' },
];
</script>

<style scoped></style>
