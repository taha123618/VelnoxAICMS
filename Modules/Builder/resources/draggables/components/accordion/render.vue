<template>
    <div :class="cn(className, customClassNames, animationClass)">
        <UAccordion
            :ui="{
                root: `accordion`,
                leadingIcon: 'hidden',
                trigger: `trigger`,
            }"
            :type="element.getProp('isSingle') ? 'single' : 'multiple'"
            label-key="name"
            :items="items"
        >
            <template #default="{ item }">
                {{ item.name }}
            </template>
            <template #content="{ item }">
                <!-- prettier-ignore -->
                <AccordionItem :element="(item as ZioraElement)" />
            </template>
        </UAccordion>
    </div>
</template>

<script setup lang="ts">
import { cn } from '@modules/Builder/resources/scripts/utils';
import AccordionItem from '@modules/Builder/resources/draggables/components/accordion/accordion-item/accordion-item.vue';
import { useAccordion } from '@modules/Builder/resources/draggables/components/accordion/use-accordion';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { useHead } from '@unhead/vue';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const { getStyles, items } = useAccordion(element)


const { customClassNames, animationClass, className } = useElement(element);

useHead({
    style: [
        {
            textContent: computed(() => getStyles()),
            id: element.id,
        },
    ],
});
</script>

<style scoped></style>
