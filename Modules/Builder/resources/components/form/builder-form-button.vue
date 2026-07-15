<template>
    <UButton
        :color="color"
        :type="type"
        :class="{ 'w-full': block }"
        :variant="variant"
        :disabled="disabled || loading"
        :loading="loading"
    >
        <slot />
    </UButton>
</template>
<script lang="ts" setup>
withDefaults(
    defineProps<{
        disabled?: boolean | undefined;
        loading?: boolean | undefined;
        block?: boolean | undefined;
        type?: 'submit' | 'button' | undefined;
        color?:
        | 'neutral'
        | 'error'
        | 'info'
        | 'primary'
        | 'secondary'
        | 'success'
        | 'warning'
        | undefined;
        variant?: 'solid' | 'outline' | 'soft' | 'subtle' | 'ghost' | 'link';
    }>(),
    {
        loading: false,
        disabled: false,
        color: 'neutral',
        block: false,
        type: 'submit',
        variant: 'outline',
    },
);
</script>


<style scoped></style>
