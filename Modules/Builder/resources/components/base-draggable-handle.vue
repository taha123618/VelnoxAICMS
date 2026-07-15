<template>
  <div
    class="pointer-events-auto font-sans flex w-fit items-center justify-center overflow-hidden bg-primary text-inverted"
  >
    <span class="px-2 text-xs text-white">{{ element.name }}: </span>

    <BaseTooltip content="Settings">
      <button
        @click="store.setSelectedElement(element)"
        class="h-7 cursor-pointer px-1.5"
      >
        <UIcon
          name="ph:gear"
          class="size-4"
        />
      </button>
    </BaseTooltip>
    <BaseTooltip content="Move">
      <button
        class="h-7 cursor-move px-1.5"
        :ref="`handleRef_${element.id}`"
      >
        <UIcon
          name="ph:arrows-out-cardinal"
          class="size-4"
        />
      </button>
    </BaseTooltip>

    <BaseTooltip content="Delete">
      <button
        @click.stop="store.deleteElement(element.id)"
        class="h-7 cursor-pointer px-1.5"
      >
        <UIcon
          name="ph:trash"
          class="size-4"
        />
      </button>
    </BaseTooltip>
  </div>
</template>

<script setup lang="ts">
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';

const { element } = defineProps<{
  element: ZioraElement;
}>();

const store = useZiora();

const dragHandleRef = useTemplateRef<string>(`handleRef_${element.id}`);

defineExpose({
  dragHandleRef,
});
</script>

<style scoped></style>
