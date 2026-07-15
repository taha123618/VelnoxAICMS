<template>
    <div class="relative grid h-full grid-cols-1 gap-10 @md:grid-cols-3">
        <div class="relative h-72 w-full">
            <AnimatePresence>
                <Motion
                    v-for="(testimonial, index) in items"
                    :key="testimonial.id"
                    as="div"
                    :initial="{
                        opacity: 0,
                        scale: 0.9,
                        z: -100,
                        rotate: randomRotateY(),
                    }"
                    :animate="{
                        opacity: isActive(index) ? 1 : 0.7,
                        scale: isActive(index) ? 1 : 0.95,
                        z: isActive(index) ? 0 : -100,
                        rotate: isActive(index) ? 0 : randomRotateY(),
                        zIndex: isActive(index)
                            ? 1
                            : Math.min(items.length + 2 - index, 0),
                        y: isActive(index) ? [0, -80, 0] : 0,
                    }"
                    :exit="{
                        opacity: 0,
                        scale: 0.9,
                        z: 100,
                        rotate: randomRotateY(),
                    }"
                    :transition="{
                        duration: 0.4,
                        ease: 'easeInOut',
                    }"
                    class="absolute inset-0 origin-bottom"
                >
                    <img
                        :src="testimonial.avatar!"
                        :alt="testimonial.name"
                        :class="`el_${element.id}_img`"
                        class="size-full rounded-3xl object-cover object-center"
                    />
                </Motion>
            </AnimatePresence>
        </div>
        <div class="flex flex-col justify-between @md:col-span-2">
            <Motion
                :key="active"
                as="div"
                :initial="{
                    y: 20,
                    opacity: 0,
                }"
                :animate="{
                    y: 0,
                    opacity: 1,
                }"
                :exit="{
                    y: -20,
                    opacity: 0,
                }"
                :transition="{
                    duration: 0.2,
                    ease: 'easeInOut',
                }"
            >
                <h3 :class="`el_${element.id}_heading`">
                    {{ items.at(active)?.name }}
                </h3>
                <p :class="`el_${element.id}_subheading`">
                    {{ items.at(active)?.title }}
                </p>
                <Motion
                    as="p"
                    :class="`el_${element.id}_body`"
                    class="mt-4"
                >
                    <Motion
                        v-for="(word, index) in activeTestimonialComment"
                        :key="index"
                        as="span"
                        :initial="{
                            filter: 'blur(10px)',
                            opacity: 0,
                            y: 5,
                        }"
                        :animate="{
                            filter: 'blur(0px)',
                            opacity: 1,
                            y: 0,
                        }"
                        :transition="{
                            duration: 0.2,
                            ease: 'easeInOut',
                            delay: 0.02 * index,
                        }"
                        class="inline-block"
                    >
                        {{ word }}&nbsp;
                    </Motion>
                </Motion>
            </Motion>
            <div class="flex gap-4 pt-10 @md:pt-0" v-if="element.getProp('arrows')">
                <UButton
                    @click="handlePrev"
                    color="neutral"
                    variant="soft"
                    icon="ph:arrow-left"
                    class="rounded-full duration-300 hover:rotate-12"
                />
                <UButton
                    @click="handleNext"
                    color="neutral"
                    variant="soft"
                    icon="ph:arrow-right"
                    class="rounded-full duration-300 hover:rotate-12"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useTestimonials } from '@modules/Builder/resources/draggables/components/testimonials/use-testimonials';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { Motion, AnimatePresence } from "motion-v";

const { element } = defineProps<{
    element: ZioraElement;
}>();


const {
    handlePrev,
    handleNext,
    items,
    active,
    activeTestimonialComment,
    isActive,
    randomRotateY
} = useTestimonials(element)

</script>

<style scoped></style>