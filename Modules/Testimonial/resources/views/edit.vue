<template>
    <BaseModalPage
        v-model="showModal"
        title="Update testimonial"
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
                        icon="ph:user"
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
                    <UButton
                        size="sm"
                        class="cursor-pointer"
                        variant="link"
                        @click="form.avatar = ''"
                        label="Remove image"
                    />
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
                label="Designation"
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
                    Update
                </UButton>
            </div>

        </form>
    </BaseModalPage>

</template>

<script setup lang="ts">
import BaseModalPage from '@/components/BaseModalPage.vue';
import { ModalLink } from '@inertiaui/modal-vue';

const { testimonial } = defineProps<{
    testimonial: Modules.Testimonial.Data.TestimonialData;
}>();

const showModal = ref<boolean>(true)

const form = useForm({
    name: testimonial.name,
    avatar: testimonial.avatar || undefined,
    comment: testimonial.comment,
    title: testimonial.title,
});


function submit() {
    form.put(route('admin.testimonials.update', [testimonial.id]), {
        onSuccess: () => {
            form.reset();
            showModal.value = false
        }
    });
}
</script>

<style scoped></style>
