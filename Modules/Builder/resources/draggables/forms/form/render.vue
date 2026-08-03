<template>
    <form
        @submit.prevent="submit"
        :class="cn(className, customClassNames, animationClass)"
        class="builder-element !relative flex flex-col gap-2"
    >
        <template
            v-for="child in element.children"
            :key="child.id"
        >
            <BaseRecursiveElement
                v-if="child.type == 'forminput'"
                :element="child"
                :error="form.errors[child.getProp('name')]"
                v-model="form[child.getProp('name')]"
            />

            <BaseRecursiveElement
                v-else
                :element="child"
                v-model="form[child.getProp('name')]"
            />
        </template>

        <div
            class="flex items-center py-4"
            :class="{
                'justify-end': element.getProp('button.align') == 'right',
                'justify-center': element.getProp('button.align') == 'center',
                'justify-start': element.getProp('button.align') == 'left',
            }"
        >
            <UButton
                :ui="{
                    base: 'submit-button rounded-nonex'
                }"
                type="submit"
                :disabled="form.processing"
                :loading="form.processing"
                :block="element.getProp('button.align') == 'full'"
                :label="element.getProp('button.label')"
                :size="element.getProp('button.size')"
                :variant="element.getProp('button.variant')"
                :color="element.getProp('button.color')"
            />
        </div>
    </form>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { cn } from '@modules/Builder/resources/scripts/utils';
import { useElement } from '@modules/Builder/resources/scripts/use-element';
import { useFormStyles } from '@modules/Builder/resources/draggables/forms/form/use-form-styles';
import { useHead } from '@unhead/vue';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const { customClassNames, animationClass, className } = useElement(element);

const { getStyles } = useFormStyles(element)

useHead({
    style: [
        {
            textContent: computed(() => getStyles()),
            id: element.id,
        },
    ],
});

const form = useForm<Record<any, any>>({
    ...element.getFormFields()
})

const toast = useToast()

function submit() {
    const endpoint = element.getProp('endpoint')
    console.log(endpoint)
    if (!endpoint) {
        toast.add({
            title: 'Form not configured',
            description: 'This form is not yet ready to accept submissions',
            color: 'error'
        })
    }
    form.post(endpoint, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()

            toast.add({
                description: element.getProp('successMessage'),
                color: 'success'
            })
            if (element.getProp('afterSubmit') == 'redirect' && !!element.getProp('redirectTo')) {
                window.location.href = element.getProp('redirectTo')
            }




        },
        onError: () => {
            toast.add({
                description: 'Oops...somthing is not quite correct.',
                color: 'error'
            })
        }
    })

}

</script>

<style scoped></style>
