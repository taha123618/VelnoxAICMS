<template>
  <BaseElementWrapper :element="element">
    <!-- Gallery Element -->
    <div v-if="element.type === 'gallery'" class="grid grid-cols-3 gap-2 w-full">
      <div v-for="(img, idx) in galleryImages" :key="idx" class="relative group aspect-square rounded overflow-hidden bg-neutral-800">
        <img :src="img" class="object-cover w-full h-full" />
      </div>
      <div v-if="galleryImages.length === 0" class="col-span-3 flex items-center justify-center p-8 bg-neutral-900 border border-dashed border-neutral-700 text-neutral-500 rounded text-xs">
        No images in gallery. Set images in settings panel.
      </div>
    </div>

    <!-- Audio Element -->
    <div v-else-if="element.type === 'audio'" class="w-full p-2 bg-neutral-900 border border-neutral-700 rounded flex items-center gap-3">
      <UIcon name="ph:music-notes-bold" class="size-6 text-primary shrink-0" />
      <div class="flex-1 min-w-0">
        <p class="text-xs font-semibold truncate text-white">{{ element.getProp('title') || 'Audio Block' }}</p>
        <p class="text-[10px] text-neutral-400 truncate">{{ element.getProp('src') || 'No audio file source set' }}</p>
      </div>
      <audio class="hidden" controls />
    </div>

    <!-- Icon Element -->
    <div v-else-if="element.type === 'icon'" class="inline-flex items-center justify-center">
      <UIcon :name="element.props.icon || 'ph:cube-bold'" :style="{ fontSize: (element.props.iconSize || 24) + 'px', color: element.props.iconColor || 'inherit' }" />
    </div>

    <!-- Lottie Element -->
    <div v-else-if="element.type === 'lottie'" class="flex flex-col items-center justify-center p-4 bg-neutral-950 border border-neutral-800 rounded w-full aspect-video">
      <UIcon name="ph:play-circle-bold" class="size-8 text-neutral-600 animate-pulse" />
      <span class="text-xs text-neutral-500 mt-2">Lottie Animation ({{ element.getProp('src') || 'No URL set' }})</span>
    </div>

    <!-- SVG Element -->
    <div v-else-if="element.type === 'svg'" class="inline-block" v-html="element.props.svgCode || defaultSvg" />

    <!-- Background Video Block -->
    <div v-else-if="element.type === 'bgvideo'" class="relative overflow-hidden w-full min-h-[200px] flex flex-col justify-center items-center">
      <div class="absolute inset-0 bg-black/40 z-10 pointer-events-none" />
      <div class="absolute inset-0 flex items-center justify-center text-xs text-neutral-400 z-0">
        [Background Video Placeholder: {{ element.getProp('videoUrl') || 'No video URL set' }}]
      </div>
      <div class="relative z-20 w-full h-full flex flex-col">
        <BaseRecursiveElement v-for="child in element.children" :key="child.id" :element="child" />
      </div>
    </div>

    <div v-else class="text-xs text-red-500">Unresolved media component.</div>
  </BaseElementWrapper>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { computed } from 'vue';

const { element } = defineProps<{
  element: ZioraElement;
}>();

const galleryImages = computed<string[]>(() => {
  const images = element.getProp('images');
  if (Array.isArray(images)) return images;
  if (typeof images === 'string') return images.split('\n').map(x => x.trim()).filter(Boolean);
  return [
    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=300&auto=format&fit=crop&q=60',
    'https://images.unsplash.com/photo-1472214222541-d510753a49df?w=300&auto=format&fit=crop&q=60',
    'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=300&auto=format&fit=crop&q=60'
  ];
});

const defaultSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-neutral-500"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>`;
</script>

<style scoped></style>
