<template>
    <BaseModalPage
        :is-slide-over="true"
        size="max-w-md"
        v-model="showModal"
        :title="`${role.label} Permissions`"
    >
        <div class="grid grid-cols-2 gap-2">
            <PermissionGroupForm
                v-for="(group, index) in permissions"
                :key="index"
                v-model="form.permissions"
                :group="group"
            />
        </div>
        <template #footer>
            <div class="flex w-full items-center justify-end">
                <UButton
                    @click="submit"
                    :loading="form.processing"
                    :disabled="!form.isDirty || Boolean((role as any)?.can?.super)"
                    label="Save changes"
                />
            </div>
        </template>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';
import PermissionGroupForm from '@modules/Auth/resources/components/PermissionGroupForm.vue';

const showModal = ref<boolean>(true)

const { role, permissions } = defineProps<{
    role: Modules.Auth.Data.RoleData;
    permissions: Record<string, any>[],
}>()

const form = useForm({
    permissions: role.permissions
})

function submit() {
    form.put(route('admin.roles.permissions', [role.id]), {
        onSuccess: () => {
            showModal.value = false
        }
    })
}
</script>

<style scoped></style>