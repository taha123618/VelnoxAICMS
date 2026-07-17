<template>
  <BaseElementWrapper :element="element">
    <div class="w-full flex items-center justify-center bg-neutral-900/10 border border-dashed border-neutral-700/30 text-[10px] text-neutral-500 font-mono" :style="{ height: getStyle('height') || '50px' }">
      Spacer ({{ getStyle('height') || '50px' }})
    </div>
  </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { computed } from 'vue';

const { element } = defineProps<{ element: ZioraElement }>();
const { getStyle } = useElement(element);
</script>
