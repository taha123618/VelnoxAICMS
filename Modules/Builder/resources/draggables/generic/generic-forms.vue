<template>
  <BaseElementWrapper :element="element">

    <!-- Form Input -->
    <div v-if="element.type === 'form-input'" class="w-full space-y-1.5">
      <label class="block text-xs font-semibold text-neutral-300">
        {{ element.getProp('label') || 'Field Label' }}
        <span v-if="element.getProp('required')" class="text-red-400 ml-0.5">*</span>
      </label>
      <input
        :type="element.getProp('inputType') || 'text'"
        disabled
        :placeholder="element.getProp('placeholder') || 'Enter value...'"
        class="w-full px-3 py-2 bg-neutral-900 border border-neutral-700 rounded-lg text-xs text-white placeholder-neutral-500 outline-none cursor-not-allowed"
      />
      <p v-if="element.getProp('helpText')" class="text-[10px] text-neutral-500">{{ element.getProp('helpText') }}</p>
    </div>

    <!-- Form Textarea -->
    <div v-else-if="element.type === 'form-textarea'" class="w-full space-y-1.5">
      <label class="block text-xs font-semibold text-neutral-300">
        {{ element.getProp('label') || 'Message' }}
        <span v-if="element.getProp('required')" class="text-red-400 ml-0.5">*</span>
      </label>
      <textarea
        disabled
        :placeholder="element.getProp('placeholder') || 'Type your message...'"
        :rows="element.getProp('rows') || 4"
        class="w-full px-3 py-2 bg-neutral-900 border border-neutral-700 rounded-lg text-xs text-white placeholder-neutral-500 outline-none cursor-not-allowed resize-none"
      />
    </div>

    <!-- Form Select -->
    <div v-else-if="element.type === 'form-select'" class="w-full space-y-1.5">
      <label class="block text-xs font-semibold text-neutral-300">
        {{ element.getProp('label') || 'Select Option' }}
        <span v-if="element.getProp('required')" class="text-red-400 ml-0.5">*</span>
      </label>
      <div class="relative">
        <select disabled class="w-full appearance-none px-3 py-2 pr-8 bg-neutral-900 border border-neutral-700 rounded-lg text-xs text-neutral-400 outline-none cursor-not-allowed">
          <option>{{ element.getProp('placeholder') || 'Select an option...' }}</option>
          <option v-for="opt in selectOptions" :key="opt">{{ opt }}</option>
        </select>
        <UIcon name="ph:caret-down-bold" class="absolute right-2.5 top-1/2 -translate-y-1/2 size-3.5 text-neutral-500 pointer-events-none" />
      </div>
    </div>

    <!-- Form Checkbox -->
    <div v-else-if="element.type === 'form-checkbox'" class="flex items-start gap-2">
      <div class="size-4 mt-0.5 shrink-0 rounded border border-neutral-600 bg-neutral-800 flex items-center justify-center">
        <UIcon v-if="element.getProp('defaultChecked')" name="ph:check-bold" class="size-2.5 text-primary" />
      </div>
      <div class="min-w-0">
        <label class="text-xs text-neutral-300 font-medium cursor-pointer select-none">{{ element.getProp('label') || 'I agree to the terms and conditions' }}</label>
        <p v-if="element.getProp('helpText')" class="text-[10px] text-neutral-500 mt-0.5">{{ element.getProp('helpText') }}</p>
      </div>
    </div>

    <!-- Form Radio -->
    <div v-else-if="element.type === 'form-radio'" class="w-full space-y-2">
      <label class="block text-xs font-semibold text-neutral-300">{{ element.getProp('label') || 'Choose Option' }}</label>
      <div class="space-y-1.5">
        <label v-for="(opt, idx) in radioOptions" :key="idx" class="flex items-center gap-2 cursor-pointer">
          <div class="size-4 rounded-full border border-neutral-600 bg-neutral-800 flex items-center justify-center">
            <div v-if="idx === 0" class="size-2 rounded-full bg-primary" />
          </div>
          <span class="text-xs text-neutral-300">{{ opt }}</span>
        </label>
      </div>
    </div>

    <!-- Form Switch -->
    <div v-else-if="element.type === 'form-switch'" class="flex items-center justify-between gap-3">
      <div class="min-w-0">
        <label class="text-xs font-semibold text-neutral-300">{{ element.getProp('label') || 'Enable Feature' }}</label>
        <p v-if="element.getProp('helpText')" class="text-[10px] text-neutral-500 mt-0.5">{{ element.getProp('helpText') }}</p>
      </div>
      <div class="relative shrink-0">
        <div class="w-10 h-5 rounded-full transition"
             :class="element.getProp('defaultChecked') ? 'bg-primary' : 'bg-neutral-700'">
          <div class="absolute top-0.5 size-4 bg-white rounded-full shadow transition-transform"
               :class="element.getProp('defaultChecked') ? 'left-5.5' : 'left-0.5'" />
        </div>
      </div>
    </div>

    <!-- Form Range Slider -->
    <div v-else-if="element.type === 'form-range'" class="w-full space-y-2">
      <div class="flex items-center justify-between">
        <label class="text-xs font-semibold text-neutral-300">{{ element.getProp('label') || 'Range Slider' }}</label>
        <span class="text-xs font-bold text-primary">{{ element.getProp('defaultValue') || '50' }}</span>
      </div>
      <div class="relative w-full h-2 bg-neutral-700 rounded-full">
        <div class="absolute left-0 top-0 h-full bg-primary rounded-full" :style="{ width: (element.getProp('defaultValue') || 50) + '%' }" />
        <div class="absolute top-1/2 -translate-y-1/2 size-4 bg-white border-2 border-primary rounded-full shadow"
             :style="{ left: 'calc(' + (element.getProp('defaultValue') || 50) + '% - 8px)' }" />
      </div>
      <div class="flex justify-between text-[9px] text-neutral-500">
        <span>{{ element.getProp('min') || '0' }}</span>
        <span>{{ element.getProp('max') || '100' }}</span>
      </div>
    </div>

    <!-- Form Color Picker Preview -->
    <div v-else-if="element.type === 'form-color'" class="w-full space-y-1.5">
      <label class="block text-xs font-semibold text-neutral-300">{{ element.getProp('label') || 'Pick a Color' }}</label>
      <div class="flex items-center gap-2">
        <div class="size-8 rounded-lg border-2 border-neutral-700" :style="{ backgroundColor: element.getProp('defaultColor') || '#6366f1' }" />
        <input type="text" disabled :value="element.getProp('defaultColor') || '#6366f1'"
               class="flex-1 px-3 py-2 bg-neutral-900 border border-neutral-700 rounded-lg text-xs text-white outline-none cursor-not-allowed font-mono" />
      </div>
    </div>

    <div v-else class="text-xs text-red-500">Unresolved form component.</div>
  </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { computed } from 'vue';

const { element } = defineProps<{
  element: ZioraElement;
}>();

const selectOptions = computed<string[]>(() => {
  const raw = element.getProp('options');
  if (Array.isArray(raw)) return raw;
  if (typeof raw === 'string') return raw.split('\n').map((s: string) => s.trim()).filter(Boolean);
  return ['Option A', 'Option B', 'Option C'];
});

const radioOptions = computed<string[]>(() => {
  const raw = element.getProp('options');
  if (Array.isArray(raw)) return raw;
  if (typeof raw === 'string') return raw.split('\n').map((s: string) => s.trim()).filter(Boolean);
  return ['Choice A', 'Choice B', 'Choice C'];
});
</script>

<style scoped></style>
