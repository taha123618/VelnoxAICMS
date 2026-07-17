<template>
  <BaseElementWrapper :element="element">
    <div class="py-1 w-full flex items-center justify-center">
      <div class="w-full border-t border-neutral-700/50" />
    </div>
  </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

defineProps<{ element: ZioraElement }>();
</script>
