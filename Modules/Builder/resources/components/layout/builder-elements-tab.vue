<template>
    <div
        v-for="item in elementItems"
        :key="item.value"
        class="mb-4"
    >
        <h1 class="text-white mb-1 flex min-w-0 text-xs font-medium">
            {{ item.label }}
        </h1>

        <div class="grid grid-cols-2 gap-1.5">
            <!-- prettier-ignore -->
            <BaseBlueprint
                v-for="el in (item.components as any as TElement[])"
                :key="el.id"
                :element="el"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import type { TElement } from '@modules/Builder/resources/scripts/types';
import BaseBlueprint from '@modules/Builder/resources/components/base-blueprint.vue';
import { elementGroups } from '@modules/Builder/resources/draggables';
import type { AccordionItem } from '@nuxt/ui';

const elementItems = elementGroups.map((grp) => {
    return {
        label: grp.name,
        value: grp.id,
        ...grp,
    } as any as AccordionItem;
}) satisfies AccordionItem[];

</script>

<style scoped></style>
