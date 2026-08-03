<template>
    <UFormField
        :required="required"
        :error="error"
        :label="label">
        <UButtonGroup class="w-full">
            <USelect
                :items="options"
                v-model="model"
                :placeholder="placeholder"
                :label-key="labelKey"
                :value-key="valueKey"
                class="w-full" />

            <UTooltip
                v-if="!!createUrl"
                text="Create new">
                <UButton
                    color="neutral"
                    variant="outline"
                    icon="ph:plus"
                    @click.prevent="handleCreateFormClick" />
            </UTooltip>
        </UButtonGroup>
    </UFormField>
</template>

<script setup lang="ts">
import { visitModal } from '@inertiaui/modal-vue';
import { ArrayOrNested, SelectItem } from '@nuxt/ui';

interface Props {
    options: ArrayOrNested<SelectItem> | undefined;
    labelKey?: any;
    valueKey?: string;
    error?: string;
    label?: string;
    createUrl?: string;
    placeholder?: string;
    required?: boolean;
    onCreate?: () => null;
}

const props = withDefaults(defineProps<Props>(), {
    labelKey: 'name',
    valueKey: 'id',
    required: false,
    onCreate: () => null,
});
const model = defineModel();

function handleCreateFormClick() {
    if (!props.createUrl) return;
    visitModal(props.createUrl, {
        onClose: () => props.onCreate(),
    });
}
</script>

<style scoped></style>
