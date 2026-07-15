import { CurrentState, DeviceType, InsertLocation } from '@modules/Builder/resources/scripts/enums';
import { removeElement, insertElementAfter, insertElementBefore, addElementToParent, findElement } from '@modules/Builder/resources/scripts/factory';
import { TBuilderType, TCutOrCopyAction, TEditor, TElement, TTabName } from '@modules/Builder/resources/scripts/types';
import { deepCopy, parseElements } from '@modules/Builder/resources/scripts/utils';
import BodyElement from '@modules/Builder/resources/draggables/static/body/config';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

const initialEditor: TEditor = {
    selectedElement: null,
    cutOrCopiedElement: null,
    cutOrCopyAction: null,
    device: DeviceType.Desktop,
    state: CurrentState.Default,
    isEnabled: true,
    showOutline: true,
    isPreviewing: false,
};

export const useZiora = defineStore('zioraStore', () => {
    const layoutElements = ref<ZioraElement[]>([]);
    const selectedTab = ref<TTabName>('components');
    const builderType = ref<TBuilderType>('page');
    const editorElements = ref<ZioraElement[]>([
        new ZioraElement(BodyElement),
    ]);
    const editor = reactive<TEditor>(initialEditor);
    const history = useRefHistory(editorElements, {
        deep: true,
        dump: deepCopy,
        parse: parseElements,
        capacity: 20,
    });
    const currentState = computed<CurrentState>(() => editor.state);
    const device = computed<DeviceType>(() => editor.device);
    const enabled = computed<boolean>(() => editor.isEnabled);
    const isDesktop = computed<boolean>(
        () => editor.device == DeviceType.Desktop,
    );
    const isTablet = computed<boolean>(
        () => editor.device == DeviceType.Tablet,
    );
    const isMobile = computed<boolean>(
        () => editor.device == DeviceType.Mobile,
    );
    const selectedElement = computed<ZioraElement | null>(
        () => editor.selectedElement,
    );

    const cutOrCopiedElement = computed<ZioraElement | null>(
        () => editor.cutOrCopiedElement,
    );

    const cutOrCopyAction = computed<TCutOrCopyAction>(
        () => editor.cutOrCopyAction,
    );
    const showOutline = computed<boolean>(() => editor.showOutline);
    const elements = computed<ZioraElement[]>(() => editorElements.value);

    const renderableElements = computed<ZioraElement[]>(() => {
        return injectElementsToContentArea(
            layoutElements.value,
            editorElements.value,
        );
    });

    function setInitialElements(
        elements: ZioraElement[],
        type: TBuilderType,
    ) {
        editorElements.value = elements;
        builderType.value = type;
        history.clear();
    }

    function setElements(elements: ZioraElement[]) {
        editorElements.value = elements;
    }

    function toggleEnable() {
        editor.isEnabled = !editor.isEnabled;
    }

    function disableEditor() {
        editor.isEnabled = false;
    }

    function setLayoutElements(elements: ZioraElement[]) {
        layoutElements.value = elements;
    }

    function changeDevice(device: DeviceType) {
        editor.device = device;
    }

    function changeCurrentState(_currentState: CurrentState) {
        editor.state = _currentState;
    }

    function toggleOuline() {
        editor.showOutline = !editor.showOutline;
    }

    function cutOrCopyElement(
        element: ZioraElement,
        action: TCutOrCopyAction,
    ) {
        editor.cutOrCopiedElement = element;
        editor.cutOrCopyAction = action;
    }

    function setSelectedElement(elementDetails: ZioraElement) {
        if (!elementDetails) return;
        clearSelectedElement();
        editor.selectedElement = elementDetails;
        selectedTab.value = 'styling';
    }

    function clearSelectedElement() {
        editor.selectedElement = null;
    }

    function deleteElement(elementId: string) {
        const results = removeElement(editorElements.value, elementId);
        editorElements.value = [
            ZioraElement.fromObject(results[0] as TElement),
        ];
        clearSelectedElement();
    }

    function addNewElement(
        targetParentId: string,
        newItem: Record<string, any>,
        insertAt: InsertLocation | null,
    ) {
        if (!targetParentId || !newItem) {
            return;
        }
        
        const elementsArray = deepCopy(editorElements.value);

        if (insertAt == InsertLocation.After) {
            const result = insertElementAfter(
                elementsArray,
                targetParentId,
                newItem,
            );
            editorElements.value = [
                ZioraElement.fromObject(result[0] as TElement),
            ];
        } else if (insertAt == InsertLocation.Before) {
            const result = insertElementBefore(
                elementsArray,
                targetParentId,
                newItem,
            );
            editorElements.value = [
                ZioraElement.fromObject(result[0] as TElement),
            ];
        } else {
            const result = addElementToParent(
                elementsArray,
                targetParentId,
                newItem,
            );

            editorElements.value = [
                ZioraElement.fromObject(result[0] as TElement),
            ];
        }
    }

    function moveElement(
        targetElementId: string,
        targetParentId: string,
        insertAt: InsertLocation | null,
    ) {
        if (targetElementId == targetParentId) {
            return;
        }
        const item = findElement(editorElements.value, targetElementId);

        if (!item) {
            return;
        }

        if (insertAt == InsertLocation.After) {
            let result = removeElement(editorElements.value, targetElementId);
            result = insertElementAfter(result, targetParentId, item);
            editorElements.value = [
                ZioraElement.fromObject(result[0] as TElement),
            ];
        } else if (insertAt == InsertLocation.Before) {
            let result = removeElement(editorElements.value, targetElementId);
            result = insertElementBefore(result, targetParentId, item);
            editorElements.value = [
                ZioraElement.fromObject(result[0] as TElement),
            ];
        } else {
            // make child
            let result = removeElement(editorElements.value, targetElementId);
            result = addElementToParent(result, targetParentId, item);
            editorElements.value = [
                ZioraElement.fromObject(result[0] as TElement),
            ];
        }
    }

    function duplicateElement(element: ZioraElement) {
        const elementsArray = deepCopy(editorElements.value);
        const newElement = ZioraElement.newFromObject({ ...element });
        const result = insertElementAfter(
            elementsArray,
            element.id,
            newElement,
        );
        editorElements.value = [
            ZioraElement.fromObject(result[0] as TElement),
        ];
    }

    function pasteElement(targetParent: ZioraElement) {
        if (
            !targetParent ||
            !editor.cutOrCopiedElement ||
            !editor.cutOrCopyAction
        ) {
            return;
        }

        if (targetParent.id == editor.cutOrCopiedElement.id) {
            return;
        }

        if (!Array.isArray(targetParent.children) || !targetParent.canDrop) {
            return;
        }

        const newElement = ZioraElement.newFromObject({
            ...editor.cutOrCopiedElement,
        });

        let result;

        if (editor.cutOrCopyAction == 'cut') {
            result = removeElement(
                deepCopy(editorElements.value),
                editor.cutOrCopiedElement.id,
            );
            console.log('cut');
        } else {
            result = deepCopy(editorElements.value);
        }

        result = addElementToParent(result, targetParent.id, newElement);

        editorElements.value = [
            ZioraElement.fromObject(result[0] as TElement),
        ];

        editor.cutOrCopiedElement = null;
        editor.cutOrCopyAction = null;
    }

    function injectElementsToContentArea(
        elementsArray: ZioraElement[],
        injectableElements: ZioraElement[],
    ): ZioraElement[] {
        for (const item of elementsArray) {
            if (item.type === 'content') {
                item.appendContentElements(injectableElements);
            }

            if (Array.isArray(item.children)) {
                injectElementsToContentArea(item.children, injectableElements);
            }
        }

        return elementsArray;
    }

    return {
        device,
        history,
        enabled,
        elements,
        isTablet,
        isMobile,
        isDesktop,
        showOutline,
        changeDevice,
        moveElement,
        setElements,
        selectedTab,
        builderType,
        toggleOuline,
        pasteElement,
        currentState,
        toggleEnable,
        addNewElement,
        deleteElement,
        disableEditor,
        layoutElements,
        editorElements,
        cutOrCopyAction,
        selectedElement,
        cutOrCopyElement,
        duplicateElement,
        setInitialElements,
        setSelectedElement,
        renderableElements,
        setLayoutElements,
        changeCurrentState,
        cutOrCopiedElement,
        clearSelectedElement,
    };
});
