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
import { ElState } from '@modules/Builder/resources/scripts/types';

const idleState: ElState = { type: 'idle' };

export const useMedia = (item: any) => {
    const elState = ref<ElState>(idleState);
    const isDraggedOver = ref(false);
    const isDragging = ref(false);

    function dragCall(currentEl: HTMLElement, canDrag: boolean = true) {
        return draggable({
            element: currentEl,
            canDrag: () => canDrag,
            getInitialData() {
                return {
                    item,
                    action: 'move',
                };
            },
            onGenerateDragPreview({ nativeSetDragImage }) {
                setCustomNativeDragPreview({
                    nativeSetDragImage,
                    getOffset: pointerOutsideOfPreview({
                        x: '8px',
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
                elState.value = { type: 'is-dragging' };
                isDragging.value = true
            },
            onDrop: () => {
                elState.value = idleState;
                isDragging.value = false
            },
        });
    }

    function dropCall(currentEl: HTMLElement, canDrop: boolean = true) {
        return dropTargetForElements({
            element: currentEl,
            canDrop({ source }) {
                if (source.element === currentEl) {
                    return false;
                }
                return canDrop;
            },

            getData: (args) => {
                const { input } = args;
                const data = {
                    destinationId: item.id
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
                const targetElement = location.current.dropTargets[0]!.element;
                if (targetElement === currentEl) {
                    const closestEdge = extractClosestEdge(self.data);
                    isDraggedOver.value = true;
                    elState.value = { ...idleState, closestEdge };
                } else {
                    elState.value = idleState;
                    isDraggedOver.value = false;
                }
            },
        });
    }

    return {
        dragCall,
        dropCall,
        elState,
        isDragging,
        isDraggedOver,
    };
};
