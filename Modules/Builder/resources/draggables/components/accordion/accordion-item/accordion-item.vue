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
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { unrefElement } from '@vueuse/core';
import { useDnD } from '@modules/Builder/resources/scripts/use-dnd';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';

import { getId } from '@/helpers';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const id = getId();

const { elRef, dropCall, isDraggedOver } = useDnD(element);

const store = useZiora()

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
