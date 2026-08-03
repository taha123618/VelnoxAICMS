<template>
    <div
        :id="element.id"
        :class="cn(className, customClassNames, animationClass)"
    >
        <div
            class="size-full aspect-square"
            ref="youtubeRef"
        />
    </div>
</template>

<script setup lang="ts">
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { PlayerVars, usePlayer } from '@vue-youtube/core';
import { cn } from '@modules/Builder/resources/scripts/utils';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();
const { customClassNames, animationClass, className } =
    useElement(element);
//OgdH-MMuEUo
const videoId = computed(() => element.getProp('videoId'));
const youtubeRef = ref();

const playerOptions = computed<PlayerVars>(() => {
    return {
        mute: element.getProp('mute') ? 1 : 0,
        autoplay: element.getProp('autoplay') ? 1 : 0,
        controls: element.getProp('controls') ? 1 : 0,
        loop: element.getProp('loop') ? 1 : 0,
        playsinline: 1, // Recommended for iOS
        modestbranding: 1, // Hide YouTube logo
        fs: element.getProp('fullscreen') ? 1 : 0, // fullscreen
        rel: element.getProp('relatedVideos') ? 1 : 0 // related videos
    }
})


const { onReady } = usePlayer(videoId, youtubeRef, {
    cookie: false,
    width: '100%',
    height: '100%',
    playerVars: playerOptions.value,
});

onReady((event) => {
    event.target.playVideo();
});

</script>

<style scoped></style>
