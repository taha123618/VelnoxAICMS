<template>
    <UFormField :label="label" :error="error" class="text-xs" :ui="{
        label: 'block font-normal VelnoxAI-label',
        container: labelPosition == 'left' ? 'col-span-2 mt-0' : 'relative',
    }" :class="[
            labelPosition == 'left'
                ? 'grid grid-cols-3 gap-1 items-center'
                : 'flex flex-col gap-1',
        ]">
        <UInputTags class="min-w-full" :placeholder="placeholder" :model-value="value"
            @update:model-value="emit('change', $event)" />
    </UFormField>
</template>

<script setup lang="ts">
import { TLabelPosition } from '@modules/Builder/resources/scripts/types';

interface Props {
    value: string[];
    inputClass?: string;
    error?: string;
    label?: string;
    placeholder?: string;
    labelPosition?: TLabelPosition;
}

withDefaults(defineProps<Props>(), {
    labelPosition: 'top',
});

const emit = defineEmits(['change']);
</script>

<style scoped></style>
