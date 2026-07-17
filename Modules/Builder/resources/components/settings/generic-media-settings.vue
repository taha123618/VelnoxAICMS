<template>
  <div class="space-y-4">
    <!-- Gallery Settings -->
    <div v-if="element.type === 'gallery'" class="space-y-3">
      <BuilderTextarea
        label="Image URLs (One per line)"
        placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg"
        :value="imagesText"
        @change="updateGalleryImages"
      />
    </div>

    <!-- Audio Settings -->
    <div v-else-if="element.type === 'audio'" class="space-y-3">
      <BuilderInput
        label="Audio Title"
        placeholder="Song / Audio track title"
        :value="element.getProp('title') || ''"
        @change="element.setProps('title', $event)"
      />
      <BuilderInput
        label="Audio Source URL"
        placeholder="https://example.com/audio.mp3"
        :value="element.getProp('src') || ''"
        @change="element.setProps('src', $event)"
      />
    </div>

    <!-- Icon Settings -->
    <div v-else-if="element.type === 'icon'" class="space-y-3">
      <BuilderInput
        label="Icon Name (Iconify/Ph)"
        placeholder="ph:cube-bold"
        :value="element.props.icon || 'ph:cube-bold'"
        @change="element.setProps('icon', $event)"
      />
      <BuilderNumberInput
        label="Icon Size (px)"
        :value="Number(element.props.iconSize || 24)"
        @change="element.setProps('iconSize', $event)"
      />
      <BuilderColorInput
        label="Icon Color"
        :value="element.props.iconColor || '#000000'"
        @change="element.setProps('iconColor', $event)"
      />
    </div>

    <!-- Lottie Settings -->
    <div v-else-if="element.type === 'lottie'" class="space-y-3">
      <BuilderInput
        label="Lottie / Iframe Embed URL"
        placeholder="https://embed.lottiefiles.com/animation/..."
        :value="element.getProp('src') || ''"
        @change="element.setProps('src', $event)"
      />
    </div>

    <!-- SVG Settings -->
    <div v-else-if="element.type === 'svg'" class="space-y-3">
      <BuilderTextarea
        label="Raw SVG Code"
        placeholder="<svg>...</svg>"
        :value="element.props.svgCode || ''"
        @change="element.setProps('svgCode', $event)"
      />
    </div>

    <!-- Background Video Settings -->
    <div v-else-if="element.type === 'bgvideo'" class="space-y-3">
      <BuilderInput
        label="Background Video URL (mp4)"
        placeholder="https://example.com/video.mp4"
        :value="element.getProp('videoUrl') || ''"
        @change="element.setProps('videoUrl', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderTextarea from '@modules/Builder/resources/components/form/builder-textarea.vue';
import BuilderNumberInput from '@modules/Builder/resources/components/form/builder-number-input.vue';
import BuilderColorInput from '@modules/Builder/resources/components/form/builder-color-input.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { computed } from 'vue';

const { element } = defineProps<{ element: ZioraElement }>();

const imagesText = computed(() => {
  const raw = element.getProp('images');
  if (Array.isArray(raw)) return raw.join('\n');
  if (typeof raw === 'string') return raw;
  return '';
});

function updateGalleryImages(value: string) {
  const images = value.split('\n').map(x => x.trim()).filter(Boolean);
  element.setProps('images', images);
}
</script>
