<template>
    <UModal
        class="dark"
        v-model:open="model"
        @close="emit('close')">
        <template #content>
            <div class="space-y-4 p-6">
                <h1 class="mb-2 font-bold dark:text-white">
                    {{ title }}
                </h1>
                <p class="dark:text-white">{{ description }}</p>
                <div class="flex items-center justify-end gap-2">
                    <UButton
                        @click="emit('cancel')"
                        label="Cancel"
                        color="error"
                        variant="soft" />
                    <UButton
                        @click="emit('confirm')"
                        label="Yes proceed"
                        color="primary"
                        variant="solid" />
                </div>
            </div>
        </template>
    </UModal>
</template>

<script setup lang="ts">
const {
    title = 'Confirmation required',
    description = 'Are you sure you want to do this?',
} = defineProps<{
    title?: string;
    description?: string;
}>();

const model = defineModel<boolean>();

const emit = defineEmits(['cancel', 'confirm', 'close', 'cancel']);
</script>

<style scoped></style>
