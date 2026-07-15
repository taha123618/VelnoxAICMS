<template>
    <template v-if="!store.selectedElement">
        <p class="text-builder-secondary-foreground p-2 text-center">
            Please select an item to configure!
        </p>
    </template>
    <Transition enter-active-class="animate-in duration-500 slide-in-from-left-5">
        <div
            class="dark py-2 space-y-4"
            v-if="!!store.selectedElement"
        >
            <div class="text-xs">
                <BuilderSelect
                    label-position="left"
                    label="State"
                    :options="[
                        { label: 'Default', value: 'default' },
                        { label: 'Hover', value: 'hover' },
                    ]"
                    :value="store.currentState"
                    @change="store.changeCurrentState($event)"
                />
            </div>
            <UAccordion
                label-key="name"
                value-field="id"
                :ui="{
                    root: 'dark',
                    content: 'dark',
                    trigger: 'group flex-1 flex items-center gap-1.5 text-white font-medium text-xs py-1.5 focus-visible:outline-primary min-w-0',
                }"
                :items="(settingsList as any as AccordionItem[])"
                type="single"
            >
                <template #content="{ item }">
                    <div class="pb-4">
                        <component
                            :is="item.component"
                            :element="store.selectedElement"
                        />
                    </div>
                </template>
            </UAccordion>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import { elementSettings } from '@modules/Builder/resources/draggables';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import type { AccordionItem } from '@nuxt/ui';
import { computed } from 'vue';

const store = useZiora();


const settingsList = computed<Record<string, any>>(() => {
    if (!store.selectedElement) return [];
    const item = elementSettings.find(
        (entry) => entry.type == store.selectedElement?.type,
    );

    if (!item) {
        return [];
    }
    return item.settings;

    // return item.settings.flatMap(i => i.content)

});
</script>

<style scoped></style>
