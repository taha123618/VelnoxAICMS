<template>
  <div :class="cn(className, customClassNames)" />
</template>

<script setup lang="ts">
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';

const { element } = defineProps<{ element: ZioraElement }>();
const { className, customClassNames } = useElement(element);
</script>
