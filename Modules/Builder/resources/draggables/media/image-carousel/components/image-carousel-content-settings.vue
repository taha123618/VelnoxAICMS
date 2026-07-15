<template>
    <div class="space-y-2">
        <div class="grid gap-2">
            <!-- prettier-ignore -->
            <div v-for="(item, index) in (element.getProp('items'))" :key="`item-${index}`" class="flex">
                <UInput :model-value="item" />
                <BaseTooltip content="Remove">
                    <UButton :disabled="index == 0" @click="element.getProp('items').splice(index, 1)" icon="ph:x"
                        color="error" variant="ghost" />
                </BaseTooltip>
            </div>
        </div>

        <div class="flex items-center justify-center py-2">
            <UButton icon="ph:plus-circle" label="Add image" variant="soft" size="sm" @click.prevent="openFileDialog" />
        </div>
    </div>
</template>

<script setup lang="ts">
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';
import { visitModal } from '@inertiaui/modal-vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();


function openFileDialog() {
    visitModal(route('admin.folders.index', { mode: 'multiselect' }), {
        listeners: {
            selected(event: any) {
                element.setProps('items', [...element.getProp('items'), event])
            }
        }
    })
}

</script>

<style scoped></style>
