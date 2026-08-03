<template>
    <div :class="cn([className, customClassNames, animationClass])">
        <TestimonialHorizontalItem
            :element="element"
            v-if="element.getProp('variant') == 'horizontal'"
        />

        <TestimonialVerticalItem
            v-else
            :element="element"
        />
    </div>
</template>

<script setup lang="ts">
import TestimonialHorizontalItem from '@modules/Builder/resources/draggables/components/testimonials/components/testimonial-horizontal-item.vue';
import TestimonialVerticalItem from '@modules/Builder/resources/draggables/components/testimonials/components/testimonial-vertical-item.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { useHead } from '@unhead/vue';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { useTestimonials } from '@modules/Builder/resources/draggables/components/testimonials/use-testimonials';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const {
    customClassNames,
    animationClass,
    className
} = useElement(element);

const { getStyles } = useTestimonials(element)

useHead({
    style: [
        {
            textContent: computed(() => getStyles()),
            id: element.id,
        },
    ],
});

</script>

<style>
@import url('assets/styles.css');
</style>
