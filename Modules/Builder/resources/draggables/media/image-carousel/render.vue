<template>
    <div :class="cn(className, customClassNames, animationClass)">
        <UCarousel
            v-slot="{ item }"
            :ui="{
                item: `item`,
                container: `container`,
            }"
            :items="items"
            :autoplay="element.getProp('autoplay.active')
                ? { delay: element.getProp('autoplay.delay') }
                : false
                "
            :dots="element.getProp('dots')"
            :fade="element.getProp('fade')"
            :arrows="element.getProp('arrows')"
            :orientation="element.getProp('orientation')"
            :auto-scroll="element.getProp('autoScroll')"
            :loop="element.getProp('loop')"
        >
            <img
                :src="String(item)"
                class="img"
            />
        </UCarousel>
    </div>
</template>

<script setup lang="ts">
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { useHead } from '@unhead/vue';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useCarousel } from '@modules/Builder/resources/draggables/media/image-carousel/use-carousel';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const { className, customClassNames, animationClass } = useElement(element);


const { getStyles, items } = useCarousel(element)

useHead({
    style: [
        {
            textContent: computed(() => getStyles()),
            id: element.id,
        },
    ],
});
</script>

<style></style>
