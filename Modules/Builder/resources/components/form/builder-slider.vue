<template>
    <div class="space-y-1">
        <UFormField
            :label="label"
            :ui="{
                label: 'block font-normal VelnoxAI-label',
            }"
            class="text-xs">
            <template #label>
                <div class="flex items-center gap-0.5">
                    <div>{{ label }}</div>
                    <BaseTooltip
                        v-if="hasChanged"
                        content="Clear hover style">
                        <UButton
                            @click="emit('clear:hover')"
                            size="sm"
                            color="error"
                            variant="link"
                            icon="ph:x" />
                    </BaseTooltip>
                </div>
            </template>
            <template #hint>
                <UBadge
                    class="mb-2"
                    variant="outline"
                    size="sm">
                    {{ value }}{{ unit }}
                </UBadge>
            </template>
            <div class="px-0.5">
                <USlider
                    class="dark"
                    :disabled="disabled"
                    :model-value="value"
                    @update:modelValue="
                        $event != undefined ? emit('change', $event) : null
                    "
                    :max="max"
                    :min="min"
                    :step="step"
                    size="xs" />
            </div>
        </UFormField>
    </div>
</template>

<script setup lang="ts">
import BaseTooltip from "@modules/Builder/resources/components/base-tooltip.vue";

interface Props {
    value: number;
    min?: number;
    max?: number;
    step?: number;
    disabled?: boolean;
    hasChanged?: boolean;
    label?: string;
    unit?: string;
}

withDefaults(defineProps<Props>(), {
    min: 0,
    max: 100,
    step: 1,
    disabled: false,
    hasChanged: false,
});

const emit = defineEmits(['change', 'clear:hover']);
</script>

<style scoped></style>
