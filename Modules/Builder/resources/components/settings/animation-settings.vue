<template>
    <div class="space-y-2">
        <BuilderSelect
            label="Entrance animation"
            :options="ANIMATIONS"
            placeholder="--none--"
            @change="
                element.setCustomStyle(
                    'animation',
                    $event == 'none' ? '' : $event,
                )
                "
            :value="String(element.getCustomStyle('animation'))"
        />
        <template v-if="element.getCustomStyle('animation')">
            <BuilderSelect
                label-position="left"
                label="Animation repeat"
                :options="repeatOptions"
                placeholder="--none--"
                @change="
                    element.setCustomStyle(
                        'animationRepeat',
                        $event == 'none' ? '' : $event,
                    )
                    "
                :value="String(element.getCustomStyle('animationRepeat'))"
            />

            <BuilderSelect
                label-position="left"
                label="Animation delay"
                :options="delayOptions"
                placeholder="--none--"
                @change="
                    element.setCustomStyle(
                        'animationDelay',
                        $event == 'none' ? '' : $event,
                    )
                    "
                :value="String(element.getCustomStyle('animationDelay'))"
            />

            <BuilderSelect
                label-position="left"
                label="Animation speed"
                :options="speedOptions"
                placeholder="--normal--"
                @change="
                    element.setCustomStyle(
                        'animationSpeed',
                        $event == 'none' ? '' : $event,
                    )
                    "
                :value="String(element.getCustomStyle('animationSpeed'))"
            />
        </template>
    </div>
</template>

<script setup lang="ts">
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import { ANIMATIONS } from '@modules/Builder/resources/scripts/constants';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
    options: Record<string, any>;
}>();

const repeatOptions = [
    { value: 'none', label: '--none--' },
    { value: '1', label: 'Once' },
    { value: '2', label: '2 times' },
    { value: '3', label: '3 times' },
    { value: 'infinite', label: 'Infinite' },
];

const delayOptions = [
    { value: 'none', label: '--none--' },
    { value: '2s', label: '2 seconds' },
    { value: '3s', label: '3 seconds' },
    { value: '4s', label: '4 seconds' },
    { value: '5s', label: '5 seconds' },
];

const speedOptions = [
    { value: 'none', label: '--normal--' },
    { value: 'slow', label: 'Slow' },
    { value: 'slower', label: 'Slower' },
    { value: 'fast', label: 'Fast' },
    { value: 'faster', label: 'Faster' },
];
</script>

<style scoped></style>
