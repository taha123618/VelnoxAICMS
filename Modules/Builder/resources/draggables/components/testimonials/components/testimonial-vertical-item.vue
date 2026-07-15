<template>
    <div class="mx-auto w-full max-w-3xl text-center">
        <!-- Testimonial image -->
        <div class="relative h-32">
            <div
                class="pointer-events-none absolute top-0 left-1/2 size-[480px] -translate-x-1/2 before:absolute before:inset-0 before:-z-10 before:rounded-full before:bg-gradient-to-b before:from-zinc-500/25 before:via-zinc-500/5 before:via-25% before:to-zinc-500/0 before:to-75%">
                <div
                    class="h-32 [mask-image:_linear-gradient(0deg,transparent,theme(colors.white)_20%,theme(colors.white))]">
                    <transition-group name="testimonial-image">
                        <div
                            v-for="(testimonial, index) in items"
                            v-show="active === index"
                            :key="`image-${index}`"
                            class="absolute inset-0 -z-10 flex h-full flex-col"
                        >
                            <UAvatar
                                :src="testimonial.avatar!"
                                :class="`el_${element.id}_avatar`"
                                class="relative top-11 left-1/2 h-[80px] w-[80px] -translate-x-1/2"
                            />
                        </div>
                    </transition-group>
                </div>
            </div>
        </div>
        <!-- Text -->
        <div class="lg:mb-8 transition-all delay-300 duration-150 ease-in-out">
            <div
                ref="testimonialsRef"
                class="relative flex flex-col"
            >
                <transition-group name="testimonial-text">
                    <div
                        v-for="(testimonial, index) in items"
                        v-show="active === index"
                        :key="`text-${index}`"
                        class="w-full"
                    >
                        <div
                            :class="`el_${element.id}_body`"
                            class="before:content-['\201C'] after:content-['\201D']"
                        >
                            {{ testimonial.comment }}
                        </div>
                    </div>
                </transition-group>
            </div>
        </div>
        <div
            :class="element.getProp('arrows')
                ? 'justify-between'
                : 'justify-center'
                "
            class="justify-betweenx lg:mt-4 flex w-full items-center gap-4 lg:pt-12 md:pt-0"
        >
            <UButton
                v-if="element.getProp('arrows')"
                @click="handlePrev"
                color="neutral"
                variant="soft"
                icon="ph:arrow-left"
                class="rounded-full duration-300 hover:rotate-12"
            />

            <!-- Name and Org -->
            <div class="flex flex-col items-center gap-1">
                <span :class="`el_${element.id}_heading`">
                    {{ items.at(active)?.name }}
                </span>
                <span :class="`el_${element.id}_subheading`">
                    {{ items.at(active)?.title }}
                </span>
            </div>
            <UButton
                v-if="element.getProp('arrows')"
                @click="handleNext"
                color="neutral"
                variant="soft"
                icon="ph:arrow-right"
                class="rounded-full duration-300 hover:rotate-12"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useTestimonials } from '@modules/Builder/resources/draggables/components/testimonials/use-testimonials';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();


const {
    handlePrev,
    handleNext,
    items,
    active
} = useTestimonials(element)

</script>

<style scoped></style>