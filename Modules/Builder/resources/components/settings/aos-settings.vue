<template>
    <div class="space-y-4">
        <BuilderSelect
            label="AOS animation"
            :options="AOS_ANIMATIONS"
            placeholder="--none--"
            @change="
                element.setCustomStyle(
                    'aos.animation',
                    $event == 'none' ? '' : $event,
                )
                "
            :value="element.getCustomStyle('aos.animation')"
        />

        <template v-if="element.getCustomStyle('aos.animation')">
            <BuilderSelect
                label-position="left"
                label="Easing"
                :options="easingFunctions"
                placeholder="--none--"
                @change="
                    element.setCustomStyle(
                        'aos.easing',
                        $event == 'none' ? '' : $event,
                    )
                    "
                :value="element.getCustomStyle('aos.easing') || 'ease'"
            />

            <BuilderSelect
                label-position="left"
                label="Placement"
                :options="anchorPlacementOptions"
                placeholder="--none--"
                @change="
                    element.setCustomStyle(
                        'aos.anchorPlacement',
                        $event == 'none' ? '' : $event,
                    )
                    "
                :value="element.getCustomStyle('aos.anchorPlacement') ||
                    'top-bottom'
                    "
            />

            <BuilderSlider
                label="Duration"
                unit="ms"
                :min="100"
                :step="50"
                :max="3000"
                :value="element.getCustomStyle('aos.duration') || 2000"
                @change="element.setCustomStyle('aos.duration', $event)"
            />

            <BuilderSlider
                label="Delay"
                :min="100"
                :step="50"
                :max="3000"
                :value="element.getCustomStyle('aos.delay') || 0"
                @change="element.setCustomStyle('aos.delay', $event)"
            />

            <BuilderSlider
                label="Offset"
                unit="px"
                :min="50"
                :max="1000"
                :value="element.getCustomStyle('aos.offset') || 120"
                @change="element.setCustomStyle('aos.offset', $event)"
            />
        </template>
    </div>
</template>

<script setup lang="ts">
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import { AOS_ANIMATIONS } from '@modules/Builder/resources/scripts/constants';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
const { element } = defineProps<{
    element: ZioraElement;
}>();

const anchorPlacementOptions = [
    { value: 'top-bottom', label: 'Top-bottom' },
    { value: 'top-center', label: 'Top-center' },
    { value: 'top-top', label: 'Top-top' },
    { value: 'center-bottom', label: 'Center-bottom' },
    { value: 'center-center', label: 'Center-center' },
    { value: 'center-top', label: 'Center-top' },
    { value: 'bottom-bottom', label: 'Bottom-bottom' },
    { value: 'bottom-center', label: 'Bottom-center' },
    { value: 'bottom-top', label: 'Bottom-top' },
];

const easingFunctions = [
    { value: 'linear', label: 'Linear' },
    { value: 'ease', label: 'Ease' },
    { value: 'ease-in', label: 'Ease-in' },
    { value: 'ease-out', label: 'Ease-out' },
    { value: 'ease-in-out', label: 'Ease-in-out' },
    { value: 'ease-in-back', label: 'Ease-in-back' },
    { value: 'ease-out-back', label: 'Ease-out-back' },
    { value: 'ease-in-out-back', label: 'Ease-in-out-back' },
    { value: 'ease-in-sine', label: 'Ease-in-sine' },
    { value: 'ease-out-sine', label: 'Ease-out-sine' },
    { value: 'ease-in-out-sine', label: 'Ease-in-out-sine' },
    { value: 'ease-in-quad', label: 'Ease-in-quad' },
    { value: 'ease-out-quad', label: 'Ease-out-quad' },
    { value: 'ease-in-out-quad', label: 'Ease-in-out-quad' },
    { value: 'ease-in-cubic', label: 'Ease-in-cubic' },
    { value: 'ease-out-cubic', label: 'Ease-out-cubic' },
    { value: 'ease-in-out-cubic', label: 'Ease-in-out-cubic' },
    { value: 'ease-in-quart', label: 'Ease-in-quart' },
    { value: 'ease-out-quart', label: 'Ease-out-quart' },
    { value: 'ease-in-out-quart', label: 'Ease-in-out-quart' },
];
</script>

<style scoped></style>
