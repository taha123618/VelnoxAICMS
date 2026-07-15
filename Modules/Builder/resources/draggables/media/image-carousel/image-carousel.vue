<template>
    <BaseElementWrapper :element="element">
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
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import { useCarousel } from '@modules/Builder/resources/draggables/media/image-carousel/use-carousel';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { useHead } from '@unhead/vue';

const { element } = defineProps<{
    element: ZioraElement;
}>();

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
