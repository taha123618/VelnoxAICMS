<template>
    <UApp>
        <HeadlessModal
            ref="modalRef"
            v-slot="{ close, emit, reload }">
            <USlideover
                :class="{ dark: isDark }"
                :ui="{
                    body: bodyClass,
                    content: size,
                    title: 'font-bold text-2xl',
                }"
                v-if="isSlideOver"
                :dismissible="false"
                v-model:open="showModal"
                :title="title"
                :description="description"
                @after:leave="
                    close();
                    emits('close');
                ">
                <template #body>
                    <slot
                        :emit="emit"
                        :reload="reload"
                        :close="close" />
                </template>
                <template
                    #footer
                    v-if="slots['footer']">
                    <slot
                        name="footer"
                        :emit="emit"
                        :reload="reload"
                        :close="close" />
                </template>
            </USlideover>
            <UModal
                v-else
                :class="{ dark: isDark }"
                :ui="{
                    content: size,
                    title: 'font-bold text-xl',
                }"
                :dismissible="false"
                v-model:open="showModal"
                :title="title"
                :description="description"
                @after:leave="close()">
                <template #body>
                    <slot
                        :emit="emit"
                        :reload="reload"
                        :close="close" />
                </template>
            </UModal>
        </HeadlessModal>
    </UApp>
</template>

<script setup lang="ts">
import { HeadlessModal } from '@inertiaui/modal-vue';

const {
    title,
    description,
    isSlideOver = false,
    isDark = false,
    size = 'max-w-sm',
    bodyClass = '',
} = defineProps<{
    title?: string | undefined;
    description?: string | undefined;
    isSlideOver?: boolean;
    isDark?: boolean;
    size?: string;
    bodyClass?: string;
}>();
const emits = defineEmits(['close']);
const modalRef = ref<any>(null);
const showModal = defineModel<boolean>();
const slots = useSlots();

defineExpose({
    reload: (...args: any[]) => modalRef.value?.reload(...args),
    emit: (name: string, payload: any = null) =>
        modalRef.value?.emit(name, payload),
    close: () => {
        showModal.value = false;
        modalRef.value?.close();
    },
});
</script>

<style scoped></style>
