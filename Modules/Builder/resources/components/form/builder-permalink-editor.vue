<template>
    <UFormField
        :label="label"
        class="text-xs"
        :error="error"
        :ui="{
            label: 'block font-normal VelnoxAI-label',
            container: labelPosition == 'left' ? 'col-span-2 mt-0' : 'relative'
        }"
        :class="[
            labelPosition == 'left'
                ? 'grid grid-cols-3 gap-1 items-center'
                : 'flex flex-col gap-1',
        ]"
    >
        <div class="text-neutral-400 flex items-center">
            {{ page.ziggy.url }}/
            <UInput
                :id="id"
                size="xs"
                error="error here"
                :placeholder="placeholder"
                variant="subtle"
                class="w-full"
                :disabled="disabled"
                @update:model-value="handleChange"
                :model-value="modelValue"
            />
        </div>
    </UFormField>
</template>

<script setup lang="ts">
import { TLabelPosition } from "@modules/Builder/resources/scripts/types";
import { kebabCase } from 'change-case-all'

import { getId } from "@/helpers";
import { SharedData } from "@/types";
interface Props {
    label?: string;
    error?: string;
    placeholder?: string;
    modelValue?: string | undefined;
    labelPosition?: TLabelPosition;
    disabled?: boolean;
}
const page = usePage<SharedData>().props

withDefaults(defineProps<Props>(), {
    labelPosition: "top",
    disabled: false,
});

const id = getId();

const emit = defineEmits(["change", "update:modelValue"]);


function handleChange(e: any) {
    emit('update:modelValue', kebabCase(e))
}

</script>

<style scoped></style>
