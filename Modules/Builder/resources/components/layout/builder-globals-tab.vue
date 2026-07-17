<template>
  <div class="p-3 space-y-4">
    <div class="pb-2 border-b border-neutral-800">
      <h3 class="text-xs font-bold text-white uppercase tracking-wider">Global Styles & Theme</h3>
      <p class="text-[10px] text-neutral-500 mt-1">Configure global CSS custom variables injected across the site.</p>
    </div>

    <!-- Primary Color -->
    <div class="space-y-1">
      <label class="text-[11px] font-semibold text-neutral-400">Primary Color</label>
      <div class="flex gap-2 items-center">
        <input 
          type="color" 
          :value="theme.primaryColor || '#3b82f6'" 
          @input="updateTheme('primaryColor', ($event.target as HTMLInputElement).value)"
          class="size-7 rounded border border-neutral-700 bg-neutral-900 cursor-pointer"
        />
        <input 
          type="text" 
          :value="theme.primaryColor || '#3b82f6'" 
          @change="updateTheme('primaryColor', ($event.target as HTMLInputElement).value)"
          class="flex-1 bg-neutral-800 border border-neutral-700 text-xs px-2.5 py-1.5 rounded text-white"
        />
      </div>
    </div>

    <!-- Background Color -->
    <div class="space-y-1">
      <label class="text-[11px] font-semibold text-neutral-400">Background Color</label>
      <div class="flex gap-2 items-center">
        <input 
          type="color" 
          :value="theme.backgroundColor || '#ffffff'" 
          @input="updateTheme('backgroundColor', ($event.target as HTMLInputElement).value)"
          class="size-7 rounded border border-neutral-700 bg-neutral-900 cursor-pointer"
        />
        <input 
          type="text" 
          :value="theme.backgroundColor || '#ffffff'" 
          @change="updateTheme('backgroundColor', ($event.target as HTMLInputElement).value)"
          class="flex-1 bg-neutral-800 border border-neutral-700 text-xs px-2.5 py-1.5 rounded text-white"
        />
      </div>
    </div>

    <!-- Font Family -->
    <div class="space-y-1">
      <label class="text-[11px] font-semibold text-neutral-400">Primary Font Family</label>
      <select 
        :value="theme.fontFamily || 'Inter, sans-serif'"
        @change="updateTheme('fontFamily', ($event.target as HTMLSelectElement).value)"
        class="w-full bg-neutral-800 border border-neutral-700 text-xs px-2 py-1.5 rounded text-white cursor-pointer"
      >
        <option value="Inter, sans-serif">Inter (Modern Sans)</option>
        <option value="Outfit, sans-serif">Outfit (Premium Rounded)</option>
        <option value="Roboto, sans-serif">Roboto</option>
        <option value="'Playfair Display', serif">Playfair Display (Elegant Serif)</option>
        <option value="system-ui, sans-serif">System Default</option>
      </select>
    </div>

    <!-- Border Radius -->
    <div class="space-y-1">
      <div class="flex justify-between text-[11px] font-semibold text-neutral-400">
        <span>Default Border Radius</span>
        <span>{{ theme.borderRadius || 8 }}px</span>
      </div>
      <input 
        type="range" 
        min="0" 
        max="32" 
        :value="theme.borderRadius || 8"
        @input="updateTheme('borderRadius', Number(($event.target as HTMLInputElement).value))"
        class="w-full h-1 bg-neutral-800 rounded-lg appearance-none cursor-pointer accent-primary"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import { computed } from 'vue';

const store = useZiora();

const theme = computed(() => {
  const body = store.elements[0];
  if (!body) return {};
  if (!body.props) body.props = {};
  if (!body.props.theme) {
    body.props.theme = {
      primaryColor: '#3b82f6',
      backgroundColor: '#ffffff',
      fontFamily: 'Inter, sans-serif',
      borderRadius: 8
    };
  }
  return body.props.theme;
});

function updateTheme(key: string, value: any) {
  const body = store.elements[0];
  if (body) {
    if (!body.props) body.props = {};
    if (!body.props.theme) body.props.theme = {};
    body.props.theme[key] = value;
  }
}
</script>
