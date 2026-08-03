import { defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { CurrentState } from '@modules/Builder/resources/scripts/enums';
import { findParentFromId } from '@modules/Builder/resources/scripts/factory';
import { ElState } from '@modules/Builder/resources/scripts/types';
import { useVelnoxAI } from '@modules/Builder/resources/scripts/use-VelnoxAI';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { computed, ref } from 'vue';

const idleState: ElState = { type: 'idle' };

export const useElement = (element: VelnoxAIElement) => {
    const store = useVelnoxAI();
    const isHovering = ref<boolean>(false);
    const elState = ref<ElState>(idleState);

    const className = computed(() => `element_${element.id}`);

    const isSelected = computed<boolean>(
        () => store.selectedElement?.id == element.id,
    );

    const isEditable = computed<boolean>(
        () =>
            !element.isLayoutElement ||
            (element.isLayoutElement && store.builderType == 'layout'),
    );
    const hasContent = computed<boolean>(() => {
        if (Array.isArray(element.children)) {
            return element.children.length > 0;
        }
        return false;
    });

    const totalContent = computed<number>(() => {
        if (Array.isArray(element.children)) {
            return element.children.length;
        }
        return 0;
    });

    function getStyle(key: string): string | Record<string, any> {
        if (!element) return '';
        return element.getStyle(store.device, store.currentState, key);
    }

    function hasChanged(property: string) {
        if (store.currentState == CurrentState.Default) return false;
        if (!store.selectedElement) return false;
        return store.selectedElement.hasChanged(store.device, property);
    }

    function deleteHoverStyle(property: string) {
        if (!store.selectedElement) return;
        store.selectedElement.deleteHoverStyle(store.device, property);
    }

    function setStyle(property: string, value: any): void {
        if (!element) return;
        element.setStyle(store.device, store.currentState, property, value);
    }

    const customClassNames = computed(() =>
        element.getCustomStyle('classNames'),
    );

    const aosProperties = computed<Record<string, any>>(() => {
        const animations = element.props.styles.custom.aos || {};
        const base = deepCopy(defaultDivStyles.custom.aos);
        return {
            ...base,
            ...animations,
        };
    });

    const animationClass = computed<string | null>(() => {
        let animation = '';
        if (element.getCustomStyle('animation')) {
            animation += `animate__animated animate__${element.getCustomStyle('animation')}`;
        }

        if (element.getCustomStyle('animationRepeat')) {
            const value = element.getCustomStyle('animationRepeat');
            if (value == 'infinite') {
                animation += ` animate__infinite`;
            } else {
                animation += ` animate__repeat-${value}`;
            }
        }

        if (element.getCustomStyle('animationDelay')) {
            const value = element.getCustomStyle('animationDelay');
            animation += ` animate__delay-${value}`;
        }

        if (element.getCustomStyle('animationSpeed')) {
            const value = element.getCustomStyle('animationSpeed');
            animation += ` animate__${value}`;
        }

        return animation;
    });

    const parentEl = computed<VelnoxAIElement | null>(
        () =>
            findParentFromId(store.editorElements, element.id) as VelnoxAIElement,
    );

    const parentIsGrid = computed<boolean>(
        () => parentEl.value?.type == 'grid',
    );

    // const emptyGridCells = computed(() => {
    //     if (element.type != 'grid') {
    //         return null;
    //     }
    //     const cols = getStyle('gridTemplateColumns.value');
    //     const rows = getStyle('gridTemplateRows.value');
    //     return Number(cols) * Number(rows) - element.children.length;
    // });
    // const emptyGridCells = ref(0);
    // watch(
    //     () => element,
    //     (newElement) => {
    //         const cols = getStyle('gridTemplateColumns.value');
    //         const rows = getStyle('gridTemplateRows.value');
    //         const emtyCells =
    //             Number(cols) * Number(rows) - newElement.children.length;
    //         emptyGridCells.value = emtyCells;
    //     },
    //     { deep: true, immediate: true },
    // );

    return {
        // emptyGridCells,
        parentEl,
        parentIsGrid,
        animationClass,
        customClassNames,
        idleState,
        totalContent,
        isSelected,
        elState,
        hasContent,
        className,
        getStyle,
        setStyle,
        aosProperties,
        isHovering,
        deleteHoverStyle,
        hasChanged,
        isEditable,
    };
};
