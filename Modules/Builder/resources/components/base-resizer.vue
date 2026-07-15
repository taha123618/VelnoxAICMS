<template>
    <!-- <div
        v-if="showResizer"
        data-testid="top-height-resizer"
        @mousedown="
            ($event) => {
                resizingType = 'height';
                heightDirection = 'top';
                handleMouseDown($event);
            }
        "
        class="height-sizer button-edit hover:background-green-400 absolute z-10 h-[6px] w-[15px] cursor-ns-resize rounded-lg border border-green-400 bg-white"
        :style="{
            left: 'calc(50% - 6px)',
            top: '-2px',
            zIndex: 11,
        }"
    /> -->
    <UBadge :size="currentValue < 50 ? 'xs' : 'sm'" v-if="currentValue && currentValue >= 30" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 transform">
        {{ currentValue }}px
    </UBadge>
    <div
        v-if="showResizer"
        data-testid="bottom-height-resizer"
        @mousedown="
            ($event) => {
                resizingType = 'height';
                heightDirection = 'bottom';
                handleMouseDown($event);
            }
        "
        class="height-sizer button-edit hover:background-green-400 absolute z-10 h-[6px] w-[15px] cursor-ns-resize rounded-lg border border-green-400 bg-white"
        :style="{
            left: 'calc(50% - 6px)',
            bottom: '-2px',
            zIndex: 11,
        }"
    />

    <div
        v-if="showResizer"
        data-testid="right-width-resizer"
        @mousedown="
            ($event) => {
                resizingType = 'width';
                widthDirection = 'right';
                handleMouseDown($event);
            }
        "
        class="width-sizer button-edit hover:background-green-400 absolute z-10 h-[15px] w-[6px] cursor-ew-resize rounded-lg border border-green-400 bg-white"
        :style="{
            right: '-2px',
            top: 'calc(50% - 9px)',
            zIndex: 11,
        }"
    />

    <!-- <div
        v-if="showResizer"
        data-testid="left-width-resizer"
        @mousedown="
            ($event) => {
                resizingType = 'width';
                widthDirection = 'left';
                handleMouseDown($event);
            }
        "
        class="width-sizer button-edit hover:background-green-400 absolute z-10 h-[15px] w-[6px] cursor-ew-resize rounded-lg border border-green-400 bg-white"
        :style="{
            left: '-2px',
            top: 'calc(50% - 9px)',
            zIndex: 11,
        }"
    /> -->
</template>

<script setup lang="ts">
import { THeightDir, TResizeType, TWidthDir } from '@modules/Builder/resources/scripts/types';
import { calculator } from '@modules/Builder/resources/scripts/utils';


const resizingType = ref<TResizeType>('width');
const widthDirection = ref<TWidthDir>('right');
const heightDirection = ref<THeightDir>('bottom');
const isResizing = reactive({
    width: false,
    height: false,
});

const {
    getRect,
    updateStyle,
    getStyle,
    showResizer = false,
} = defineProps<{
    getRect: () => DOMRect | null;
    updateStyle: (property: string, value: any) => void;
    getStyle: (property: string) => any;
    showResizer: boolean;
}>();

const currentValue = ref<number | null>(null);

function handleMouseMove(e: MouseEvent) {
    const rect = getRect();
    if (resizingType.value === 'width') {
        const newWidth = Math.round(calculator.width(rect, widthDirection.value, e.clientX));
        currentValue.value =  newWidth;
        
        updateStyle('width.value', newWidth);
        updateStyle('width.unit', 'px');

        const minWith = getStyle('minWidth.value')
        if(newWidth < minWith){
            updateStyle('minWidth.value', newWidth);
            updateStyle('minWidth.unit', 'px');
        }
    } else {
        const newHeight = Math.round(calculator.height(rect, heightDirection.value, e.clientY));
        currentValue.value = newHeight;
        
        updateStyle('height.value', Math.round(newHeight));
        updateStyle('height.unit', 'px');

        const minHeight = getStyle('minHeight.value')

        if(newHeight < minHeight){
            updateStyle('minHeight.value', newHeight);
            updateStyle('minHeight.unit', 'px');
        }
    }
}

function handleMouseUp() {
    isResizing.width = false;
    isResizing.height = false;
    currentValue.value = null;
    window.removeEventListener('mousemove', handleMouseMove);
    window.removeEventListener('mouseup', handleMouseUp);
}

function handleMouseDown(e: MouseEvent) {
    e.stopPropagation();
    e.preventDefault();
    isResizing.width = resizingType.value === 'width';
    isResizing.height = resizingType.value === 'width';

    window.addEventListener('mousemove', handleMouseMove);
    window.addEventListener('mouseup', handleMouseUp);
}
</script>

<style scoped></style>
