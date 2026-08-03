<template>
    <div
        :id="element.id"
        class="h-full w-full bg-transparent">
        <!-- prettier-ignore -->
        <BaseRecursiveElement
                v-for="child in element.children"
                :key="child.id"
                :element="child" />

        <div
            ref="elRef"
            v-if="store.enabled"
            :class="
                cn(
                    [
                        isDraggedOver
                            ? 'bg-primary/10 text-primary'
                            : ' text-gray-500 bg-gray-200',
                    ],
                    'group flex flex-col m-3 items-center justify-center gap-2 p-3',
                )
            ">
            <UButton
                icon="ph:plus"
                class="rounded-full"
                :color="isDraggedOver ? 'primary' : 'neutral'"
                :variant="isDraggedOver ? 'subtle' : 'soft'" />

            <p>Drop elements here</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import type VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useDnD } from '@modules/Builder/resources/scripts/use-dnd';
import { useVelnoxAI } from '@modules/Builder/resources/scripts/use-VelnoxAI';
import { unrefElement } from '@vueuse/core';
import { onMounted, onUnmounted } from 'vue';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const store = useVelnoxAI()
const { elRef, dropCall, isDraggedOver } = useDnD(element);

let cleanup = () => {};

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
