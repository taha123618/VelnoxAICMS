<template>
    <UCard
        :ui="{
            body: 'p-0 sm:p-0',
            footer: 'flex items-center justify-between',
        }">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <slot name="filters" />
                </div>
                <div>
                    <slot name="actions" />
                </div>
            </div>
        </template>
        <slot />

        <template #footer>
            <slot name="footer" />
        </template>
    </UCard>
</template>

<script setup lang="ts"></script>

<style scoped></style>
