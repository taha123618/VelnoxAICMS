<template>
    <UCollapsible trailing-icon="ph:caret-down" :ui="{
        root: 'bg-white dark:bg-neutral-900',
        content: 'w-full py-4 px-2 bg-white dark:bg-neutral-900'
    }" class="border border-neutral-200 dark:border-neutral-800 flex-col group justify-start items-start px-1 py-1 w-full" ref="elRef"
        :class="{ 'opacity-50': isDragging }">
        <div class="w-full flex items-center justify-between gap-2">
            <div class="flex items-center gap-1">
                <div ref="dragHandleRef">
                    <UButton class="cursor-move" size="sm" color="neutral" variant="ghost" icon="ph:list" />
                </div>
                <div>{{ item.label }}</div>
            </div>
            <div class="flex items-center gap-2">
                <UIcon name="ph:caret-down"
                    class="group-data-[state=open]:rotate-180 transition-transform duration-200" />
            </div>
        </div>

        <template #content>
            <div class="text-start space-y-2" @click.stop>
                <UBadge variant="soft">
                    <ULink target="_blank" :href="item.href">{{ item.href }}</ULink>
                </UBadge>

                <UFormField label="Label">
                    <UInput class="w-full" size="sm" v-model="item.label" />
                </UFormField>

                <UFormField label="Target">
                    <USelect required class="w-full" :items="customLinkTargetOptions" v-model="item.target" size="sm" />
                </UFormField>

                <div class="flex justify-end">
                    <UButton @click.prevent="emit('remove', item)" variant="link" size="sm" icon="ph:trash"
                        color="error">
                        Remove
                    </UButton>
                </div>
            </div>
        </template>

        <div v-if="instruction" class="absolute top-0 h-full w-full border-primary px-2 left-0" :class="{
            '!border-b-2': instruction?.type === 'reorder-below',
            '!border-t-2': instruction?.type === 'reorder-above',
            'rounded !border-2': instruction?.type === 'make-child',
        }" />
    </UCollapsible>
</template>


<script setup lang="ts">
import { type Instruction, attachInstruction, extractInstruction } from '@atlaskit/pragmatic-drag-and-drop-hitbox/tree-item';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { draggable, dropTargetForElements, monitorForElements } from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { pointerOutsideOfPreview } from '@atlaskit/pragmatic-drag-and-drop/element/pointer-outside-of-preview';
import { setCustomNativeDragPreview } from '@atlaskit/pragmatic-drag-and-drop/element/set-custom-native-drag-preview';
import { useTree } from '@modules/Builder/resources/scripts/use-tree';
import { unrefElement } from '@vueuse/core';
import { computed, h, nextTick, ref, render, watchEffect } from 'vue';

const { parentItem, item, level, expanded, index = 0 } = defineProps<{
    parentItem: Modules.Menu.Data.MenuItemData | null,
    item: Modules.Menu.Data.MenuItemData;
    level: number,
    index: number,
    expanded: boolean,
    selected: boolean
}>();

const elRef = ref();
const dragHandleRef = ref();
const expandedItems = defineModel<string[]>()
const isDragging = ref(false);
const isDraggedOver = ref(false);
const isInitialExpanded = ref(false);
const instruction = ref<Extract<Instruction, { type: 'reorder-above' | 'reorder-below' | 'make-child' }> | null>(null);
const { hasChildren, customLinkTargetOptions } = useTree()

const emit = defineEmits(['remove'])

const mode = computed(() => {
    if (hasChildren(item)) return 'expanded';
    if (index + 1 === parentItem?.children?.length) return 'last-in-group'; // fn to get parent
    return 'standard';
});

watchEffect((onCleanup) => {
    const currentElement = unrefElement(elRef);
    const currentHandleElement = unrefElement(dragHandleRef);

    if (!currentElement || !currentHandleElement) return;

    const menuItem = { ...item, parentId: parentItem?.id || null, level: level, id: item.id };

    const expandItem = () => {
        if (expandedItems.value != undefined) {
            expandedItems.value = [...expandedItems.value, String(item.id)];
        }
    };

    const closeItem = () => {
        if (expandedItems.value != undefined) {
            expandedItems.value = expandedItems.value!.filter(f => f !== String(item.id));
        }
    };

    const dndFunction = combine(
        draggable({
            element: currentElement,
            dragHandle: currentHandleElement,
            getInitialData: () => menuItem,
            onDragStart: () => {
                isDragging.value = true;
                isInitialExpanded.value = expanded;
                closeItem();
            },
            onDrop: () => {
                isDragging.value = false;
                if (isInitialExpanded.value) expandItem();
            },
            onGenerateDragPreview({ nativeSetDragImage }) {
                setCustomNativeDragPreview({
                    getOffset: pointerOutsideOfPreview({ x: '16px', y: '8px' }),
                    render: ({ container }) => {
                        return render(h('div', { class: 'bg-white dark:bg-neutral-900 text-black dark:text-white border border-neutral-200 dark:border-neutral-800 rounded-md text-sm font-medium px-3 py-1.5' }, menuItem.label), container);
                    },
                    nativeSetDragImage,
                });
            },
        }),

        dropTargetForElements({
            element: currentElement,
            getData: ({ input, element }) => {
                const data = { id: menuItem.id };

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
                const dropLevel = instruction.value?.currentLevel
                if (dropLevel != undefined && (Number(source.data.level) + dropLevel > 1) && instruction.value?.type == 'make-child') {
                    return false;
                }
                return source.data.id !== menuItem.id;
            },
            onDrag: ({ self }) => {
                instruction.value = extractInstruction(self.data) as typeof instruction.value;
            },
            onDragEnter: (args) => {
                const { source } = args
                if (source.data.id !== menuItem.id) {
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
                if (location.current.dropTargets[0].data.id === menuItem.id) {
                    nextTick(() => {
                        expandItem();
                    });
                }
            },
            getIsSticky: () => true,
        }),

        monitorForElements({
            canMonitor: ({ source }) => {
                return source.data.id !== menuItem.id;
            },
        }),
    );

    onCleanup(() => dndFunction());
});
</script>
