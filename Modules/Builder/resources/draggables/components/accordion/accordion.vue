<template>
    <BaseElementWrapper :element="element">
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
                <AccordionItem :element="(item as VelnoxAIElement)" />
            </template>
        </UAccordion>
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import AccordionItem from '@modules/Builder/resources/draggables/components/accordion/accordion-item/accordion-item.vue';
import { useAccordion } from '@modules/Builder/resources/draggables/components/accordion/use-accordion';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

import { useHead } from '@unhead/vue';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const { getStyles, items } = useAccordion(element)

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
