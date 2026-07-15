<template>
    <BaseElementWrapper :element="element">
        <TestimonialHorizontalItem
            :element="element"
            v-if="element.getProp('variant') == 'horizontal'"
        />
        <TestimonialVerticalItem
            v-else
            :element="element"
        />
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import TestimonialHorizontalItem from '@modules/Builder/resources/draggables/components/testimonials/components/testimonial-horizontal-item.vue';
import TestimonialVerticalItem from '@modules/Builder/resources/draggables/components/testimonials/components/testimonial-vertical-item.vue';
import { useTestimonials } from '@modules/Builder/resources/draggables/components/testimonials/use-testimonials';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { useHead } from '@unhead/vue';

const { element } = defineProps<{
    element: ZioraElement;
}>();

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