<template>
    <div
        ref="elRef"
        class="w-full"
    >
        <div class="flex gap-1.5 items-center justify-between w-full">
            <UButton
                size="md"
                variant="link"
                color="neutral"
                @click.stop="isModalPage ? modalRef?.reload({ data: { folder: item.id } }) : router.reload({ data: { folder: item.id } })"
                class="w-full p-0 flex justify-between"
            >
                <span class="flex items-center gap-2">
                    <UIcon
                        class="text-orange-400 size-5"
                        :name="expanded ? 'ph:folder-open-fill' : 'ph:folder-fill'"
                    />
                    {{ item.name }}
                    <UBadge
                        class="rounded-full"
                        v-if="item.contentCount > 0"
                        variant="soft"
                        size="xs"
                        :label="item.contentCount"
                    />
                </span>
            </UButton>

            <UButton
                variant="ghost"
                size="xs"
                v-if="item.children.length > 0"
                :icon="expanded ? 'ph:caret-up' : 'ph:caret-down'"
            />
        </div>

        <div
            v-if="instruction"
            class="absolute top-0 h-full w-full border-primary px-2 left-0"
            :class="{
                '!border-b-2': instruction?.type === 'reorder-below',
                '!border-t-2': instruction?.type === 'reorder-above',
                'rounded !border-2': instruction?.type === 'make-child',
            }"
        />
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { type Instruction, attachInstruction, extractInstruction } from '@atlaskit/pragmatic-drag-and-drop-hitbox/tree-item';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { draggable, dropTargetForElements, monitorForElements } from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { pointerOutsideOfPreview } from '@atlaskit/pragmatic-drag-and-drop/element/pointer-outside-of-preview';
import { setCustomNativeDragPreview } from '@atlaskit/pragmatic-drag-and-drop/element/set-custom-native-drag-preview';
import { useTree } from '@modules/Builder/resources/scripts/use-tree';
import { unrefElement } from '@vueuse/core';
import { computed, h, nextTick, ref, render, watchEffect } from 'vue';
import { useFileManager } from '@modules/Media/resources/scripts/use-filemanager';

const { item, level, expanded, isModalPage } = defineProps<{
    item: Record<string, any>;
    level: number,
    index: number,
    expanded: boolean,
    selected: boolean,
    isModalPage: boolean,
}>();

const {modalRef} = useFileManager()

const elRef = ref();
const expandedItems = defineModel<string[]>()
const isDragging = ref(false);
const isDraggedOver = ref(false);

const isInitialExpanded = ref(false);
const instruction = ref<Extract<Instruction, { type: 'reorder-above' | 'reorder-below' | 'make-child' }> | null>(null);

const { hasChildren } = useTree()

const mode = computed(() => {
    if (hasChildren(item)) return 'expanded';
    return 'standard';
});


watchEffect((onCleanup) => {

    const currentElement = unrefElement(elRef);
    if (!currentElement) return;

    const folderItem = {
        ...item,
        name: item.name,
        parentId: item.parent_id,
        level: level,
        id: item.id
    };

    const expandItem = () => {
        if (expandedItems.value != undefined) {
            expandedItems.value = [...expandedItems.value, item.id]
        }
    };

    const closeItem = () => {
        if (expandedItems.value != undefined) {
            expandedItems.value = expandedItems.value!.filter(f => f !== item.id)
        }
    };

    const dndFunction = combine(
        draggable({
            element: currentElement,
            getInitialData: () => folderItem,
            onDragStart: () => {
                isDragging.value = true;
                isInitialExpanded.value = expanded;
                closeItem();
            },
            onDrop: () => {
                isDragging.value = false;
                if (isInitialExpanded.value) {
                    expandItem()
                };
            },
            onGenerateDragPreview({ nativeSetDragImage }) {
                setCustomNativeDragPreview({
                    getOffset: pointerOutsideOfPreview({ x: '16px', y: '8px' }),
                    render: ({ container }) => {
                        return render(h('div', { class: 'bg-white dark:bg-neutral-900 text-black dark:text-white border border-neutral-200 dark:border-neutral-800 rounded-md text-sm font-medium px-3 py-1.5' }, folderItem.name), container);
                    },
                    nativeSetDragImage,
                });
            },
        }),

        dropTargetForElements({
            element: currentElement,
            getData: ({ input, element }) => {
                const data = { id: folderItem.id };
                return attachInstruction(data, {
                    input,
                    element,
                    indentPerLevel: 16,
                    currentLevel: level,
                    mode: mode.value,
                    block: [],
                });
            },
            canDrop: ({ source }) => {
                return source.data.id !== folderItem.id;
            },
            onDrag: ({ self }) => {
                instruction.value = extractInstruction(self.data) as typeof instruction.value;
            },
            onDragEnter: (args) => {
                const { source } = args
                if (source.data.id !== folderItem.id) {
                    isDraggedOver.value = true;
                    expandItem();
                }
            },
            onDragLeave: () => {
                isDraggedOver.value = false;
                instruction.value = null;
            },
            onDrop: ({ location }) => {
                isDraggedOver.value = false;
                instruction.value = null;
                if (location.current.dropTargets[0].data.id === folderItem.id) {
                    nextTick(() => {
                        expandItem();
                    });
                }
            },
            getIsSticky: () => true,
        }),

        monitorForElements({
            canMonitor: ({ source }) => {
                return source.data.id !== folderItem.id && source.data.type == 'FolderTree';
            },
        }),
    )


    onCleanup(() => dndFunction());
})
</script>

<style scoped></style>