<template>
    <div ref="elRef" :id="element.id" :class="cn(className, customClassNames, animationClass)" class="builder-element box-border">
        <a :href="((element.getContent('href') || '') as string)" :target="((element.getContent('target') || '_self') as string)" class="outline-none">
            {{ element.getContent('innerText') }}
        </a>
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
