<template>
    <UFormField
        :label="label"
        :ui="{
            label: 'block font-normal ziora-label',
        }"
        class="flex flex-col gap-1 text-xs"
    >
        <template #label>
            <div class="flex items-center gap-0.5">
                <div>{{ label }}</div>
                <BaseTooltip
                    v-if="hasChanged"
                    content="Clear hover style"
                >
                    <UButton
                        @click.stop="emit('clear:hover')"
                        size="sm"
                        color="error"
                        variant="link"
                        icon="ph:x"
                    />
                </BaseTooltip>
            </div>
        </template>

        <component
            :is="!!value ? 'div' : UButton"
            variant="soft"
            :class="{ 'bg-primary/10 hover:bg-primary/15': !!value }"
            class="group relative  flex h-20 w-full flex-col items-center justify-center overflow-hidden"
        >
            <template v-if="!!value">
                <img
                    :src="value"
                    class="size-full object-contain"
                />

                <BaseTooltip content="Remove">
                    <UButton
                        @click.stop="emit('remove')"
                        icon="ph:trash"
                        size="xs"
                        variant="solid"
                        class="absolute z-50 top-0 right-0 m-1 cursor-pointer text-red-600"
                        color="neutral"
                    />
                </BaseTooltip>
            </template>

            <ModalLink
                class="absolute flex items-center justify-center cursor-pointer inset-0"
                :href="route('admin.folders.index')"
                @selected="emit('change', $event)"
            >
                <UIcon
                    name="ph:plus-circle-fill"
                    :class="!!value ? 'opacity-20' : 'opacity-60'"
                    class="size-8 group-hover:opacity-60"
                />
            </ModalLink>
            <div
                class="bg-builder-body border-inverted/20 absolute inset-x-0 -bottom-10 border-t py-1 text-xs text-white transition-all duration-300 group-hover:bottom-0">
                Choose image
            </div>
        </component>
    </UFormField>
</template>

<script setup lang="ts">
import { ModalLink } from '@inertiaui/modal-vue';
import UButton from '@nuxt/ui/runtime/components/Button.vue';
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';

interface Props {
    value?: string;
    label?: string;
    disabled?: boolean;
    hasChanged?: boolean;
}

const { hasChanged = false } = defineProps<Props>();

const emit = defineEmits(['change', 'clear:hover', 'remove']);

</script>

<style lang="scss" scoped></style>
