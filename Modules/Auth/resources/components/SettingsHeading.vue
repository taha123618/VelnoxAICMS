<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
    isSmall?: boolean
}

withDefaults(defineProps<Props>(), {
    isSmall: false
});
</script>

<template>
    <header v-if="isSmall">
        <h3 class="mb-0.5 text-base font-medium">{{ title }}</h3>
        <p
            v-if="description"
            class="text-sm text-muted-foreground"
        >
            {{ description }}
        </p>
    </header>
    <div
        v-else
        class="mb-8 space-y-0.5"
    >
        <h2 class="text-xl font-semibold tracking-tight">{{ title }}</h2>
        <p
            v-if="description"
            class="text-sm text-muted-foreground"
        >
            {{ description }}
        </p>
    </div>
</template>