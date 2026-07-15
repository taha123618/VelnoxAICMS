<template>
    <ContextMenu :isModalPage="isModalPage" :item="item">
        <div ref="elRef" :class="{ 'opacity-25': elState.type == 'is-dragging' }">
            <UButton :variant="isDraggedOver ? 'subtle' : isSelected ? 'soft' : 'ghost'" @click="emit('clicked', item)"
                @dblclick.prevent="emit('doubleClicked', item)"
                class="relative flex size-18 cursor-pointer flex-col items-center justify-center gap-0 overflow-hidden p-1.5">
                <UIcon name="ph:folder-fill" class="size-14 text-orange-300 rounded-md" />
                <UIcon v-if="isSelected" name="ph:check-circle-fill" class="absolute top-0 right-0 m-2 size-4" />
            </UButton>

            <div :title="item.name" class="line-clamp-2 w-18 text-center text-xs">
                {{ item.name }}
            </div>
            <Teleport v-if="elState.type === 'preview'" :to="elState.container">
                <UIcon name="ph:folder-fill" class="size-14 text-orange-300" />
                <p>{{ item.name }}</p>
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
    isModalPage: boolean,
    item: Record<string, any>
}>()

const { selectedMedia } = useFileManager()

const isSelected = computed<boolean>(() => item.id == selectedMedia.value?.id)


const emit = defineEmits(['doubleClicked', 'clicked'])

const elRef = useTemplateRef<HTMLElement>('elRef');

const { dragCall, dropCall, elState, isDraggedOver } = useMedia(item);


watchEffect((onCleanup) => {
    const currentElement = unrefElement(elRef);
    if (!currentElement) return;

    const dndFunction = combine(
        dragCall(currentElement),
        dropCall(currentElement),
    )

    onCleanup(() => {
        dndFunction();
    });
})
</script>

<style scoped></style>