<template>
  <AdminLayout>
    <div class="px-4 sm:px-6 lg:px-8 py-8">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-xl font-semibold text-white">Forms</h1>
          <p class="mt-2 text-sm text-gray-400">
            A list of all the forms in your account including their name, slug, and submission count.
          </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex gap-2">
          <button
            @click="showAiModal = true"
            type="button"
            class="block rounded-md bg-purple-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-purple-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-500 flex items-center gap-1.5"
          >
            Generate Form with AI
          </button>
          <button
            @click="isCreateModalOpen = true"
            type="button"
            class="block rounded-md bg-indigo-500 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
          >
            Create form
          </button>
        </div>
      </div>

      <!-- Forms Table -->
      <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-white/5 sm:rounded-lg">
              <table class="min-w-full divide-y divide-white/5">
                <thead class="bg-white/5">
                  <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Name</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Slug</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Status</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Submissions</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                      <span class="sr-only">Actions</span>
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-white/5 bg-gray-900">
                  <tr v-for="formItem in forms" :key="formItem.id">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                      {{ formItem.name }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ formItem.slug }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                      <span v-if="formItem.is_active" class="inline-flex items-center rounded-md bg-green-500/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-500/20">Active</span>
                      <span v-else class="inline-flex items-center rounded-md bg-red-500/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-500/20">Inactive</span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ formItem.submissions_count }}</td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                      <button @click="openEditModal(formItem)" class="text-indigo-400 hover:text-indigo-300 mr-4">Edit</button>
                      <button @click="deleteForm(formItem)" class="text-red-400 hover:text-red-300">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <AiPromptModal
      v-model:isOpen="showAiModal"
      title="AI Form Schema Architect"
      description="Generate form fields, validation rules, input types, and labels with AI."
      endpoint="/api/forms/generate-form"
      placeholder="Generate a customer feedback survey form with rating and comments..."
      :suggestions="['User registration & profile form', 'Event registration form with ticket options']"
      @success="handleAiSuccess"
    />
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import AiPromptModal from '@/components/AiPromptModal.vue';

const props = defineProps({
  forms: Array,
});

const isCreateModalOpen = ref(false);
const showAiModal = ref(false);
const editingForm = ref(null);

const form = useForm({
  name: '',
  slug: '',
  success_message: '',
  redirect_url: '',
  is_active: true,
});

function handleAiSuccess(result) {
  if (result.form || result) {
    router.reload();
  }
}

const openEditModal = (formToEdit) => {
  editingForm.value = formToEdit;
  form.name = formToEdit.name;
  form.slug = formToEdit.slug;
  form.success_message = formToEdit.success_message;
  form.redirect_url = formToEdit.redirect_url;
  form.is_active = formToEdit.is_active;
  isCreateModalOpen.value = true;
};

const closeModal = () => {
  isCreateModalOpen.value = false;
  editingForm.value = null;
  form.reset();
};

const submitForm = () => {
  if (editingForm.value) {
    form.put(route('admin.forms.update', editingForm.value.id), {
      onSuccess: () => closeModal(),
    });
  } else {
    form.post(route('admin.forms.store'), {
      onSuccess: () => closeModal(),
    });
  }
};

const deleteForm = (formToDelete) => {
  if (confirm('Are you sure you want to delete this form?')) {
    router.delete(route('admin.forms.destroy', formToDelete.id));
  }
};
</script>