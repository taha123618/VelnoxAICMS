<template>
    <div class="grid gap-2 p-2">
        <Stencil v-for="block in blocks"
            :key="block.id" :block="block" />
    </div>
</template>

<script setup lang="ts">
import { blocks } from '@modules/Builder/resources/blocks';
import Stencil from '@modules/Builder/resources/blocks/Stencil.vue';

// onBeforeMount(() => {
//     console.log(blocks)

// })
</script>

<style scoped></style>
