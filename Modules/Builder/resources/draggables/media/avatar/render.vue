<template>
    <div
        :class="cn(className, customClassNames, animationClass)"
        class="builder-element z-0 box-border outline-2 outline-offset-0 outline-transparent">
       <UAvatar
            :src="element.getProp('src')"
            :alt="element.getProp('alt')"
            :text="element.getProp('text')"
            :ui="{
                root: `size-full rounded-[inherit]`,
            }"
        />
    </div>
</template>

<script setup lang="ts">
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const { customClassNames, animationClass, className } = useElement(element);
</script>

<style scoped></style>
