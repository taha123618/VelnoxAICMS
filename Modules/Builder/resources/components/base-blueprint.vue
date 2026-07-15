<template>
    <button
        ref="elRef"
        type="button"
        class="flex cursor-move flex-col items-center gap-2 rounded-md bg-neutral-800 px-3 py-2 text-base font-medium text-neutral-400 ring ring-neutral-700 transition-all ring-inset hover:shadow-[0px_0px_16px_4px] hover:shadow-primary-900/40 duration-500 hover:bg-neutral-800/75 focus:outline-hidden focus-visible:ring-1 focus-visible:ring-primary-800 disabled:cursor-not-allowed disabled:opacity-75 aria-disabled:cursor-not-allowed aria-disabled:opacity-75"
        :class="{ 'opacity-30': isDragging }"
    >
        <UIcon
            :name="element.icon"
            class="size-6"
        />
        <p class="text-xs">
            {{ element.name }}
        </p>
    </button>
</template>

<script setup lang="ts">

import { getId } from '@/helpers';
import { draggable } from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { TElement } from '@modules/Builder/resources/scripts/types';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: TElement;
}>();

const store = useZiora();

const elRef = ref<HTMLElement | null>(null);

const isDragging = ref<boolean>(false);

let cleanupFn = () => {}

onMounted(() => {
    cleanupFn = draggable({
        element: elRef.value as HTMLElement,
        getInitialData() {
            const el = new ZioraElement({
                ...element,
                id: getId(),
                isLayoutElement: store.builderType == 'layout',
            })

            return {
                item: el,
                canDrop: element.canDrop,
                action: 'add',
            };
        },
        onDragStart: () => (isDragging.value = true),
        onDrop: () => (isDragging.value = false),
    })
})

onBeforeUnmount(() => {
    cleanupFn()
})
</script>

<style scoped></style>
