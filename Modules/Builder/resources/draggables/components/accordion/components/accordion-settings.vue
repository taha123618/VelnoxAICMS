<template>
    <div class="space-y-2">
        <div class="grid gap-2">
            <!-- prettier-ignore -->
            <div
                v-for="(child, index) in element.children"
                :key="child.id"
                class="flex"
            >
                <UInput
                    @update:model-value="child.setName($event as string)"
                    :model-value="child.name"
                />
                <BaseTooltip content="Remove">
                    <UButton
                        :disabled="index == 0"
                        @click="element.removeChild(index)"
                        icon="ph:x"
                        color="error"
                        variant="ghost"
                    />
                </BaseTooltip>
            </div>
        </div>

        <div class="flex items-center justify-center py-2">
            <UButton
                variant="soft"
                icon="ph:plus"
                label="Add item"
                size="sm"
                @click.stop="addAccordionItem"
                class="cursor-pointer"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import accordionItemElement from '@modules/Builder/resources/draggables/components/accordion/accordion-item/config';

import { getId } from '@/helpers';

const { element } = defineProps<{
    element: ZioraElement;
}>();

function addAccordionItem() {
    element.addChild(
        new ZioraElement({
            ...accordionItemElement,
            id: getId(),
            name: 'New item',
        }),
    );
}
</script>

<style scoped></style>
