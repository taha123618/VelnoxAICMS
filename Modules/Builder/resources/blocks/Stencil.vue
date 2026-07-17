<template>
    <button
        type="button"
        class="flex relative cursor-move flex-col overflow-hidden items-center gap-2 rounded-sm bg-neutral-800 p-1 text-base font-medium text-neutral-400 ring ring-neutral-700 transition-all ring-inset hover:shadow-[0px_0px_16px_4px] hover:shadow-primary-900/40 duration-500 hover:bg-neutral-800/75 focus:outline-hidden focus-visible:ring-1 focus-visible:ring-primary-800 disabled:cursor-not-allowed disabled:opacity-75 aria-disabled:cursor-not-allowed aria-disabled:opacity-75"
        :class="{ 'opacity-30': isDragging }"
    >

        <div
            class="absolute inset-0"
            ref="elRef"
        />
        <img
            :src="block.image"
            class="rounded-sm"
            loading="eager"
            decoding="sync"
        />
        <p class="text-xs">
            {{ block.label }}
        </p>
    </button>
</template>

<script setup lang="ts">

import { draggable } from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { BlockData } from '@modules/Builder/resources/blocks';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { block } = defineProps<{
    block: BlockData;
}>();

const store = useZiora()
const elRef = ref<HTMLElement | null>(null);

const isDragging = ref<boolean>(false);

let cleanupFn = () => { }

onMounted(() => {
    // await nextTick()
    // if ((!block || !block.data)) {
    //     return
    // }
    cleanupFn = draggable({
        element: elRef.value as HTMLElement,
        getInitialData() {
            const item = JSON.parse(block.data)
            ZioraElement.updateIsLayoutProperty(item, store.builderType == 'layout')

            return {
                item: ZioraElement.newFromObject(item),
                canDrop: false,
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
