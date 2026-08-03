<template>
    <BaseElementWrapper
        :element="element"
        class="!relative flex flex-col gap-2"
    >
        <BaseRecursiveElement
            v-for="child in element.children"
            :key="child.id"
            :element="child"
        />
        <div
            class="flex items-center py-4"
            :class="{
                'justify-end': element.getProp('button.align') == 'right',
                'justify-center': element.getProp('button.align') == 'center',
                'justify-start': element.getProp('button.align') == 'left',
            }"
        >
            <UButton
                :ui="{
                    base: 'submit-button rounded-nonex'
                }"
                type="submit"
                @click.prevent
                :block="element.getProp('button.align') == 'full'"
                :label="element.getProp('button.label')"
                :size="element.getProp('button.size')"
                :variant="element.getProp('button.variant')"
                :color="element.getProp('button.color')"
            />
        </div>
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import { useFormStyles } from '@modules/Builder/resources/draggables/forms/form/use-form-styles';
import { useHead } from '@unhead/vue';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const { getStyles } = useFormStyles(element)

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
