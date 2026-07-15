<template>
  <UFormField
    v-if="showLabel"
    :label="label"
    class="text-xs"
    :ui="{
      label: 'block font-normal ziora-label',
      container: labelPosition == 'left' ? 'col-span-2 mt-0' : 'relative'
    }"
    :class="[
      labelPosition == 'left'
        ? 'grid grid-cols-3 gap-1 items-center'
        : 'flex flex-col gap-1',
    ]"
  >
    <template #label>
      <div class="flex gap-0.5 items-center">
        <div>{{ label }}</div>
        <BaseTooltip
          v-if="hasChanged"
          content="Clear hover style"
        >
          <UButton
            @click.prevent="emit('clear:hover')"
            size="sm"
            color="error"
            variant="link"
            icon="ph:x"
          ></UButton>
        </BaseTooltip>
      </div>
    </template>

    <UPopover
      class="dark w-full flex items-center flex-nowrap"
      arrow
      v-model:open="isOpen"
    >
      <div class="inline-flex items-center gap-1 flex-nowrap">
        <UButton
          color="neutral"
          size="xl"
          variant="subtle"
          class="dark h-6 w-28 truncate flex flex-1 items-center !p-0"
        >
          <UIcon
            v-if="value == 'transparent' || value == 'none'"
            class="size-6"
            name="mdi:square-transparent"
          />
          <span
            class="flex w-28 text-xs items-center gap-1 truncate"
            v-else-if="propertyType == 'solid'"
          >
            <UIcon
              class="h-full border-5"
              :style="{ backgroundColor: value, borderColor: value }"
              name="ph:square-fill"
            />
            {{ value }}
          </span>
          <div
            :style="{ backgroundImage: value }"
            class="w-28 text-xs truncate rounded px-0.5"
            v-else
          >
            {{ value }}
          </div>
        </UButton>
        <BaseTooltip content="Clear">
          <UButton
            :disabled="value == 'transparent' || value == 'none'"
            @click.prevent="emit('change', 'none')"
            size="sm"
            color="error"
            variant="link"
            icon="ph:x"
          />
        </BaseTooltip>
      </div>

      <template #content>
        <UCard :ui="{
          body: 'sm:p-0 max-h-[23rem] overflow-y-auto'
        }">
          <Vue3ColorPicker
            :mode="propertyType"
            :model-value="value"
            :show-color-list="false"
            :show-eye-drop="true"
            :show-input-menu="false"
            :show-picker-mode="false"
            type="RGBA"
            input-type="RGB"
            class="!shadow-none"
            :show-buttons="false"
            @update:model-value="emit('change', $event)"
          />
        </UCard>
      </template>
    </UPopover>
  </UFormField>

  <UPopover
    v-else
    class="dark w-full flex items-center flex-nowrap"
    arrow
    v-model:open="isOpen"
  >
    <div class="inline-flex items-center gap-1 flex-nowrap">
      <UButton
        color="neutral"
        size="xl"
        variant="subtle"
        class="dark h-6 flex flex-1 overflow-hidden items-center px-1x !p-0"
      >
        <UIcon
          v-if="value == 'transparent' || value == 'none'"
          class="size-6"
          name="mdi:square-transparent"
        />
        <span
          class="flex w-28x text-xs items-center gap-1 truncate"
          v-else-if="propertyType == 'solid'"
        >
          <UIcon
            class="h-full border-5"
            :style="{ backgroundColor: value, borderColor: value }"
            name="ph:square-fill"
          />
          {{ value }}
        </span>
        <div
          :style="{ backgroundImage: value }"
          class="w-28x text-xs truncate rounded px-0.5"
          v-else
        >
          {{ value }}
        </div>
      </UButton>
    </div>

    <template #content>
      <UCard :ui="{
        body: 'sm:p-0 max-h-[23rem] overflow-y-auto'
      }">
        <Vue3ColorPicker
          :mode="propertyType"
          :model-value="value"
          :show-color-list="false"
          :show-eye-drop="true"
          :show-input-menu="false"
          :show-picker-mode="false"
          type="RGBA"
          input-type="RGB"
          class="!shadow-none"
          :show-buttons="false"
          @update:model-value="emit('change', $event)"
        />
      </UCard>
    </template>
  </UPopover>
</template>

<script setup lang="ts">
import { Vue3ColorPicker } from '@cyhnkckali/vue3-color-picker';
import '@cyhnkckali/vue3-color-picker/dist/style.css'
import BaseTooltip from "@modules/Builder/resources/components/base-tooltip.vue";
import { TGradientType, TLabelPosition } from "@modules/Builder/resources/scripts/types";

interface Props {
  value: string | undefined;
  inputClass?: string;
  label?: string;
  clearable?: boolean;
  showLabel?: boolean;
  hasChanged?: boolean;
  placeholder?: string;
  labelPosition?: TLabelPosition;
  propertyType?: 'solid' | 'gradient',
  gradientType?: TGradientType
}

withDefaults(defineProps<Props>(), {
  labelPosition: "top",
  clearable: true,
  hasChanged: false,
  showLabel: true,
  inputClass: "w-12",
  propertyType: 'solid',
  gradientType: 'linear'
});

const isOpen = ref<boolean>(false);

const emit = defineEmits(["change", "clear:hover"]);

</script>

<style>

</style>
