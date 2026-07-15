<template>
    <div class="space-y-4">
        <BuilderTagsInput
            label="Custom classes"
            @change="element.setCustomStyle('classNames', $event)"
            :value="element.getCustomStyle('classNames')"
        />
        
        <BuilderCodeInput
            v-if="element.hasCustomStyle('styles')"
            label="Custom css"
            :value="element.getCustomStyle('styles')"
            @change="element.setCustomStyle('styles', $event)"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderCodeInput from '@modules/Builder/resources/components/form/builder-code-input.vue';
import BuilderTagsInput from '@modules/Builder/resources/components/form/builder-tags-input.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';


const { element } = defineProps<{ element: ZioraElement }>();

</script>

<style scoped></style>
