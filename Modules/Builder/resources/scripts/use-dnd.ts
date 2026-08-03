import {
    attachClosestEdge,
    extractClosestEdge,
} from '@atlaskit/pragmatic-drag-and-drop-hitbox/closest-edge';
import {
    draggable,
    dropTargetForElements,
} from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { pointerOutsideOfPreview } from '@atlaskit/pragmatic-drag-and-drop/element/pointer-outside-of-preview';
import { setCustomNativeDragPreview } from '@atlaskit/pragmatic-drag-and-drop/element/set-custom-native-drag-preview';
import { autoUpdate, flip, shift, useFloating } from '@floating-ui/vue';
import { ElState } from '@modules/Builder/resources/scripts/types';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { cursorIsonEdge } from '@modules/Builder/resources/scripts/utils';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { computed, ref, useTemplateRef } from 'vue';

const idleState: ElState = { type: 'idle' };

export const useDnD = (element: VelnoxAIElement) => {
    const isDraggedOver = ref<boolean>(false);
    const isDragging = ref<boolean>(false);
    const isDraggingOnEdge = ref<boolean>(false);
    const elState = ref<ElState>(idleState);
    const elRef = useTemplateRef<HTMLElement>('elRef');
    const { isEditable } = useElement(element);

    const floatingRef = ref(null);

    function getRect() {
        return elRef.value ? elRef.value.getBoundingClientRect() : null;
    }

    const { floatingStyles, placement } = useFloating(elRef, floatingRef, {
        placement: 'top-start',
        middleware: [
            flip({
                mainAxis: true,
                crossAxis: false,
            }),
            shift({
                mainAxis: true,
                crossAxis: true,
            }),
        ],
        whileElementsMounted: autoUpdate,
    });

    const showIndicator = computed<boolean>(() => {
        return elState.value.type === 'idle' && !!elState.value.closestEdge;
    });

    function dragCall(currentEl: HTMLElement) {
        if (!isEditable.value) {
            return () => void 0;
        }

        return draggable({
            element: currentEl,
            getInitialData() {
                return {
                    item: element,
                    action: 'move',
                };
            },
            onGenerateDragPreview({ nativeSetDragImage }) {
                setCustomNativeDragPreview({
                    nativeSetDragImage,
                    getOffset: pointerOutsideOfPreview({
                        x: '16px',
                        y: '8px',
                    }),
                    render({ container }) {
                        elState.value = {
                            type: 'preview',
                            container: container,
                        };
                    },
                });
            },
            onDragStart: () => {
                isDragging.value = true;
                elState.value = { type: 'is-dragging' };
            },

            onDrop: () => {
                isDragging.value = false;
                elState.value = idleState;
            },
        });
    }

    function dropCall(currentEl: HTMLElement, isDraggable: boolean = true) {
        return dropTargetForElements({
            element: currentEl,
            /* prevent element from being dropped on itself or on a child droppable
                https://github.com/atlassian/pragmatic-drag-and-drop/issues/113
                /*canDrop: ({ source, element }) => {
                   // assumes drag handle is inside droptarget
                   const sourceEl = source.element.closest('[data-drop-target-for-element="true"]')
                   if (sourceEl?.contains(element)) return false
                   return true
                }*/
            canDrop({ source }) {
                if (
                    source.element === currentEl ||
                    (!isEditable.value && element.type !== 'body')
                ) {
                    return false;
                }
                return true;
            },
            getData: (args) => {
                const { input, source } = args;
                const data = {
                    itemId: (source.data.item as VelnoxAIElement).id,
                    parentId: element.id,
                    canDrop: element.canDrop,
                    showingIndicator: showIndicator.value,
                };
                return attachClosestEdge(data, {
                    element: currentEl,
                    input,
                    allowedEdges: ['top', 'right', 'left', 'bottom'],
                });
            },
            getIsSticky() {
                return false;
            },

            onDragEnter({ self }) {
                const closestEdge = extractClosestEdge(self.data);
                elState.value = { type: 'is-dragging-over', closestEdge };
                isDraggedOver.value = true;
            },

            onDragLeave() {
                isDraggedOver.value = false;
                elState.value = idleState;
            },

            onDrop() {
                isDraggedOver.value = false;
                elState.value = idleState;
            },

            onDrag({ location, self }) {
                if (!isDraggable) return;
                const targetElement = location.current.dropTargets[0]!.element;
                // cursor position
                // get element and check if the mouse is within x pixels of the element
                const cursorPosition = location.current.input;
                isDraggingOnEdge.value = cursorIsonEdge(
                    cursorPosition,
                    targetElement as HTMLElement,
                );

                // helps in nested drag and drop to not show indicator on parents
                if (targetElement === currentEl) {
                    isDraggedOver.value = true;
                } else {
                    isDraggedOver.value = false;
                }

                if (targetElement === currentEl && !isDraggingOnEdge.value) {
                    const closestEdge = extractClosestEdge(self.data);
                    if (
                        elState.value.type !== 'is-dragging-over' ||
                        elState.value.closestEdge !== closestEdge
                    ) {
                        elState.value = {
                            type: 'is-dragging-over',
                            closestEdge,
                        };
                    }
                } else if (targetElement === currentEl) {
                    const closestEdge = extractClosestEdge(self.data);
                    elState.value = { ...idleState, closestEdge };
                } else {
                    elState.value = idleState;
                }
            },
        });
    }

    return {
        showIndicator,
        idleState,
        isDraggingOnEdge,
        floatingRef,
        elRef,
        placement,
        floatingStyles,
        isDraggedOver,
        isDragging,
        elState,
        dragCall,
        dropCall,
        getRect,
    };
};
