<template>
    <div class="space-y-2">
        <BuilderSelect
            :disabled="store.currentState != CurrentState.Default"
            label="Timing"
            label-position="left"
            :options="transitionTimingOptions"
            placeholder="--none--"
            @change="setStyle('transitionTimingFunction', $event)"
            :value="String(getStyle('transitionTimingFunction'))" />

        <BuilderSlider
            label="Transition duration"
            :max="4000"
            :min="100"
            :step="100"
            unit="ms"
            :disabled="store.currentState != CurrentState.Default"
            :has-changed="hasChanged(`transitionDuration.value`)"
            @clear:hover="deleteHoverStyle(`transitionDuration.value`)"
            :value="Number(getStyle(`transitionDuration.value`) || 100)"
            @change="
                setStyle(`transitionDuration`, {
                    unit: getStyle(`transitionDuration.unit`) || 'ms',
                    value: $event,
                })
            " />
    </div>
</template>

<script setup lang="ts">
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { useVelnoxAI } from '@modules/Builder/resources/scripts/use-VelnoxAI';
import { CurrentState } from '@modules/Builder/resources/scripts/enums';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const { getStyle, deleteHoverStyle, hasChanged, setStyle } =
    useElement(element);

const store = useVelnoxAI();
const transitionTimingOptions = [
    { value: 'linear', label: 'Linear' },
    { value: 'ease-in', label: 'Ease-in' },
    { value: 'ease-out', label: 'Ease-out' },
    { value: 'ease-in-out', label: 'Ease-in-out' },
    { value: 'step-start', label: 'Step-start' },
    { value: 'step-end', label: 'Step-end' },
];
</script>

<style scoped></style>
