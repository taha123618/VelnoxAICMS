<template>
    <BaseElementWrapper :element="element">
        <UTabs
            :ui="{
                leadingIcon: 'hidden',
                trigger: `cursor-pointer trigger`,
                indicator: `indicator`,
            }"
            label-key="name"
            :orientation="element.getProp('orientation')"
            :variant="element.getProp('variant')"
            :items="items"
        >
            <template #default="{ item }">
                {{ item.name }}
            </template>
            <template #content="{ item }">
                <!-- prettier-ignore -->
                <TabsItem :element="(item as VelnoxAIElement)" />
            </template>
        </UTabs>
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import TabsItem from '@modules/Builder/resources/draggables/components/tabs/tabs-item/tabs-item.vue';
import type { TabsItem as TTabsItem } from '@nuxt/ui';
import { useHead } from '@unhead/vue';
import { useTabs } from '@modules/Builder/resources/draggables/components/tabs/use-tabs';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const items = computed<TTabsItem[]>(
    () => element.children as TTabsItem[],
);

const { getStyles } = useTabs(element)

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
