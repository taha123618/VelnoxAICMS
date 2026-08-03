<template>
    <div
        ref="elRef"
        :class="{
            'opacity-50': isDragging,
            'cursor-no-drop': isDraggedOver && !showDropIndicator,
        }"
        class="w-full"
    >
        <BaseContextMenu :element="element">
            <div
                :class="{ 'bg-primary-800': isSelected }"
                class="flex w-full items-center rounded justify-between gap-2 transition-all duration-200"
            >
                <div
                    class="flex items-center gap-1 text-neutral-400 truncate"
                    :class="{ '!text-white': isSelected }"
                >
                    <div>
                        <UIcon
                            size="sm"
                            color="neutral"
                            variant="ghost"
                            :name="element.icon"
                        />
                    </div>
                    <div class="text-xs truncate">{{ element.name }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <div>
                        <UIcon
                            v-if="element.hasChildren()"
                            :name="expanded ? 'ph:minus' : 'ph:plus'"
                            class="transition-transform duration-200 group-data-[state=open]:rotate-180"
                        />
                    </div>
                    <div>
                        <UButton
                            color="neutral"
                            @click.prevent.stop="
                                store.setSelectedElement(element)
                                "
                            icon="ph:gear"
                            variant="ghost"
                            size="xs"
                        />
                    </div>
                </div>
            </div>
        </BaseContextMenu>

        <div
            v-if="instruction"
            class="absolute top-0 left-0 h-full w-full px-2"
            :class="[showDropIndicator ? 'border-primary' : 'border-transparent', {
                '!border-b': instruction?.type === 'reorder-below',
                '!border-t': instruction?.type === 'reorder-above',
                'rounded !border': instruction?.type === 'make-child',
            }]"
        />
    </div>
</template>

<script setup lang="ts">
import BaseContextMenu from '@modules/Builder/resources/components/base-context-menu.vue';
import {
    type Instruction,
    attachInstruction,
    extractInstruction,
} from '@atlaskit/pragmatic-drag-and-drop-hitbox/tree-item';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import {
    draggable,
    dropTargetForElements,
    monitorForElements,
} from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { pointerOutsideOfPreview } from '@atlaskit/pragmatic-drag-and-drop/element/pointer-outside-of-preview';
import { setCustomNativeDragPreview } from '@atlaskit/pragmatic-drag-and-drop/element/set-custom-native-drag-preview';
import { unrefElement } from '@vueuse/core';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { useVelnoxAI } from '@modules/Builder/resources/scripts/use-VelnoxAI';
import { render } from 'vue';

const { element, expanded, parentItem, index, level } = defineProps<{
    element: VelnoxAIElement;
    expanded: boolean;
    selected: boolean;
    index: number;
    level: number;
    parentItem?: VelnoxAIElement;
}>();

const elRef = ref();
const store = useVelnoxAI();
const isSelected = computed(() => store.selectedElement?.id == element.id);
const expandedItems = defineModel<string[]>();
const isDragging = ref(false);
const isDraggedOver = ref(false);
const isInitialExpanded = ref(false);

const instruction = ref<Extract<
    Instruction,
    { type: 'reorder-above' | 'reorder-below' | 'make-child' }
> | null>(null);

const showDropIndicator = computed(() => {
    if (!instruction.value || !isDraggedOver.value) return false;

    if (instruction.value?.type == 'make-child' && !element.canDrop) {
        return false;
    }

    return true;
});
const mode = computed(() => {
    if (element.hasChildren()) return 'expanded';
    if (index + 1 === parentItem?.children?.length) return 'last-in-group'; // fn to get parent
    return 'standard';
});


watchEffect((onCleanup) => {
    const currentElement = unrefElement(elRef);
    if (!currentElement) return;

    const treeItem = {
        ...element,
        parentId: parentItem?.id || null,
        level: level,
        id: element.id,
        draggableType: 'TREE',
    };

    const expandItem = () => {
        if (expandedItems.value != undefined) {
            expandedItems.value = [...expandedItems.value, element.id];
        }
    };

    const closeItem = () => {
        if (expandedItems.value != undefined) {
            expandedItems.value = expandedItems.value!.filter(
                (f) => f !== element.id,
            );
        }
    };

    const dndFunction = combine(
        draggable({
            element: currentElement,
            getInitialData: () => treeItem,
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
                        return render(
                            h(
                                'div',
                                {
                                    class: 'bg-white text-black rounded-md text-sm font-medium px-3 py-1.5',
                                },
                                treeItem.name,
                            ),
                            container,
                        );
                    },
                    nativeSetDragImage,
                });
            },
        }),
        dropTargetForElements({
            element: currentElement,
            getData: ({ input, element }) => {
                const data = { id: treeItem.id };
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
                if (instruction.value && instruction.value.type == 'make-child' && !element.canDrop) {
                    return false;
                }
                return source.data.id !== treeItem.id;
            },
            onDrag: ({ self }) => {
                instruction.value = extractInstruction(
                    self.data,
                ) as typeof instruction.value;
            },
            onDragEnter: (args) => {
                const { source } = args;
                if (source.data.id !== treeItem.id) {
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
                if (location.current.dropTargets[0].data.id === treeItem.id) {
                    nextTick(() => {
                        expandItem();
                    });
                }
            },
            getIsSticky: () => true,
        }),

        monitorForElements({
            canMonitor: ({ source }) => {
                return source.data.id !== treeItem.id;
            },
        }),
    );

    onCleanup(() => dndFunction());
});
</script>

<style scoped></style>
