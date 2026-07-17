<template>
  <!-- Gallery Block -->
  <div v-if="element.type === 'gallery'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 w-full')">
    <div v-for="(img, idx) in galleryImages" :key="idx" class="relative overflow-hidden aspect-square rounded bg-neutral-100 hover:shadow-lg transition duration-300">
      <img :src="img" class="object-cover w-full h-full hover:scale-105 transition duration-500" />
    </div>
  </div>

  <!-- Audio Block -->
  <div v-else-if="element.type === 'audio'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'w-full')">
    <audio :src="element.getProp('src')" controls class="w-full" />
  </div>

  <!-- Icon Block -->
  <div v-else-if="element.type === 'icon'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'inline-flex items-center justify-center')">
    <UIcon :name="element.props.icon || 'ph:cube-bold'" :style="{ fontSize: (element.props.iconSize || 24) + 'px', color: element.props.iconColor || 'inherit' }" />
  </div>

  <!-- Lottie Animation -->
  <div v-else-if="element.type === 'lottie'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'w-full flex justify-center')">
    <iframe :src="element.getProp('src')" class="border-0 max-w-full aspect-square" width="300" height="300" />
  </div>

  <!-- SVG Block -->
  <div v-else-if="element.type === 'svg'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'inline-block')" 
       v-html="element.props.svgCode || defaultSvg" />

  <!-- Background Video Block -->
  <div v-else-if="element.type === 'bgvideo'" 
       v-bind="elementAttributes"
       :class="cn(className, customClassNames, animationClass, 'relative overflow-hidden w-full')">
    <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0">
      <source :src="element.getProp('videoUrl')" type="video/mp4" />
    </video>
    <div class="relative z-10 w-full h-full">
      <BaseRecursiveElement v-for="child in element.children" :key="child.id" :element="child" />
    </div>
  </div>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { computed } from 'vue';

const { element } = defineProps<{
  element: ZioraElement;
}>();

const { customClassNames, animationClass, className } = useElement(element);

const galleryImages = computed<string[]>(() => {
  const images = element.getProp('images');
  if (Array.isArray(images)) return images;
  if (typeof images === 'string') return images.split('\n').map(x => x.trim()).filter(Boolean);
  return [];
});

const elementAttributes = computed(() => {
    const attrs: Record<string, any> = {};
    const customAttrs = element.getProp('custom.attributes') || {};
    return { ...attrs, ...customAttrs };
});

const defaultSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-neutral-500"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>`;
</script>

<style scoped></style>
