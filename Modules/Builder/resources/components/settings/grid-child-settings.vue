<template>
  <div class="space-y-4">
    <template v-if="parentIsGrid">
      <BuilderSlider
        label="Column span"
        :has-changed="hasChanged('gridColumn')"
        @clear:hover="deleteHoverStyle('gridColumn')"
        :value="Number(getStyle('gridColumn') || 1)"
        @change="setStyle('gridColumn', $event)"
      />

      <BuilderSlider
        label="Row span"
        :value="Number(getStyle('gridRow') || 1)"
        :has-changed="hasChanged('gridRow')"
        @clear:hover="deleteHoverStyle('gridRow')"
        @change="setStyle('gridRow', $event)"
      />
    </template>
  </div>
</template>

<script setup lang="ts">
import BuilderSlider from '@modules/Builder/resources/components/form/builder-slider.vue';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{ element: VelnoxAIElement }>();

const { getStyle, hasChanged, deleteHoverStyle, parentIsGrid, setStyle } = useElement(element);
</script>

<style scoped></style>
