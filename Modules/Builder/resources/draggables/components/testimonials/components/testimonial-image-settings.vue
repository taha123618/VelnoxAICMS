<template>
    <div class="space-y-4">
        <template v-if="element.getProp('variant') == 'horizontal'">
            <template
                v-for="item in imageDimensions"
                :key="item.id">
                <BuilderInputGroup
                    label-position="left"
                    select-class="w-auto"
                    :show-icons="false"
                    :max="10000"
                    :disabled="element.getProp(`${item.id}.unit`) == 'auto'"
                    :options="item.options"
                    :label="item.name"
                    @change:input="element.setProps(`${item.id}.value`, $event)"
                    @change:select="element.setProps(`${item.id}.unit`, $event)"
                    :input-value="element.getProp(`${item.id}.value`)"
                    :select-value="element.getProp(`${item.id}.unit`)" />
            </template>
        </template>
        <template v-else>
            <template
                v-for="item in avatarDimensions"
                :key="item.id">
                <BuilderInputGroup
                    label-position="left"
                    select-class="w-auto"
                    :show-icons="false"
                    :max="10000"
                    :disabled="element.getProp(`${item.id}.unit`) == 'auto'"
                    :options="item.options"
                    :label="item.name"
                    @change:input="element.setProps(`${item.id}.value`, $event)"
                    @change:select="element.setProps(`${item.id}.unit`, $event)"
                    :input-value="element.getProp(`${item.id}.value`)"
                    :select-value="element.getProp(`${item.id}.unit`)" />
            </template>
        </template>
    </div>
</template>

<script setup lang="ts">
import BuilderInputGroup from '@modules/Builder/resources/components/form/builder-input-group.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const heightUnitOptions = ['%', 'px', 'rem', 'vh', 'em'];
const widthUnitOptions = ['%', 'px', 'rem', 'vw', 'em'];

const imageDimensions = [
    { id: 'image.height', name: 'Image height', options: heightUnitOptions },
    { id: 'image.width', name: 'Image width', options: widthUnitOptions },
];

const avatarDimensions = [
    { id: 'avatar.height', name: 'Avatar height', options: heightUnitOptions },
    { id: 'avatar.width', name: 'Avatar width', options: widthUnitOptions },
];
</script>

<style scoped></style>
