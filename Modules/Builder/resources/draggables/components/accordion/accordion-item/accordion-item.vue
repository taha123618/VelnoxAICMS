<template>
    <div
        :id="`accordion_item_box_${id}`"
        class="h-full w-full bg-transparent mb-4"
    >
        <!-- prettier-ignore -->
        <BaseRecursiveElement
            v-for="child in element.children"
            :key="child.id"
            :element="child"
        />

        <UAlert
            ref="elRef"
            v-if="store.enabled"
            :ui="{
                root: 'w-full m-4 max-w-xs mx-auto',
                wrapper: 'p-1 text-center',
                icon: 'text-red-400 mx-auto'
            }"
            :variant="isDraggedOver ? 'solid' : 'subtle'"
            color="primary"
        >
            <template #title>
                <div class="flex flex-row gap-2 items-center justify-center">
                    <UIcon
                        name="ph:layout"
                        class="size-4 text-primary-300"
                    />
                    <p>Drop elements here</p>
                </div>
            </template>
        </UAlert>
    </div>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { unrefElement } from '@vueuse/core';
import { useDnD } from '@modules/Builder/resources/scripts/use-dnd';
import { useVelnoxAI } from '@modules/Builder/resources/scripts/use-VelnoxAI';

import { getId } from '@/helpers';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const id = getId();

const { elRef, dropCall, isDraggedOver } = useDnD(element);

const store = useVelnoxAI()

let cleanup = () => { };

onMounted(() => {
    const currentElement = unrefElement(elRef);
    if (!currentElement) return;
    cleanup = dropCall(currentElement);
});

onUnmounted(() => {
    cleanup();
});
</script>

<style scoped></style>
