<template>
    <HeadlessModal
        ref="modalRef"
        v-slot="{ close, emit }"
        :show="showModal"
    >
        <UModal
            class="dark"
            v-model:open="showModal"
            :ui="{
                content: 'max-w-md',
                title: 'font-bold text-xl',
            }"
            :dismissible="false"
            description="Select link type to insert"
            title="Pick a link"
        >
            <template #body>
                <div class="space-y-4">
                    <BuilderSelect
                        label="Link type"
                        label-position="left"
                        option-is-object
                        :options="linkTypes"
                        @change="form.linkType = $event"
                        :value="form.linkType"
                    />
                    <template v-if="form.linkType == 'external'">
                        <BuilderInput
                            label-position="left"
                            placeholder="Paste url here"
                            :value="form.href"
                            @change="form.href = $event"
                            label="URL"
                        />
                    </template>
                    <template v-else-if="form.linkType == 'page'">
                        <BuilderSelect
                            label-position="left"
                            label="Select page"
                            option-is-object
                            :options="pages"
                            @change="form.href = $event"
                            :value="form.href"
                        />
                    </template>
                    <template v-else-if="form.linkType == 'post'">
                        <BuilderSelect
                            label-position="left"
                            label="Select post"
                            option-is-object
                            :options="posts"
                            @change="form.href = $event"
                            :value="form.href"
                        />
                    </template>

                    <div class="flex justify-end items-center gap-2">
                        <UButton
                            @click="close();showModal=false;"
                            variant="soft"
                            color="error"
                            label="Cancel"
                        />
                        <UButton
                            :disabled="!form.isDirty"
                            label="Insert link"
                            @click="emit('insert', form.data()); showModal = false; close()"
                        />
                    </div>
                </div>
            </template>
        </UModal>
    </HeadlessModal>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import BuilderInput from '@modules/Builder/resources/components/form/builder-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import { HeadlessModal } from '@inertiaui/modal-vue'

const props = defineProps<{
    payload: Record<string, any>;
    pages: Record<string, any>[];
    posts: Record<string, any>[];
}>();

const modalRef = ref(null);
const showModal = ref(true)

const form = useForm({
    linkType: props.payload.linkType,
    href: props.payload.href
})

const linkTypes = [
    { value: 'page', label: 'Page' },
    { value: 'post', label: 'Post' },
    { value: 'external', label: 'External url' },
];


</script>

<style scoped></style>
