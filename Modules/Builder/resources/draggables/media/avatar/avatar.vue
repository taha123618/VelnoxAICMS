<template>
    <BaseElementWrapper :element="element">
        <UAvatar
            :src="element.getProp('src')"
            :alt="element.getProp('alt')"
            :text="element.getProp('text')"
            :ui="{
                root: `size-full rounded-[inherit]`,
            }"
        />
    </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();

</script>

<style scoped></style>
