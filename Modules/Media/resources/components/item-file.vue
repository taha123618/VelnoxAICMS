<template>
    <ContextMenu :item="item" :isModalPage="isModalPage">
        <div ref="elRef" :class="{ 'opacity-25': isDragging }">
            <UButton as="div" @click.prevent="setSelectedMedia(item)" variant="ghost"
                class="relative flex size-18 cursor-pointer flex-col items-center justify-center gap-0 overflow-hidden bg-transparent p-1.5">
                <img :src="item.thumbnail" class="object-cover pointer-events-none" />
                <UIcon v-if="isSelected" name="ph:check-circle-fill" class="absolute top-0 right-0 m-2 size-6" />
            </UButton>
            <div :title="item.name" class="line-clamp-2 w-18 text-center text-xs">
                {{ item.name }}
            </div>
            <Teleport v-if="elState.type === 'preview'" :to="elState.container">
                <img :src="item.thumbnail" class="object-cover size-20" />
            </Teleport>
        </div>
    </ContextMenu>
</template>

<script setup lang="ts">
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import ContextMenu from '@modules/Media/resources/components/context-menu.vue';
import { useFileManager } from '@modules/Media/resources/scripts/use-filemanager';
import { useMedia } from '@modules/Media/resources/scripts/useMedia';
import { unrefElement } from '@vueuse/core';

const { item, isModalPage } = defineProps<{
    item: Record<string, any>,
    isModalPage: boolean
}>()

const { selectedMedia, setSelectedMedia } = useFileManager()
const isSelected = computed<boolean>(() => item.id == selectedMedia.value?.id)

const elRef = useTemplateRef<HTMLElement>('elRef');

const { dragCall, dropCall, isDragging, elState } = useMedia(item);


watchEffect((onCleanup) => {
    const currentElement = unrefElement(elRef);
    if (!currentElement) return;

    const dndFunction = combine(
        dragCall(currentElement),
        dropCall(currentElement, false),
    )

    onCleanup(() => {
        dndFunction();
    });
})


</script>

<style scoped></style>