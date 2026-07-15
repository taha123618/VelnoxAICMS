<template>
    <BaseModalPage
        v-model="showModal"
        is-slide-over
        title="Contact details"
    >
        <div class="flow-root">
            <dl class="-my-3 divide-y divide-gray-200 text-sm">
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-neutral-900">Sender</dt>
                    <dd class="text-neutral-700 sm:col-span-2">{{ contact.name }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-neutral-900">Email</dt>
                    <dd class="text-neutral-700 sm:col-span-2">{{ contact.email }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-neutral-900">Subject</dt>
                    <dd class="text-neutral-700 sm:col-span-2">
                        {{ contact.subject }}
                    </dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-neutral-900">Sent at</dt>
                    <dd class="text-neutral-700 sm:col-span-2">
                        {{ contact.created_at }}
                    </dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-neutral-900">Message</dt>
                    <dd class="text-neutral-700 sm:col-span-2">
                        {{ contact.body }}
                    </dd>
                </div>
            </dl>
        </div>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';

const { contact } = defineProps<{
    contact: Modules.Contacts.Data.ContactData
}>()

const showModal = ref<boolean>(true)
</script>

<style scoped></style>