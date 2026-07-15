<template>
  <UTooltip
    arrow
    :delay-duration="0"
    :text="content"
    :content="{
      align: 'center',
      side: side,
      sideOffset: 0,
    }"
  >
    <slot />
  </UTooltip>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    content: string;
    side?: "left" | "right" | "bottom" | "top";
  }>(),
  {
    side: "top",
  }
);
</script>

<style scoped></style>
