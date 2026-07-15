<template>
    <BaseModalPage
        v-model="showModal"
        title="New Testimonial"
    >
        <form
            class="space-y-4"
            @submit.prevent="submit"
        >
            <UFormField
                :error="form.errors.avatar"
                label="Pick user avatar"
            >
                <div class="flex items-center gap-2">
                    <UAvatar
                        size="3xl"
                        :src="form.avatar"
                    />
                    <UButton
                        size="sm"
                        class="cursor-pointer"
                        variant="outline"
                    >
                        <ModalLink
                            :href="route('admin.folders.index')"
                            @selected="form.avatar = $event"
                        >
                            <p>Pick image</p>
                        </ModalLink>
                    </UButton>
                </div>
            </UFormField>
            <UFormField
                required
                :error="form.errors.name"
                label="Name"
            >
                <UInput
                    v-model="form.name"
                    placeholder="Name"
                    class="w-full"
                />
            </UFormField>
            <UFormField
                :error="form.errors.title"
                label="Title"
            >
                <UInput
                    v-model="form.title"
                    placeholder="eg CTO, Google Inc."
                    class="w-full"
                />
            </UFormField>
            <UFormField
                required
                :error="form.errors.comment"
                label="Comment"
            >
                <UTextarea
                    v-model="form.comment"
                    class="w-full"
                />
            </UFormField>
            <div class="flex justify-end">
                <UButton
                    @click.prevent="submit"
                    type="submit"
                    :loading="form.processing"
                >
                    Create
                </UButton>
            </div>
        </form>
    </BaseModalPage>
</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';
import { ModalLink } from '@inertiaui/modal-vue';


const showModal = ref<boolean>(true)

const form = useForm({
    name: '',
    avatar: '',
    comment: '',
    title: '',
});


function submit() {
    form.post(route('admin.testimonials.store'), {
        onSuccess: () => {
            form.reset();
            showModal.value = false
        }
    });
}
</script>

<style scoped></style>
