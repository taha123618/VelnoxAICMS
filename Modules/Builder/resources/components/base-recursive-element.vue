<template>
  <component
    :is="resolvedComponent"
    :element="element"
  />
</template>

<script lang="ts" setup>
import { elementSettings } from '@modules/Builder/resources/draggables';
import { useVelnoxAI } from '@modules/Builder/resources/scripts/use-VelnoxAI';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';

const { element } = defineProps<{
  element: VelnoxAIElement;
}>();

const store = useVelnoxAI()

const resolvedComponent = computed(() => {
  const resolvedElement = elementSettings.find(
    (setting) => setting.type == element.type
  );

  if (!resolvedElement || resolvedElement == undefined) {
    throw new Error(
      "Unidentified element. Make sure the element has a `settings` definition."
    );
  }
  return store.enabled ? resolvedElement.component : (resolvedElement.renderable || resolvedElement.component);
});
</script>

<style lang="scss" scoped></style>
