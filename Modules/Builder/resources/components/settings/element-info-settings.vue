<template>
  <div class="space-y-2">
    <UFormField
      label="ID"
      :ui="{
        label: 'block font-normal ziora-label',
      }"
      class="text-xs grid grid-cols-2 items-center"
    >
      <div
        class="rounded-md border-0 opacity-75 text-xs text-white bg-elevated ring ring-inset ring-accented flex px-2 py-1 items-center justify-between border-neutral-700"
      >
        <span>{{ element.id }}</span>
        <BaseTooltip content="Copy">
          <button @click="onCopy">
            <UIcon name="ph:copy" />
          </button>
        </BaseTooltip>
      </div>
    </UFormField>

    <BuilderInput
      label-position="left"
      :value="element.name"
      @change="element.setName($event)"
      label="Name"
    />
  </div>
</template>

<script setup lang="ts">
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';

const { element } = defineProps<{
  element: ZioraElement;
}>();

const toast = useToast();

async function onCopy() {
  try {
    await navigator.clipboard.writeText(element.id);

    toast.add({
      color: "success",
      title: "Copied to clipboard",
    });
  } catch (error) {
    console.log(error)
    toast.add({
      color: "error",
      title: "Unable to copy to clipboard.",
    });
  }
}
</script>

<style scoped></style>
