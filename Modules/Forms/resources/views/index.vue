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
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
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
                  <tr v-for="form in forms" :key="form.id">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                      {{ form.name }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ form.slug }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                      <span v-if="form.is_active" class="inline-flex items-center rounded-md bg-green-500/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-500/20">Active</span>
                      <span v-else class="inline-flex items-center rounded-md bg-red-500/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-500/20">Inactive</span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ form.submissions_count }}</td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                      <button @click="openEditModal(form)" class="text-indigo-400 hover:text-indigo-300 mr-4">Edit</button>
                      <button @click="deleteForm(form)" class="text-red-400 hover:text-red-300">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="isCreateModalOpen" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="fixed inset-0 bg-gray-900/75 transition-opacity"></div>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div class="relative transform overflow-hidden rounded-lg bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            <div>
              <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-base font-semibold leading-6 text-white" id="modal-title">
                  {{ editingForm ? 'Edit Form' : 'Create Form' }}
                </h3>
                <div class="mt-2 text-left">
                  <form @submit.prevent="submitForm">
                    <div class="space-y-4">
                      <div>
                        <label for="name" class="block text-sm font-medium text-white">Name</label>
                        <input type="text" v-model="form.name" id="name" class="mt-1 block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                      </div>
                      <div>
                        <label for="slug" class="block text-sm font-medium text-white">Slug</label>
                        <input type="text" v-model="form.slug" id="slug" class="mt-1 block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                      </div>
                      <div>
                        <label for="success_message" class="block text-sm font-medium text-white">Success Message</label>
                        <input type="text" v-model="form.success_message" id="success_message" class="mt-1 block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                      </div>
                      <div>
                        <label for="redirect_url" class="block text-sm font-medium text-white">Redirect URL</label>
                        <input type="text" v-model="form.redirect_url" id="redirect_url" class="mt-1 block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" />
                      </div>
                      <div class="flex items-center">
                        <input id="is_active" v-model="form.is_active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                        <label for="is_active" class="ml-2 block text-sm text-gray-300">Active</label>
                      </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                      <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2">
                        {{ editingForm ? 'Update' : 'Create' }}
                      </button>
                      <button @click="closeModal" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0">
                        Cancel
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';

const props = defineProps({
  forms: Array,
});

const isCreateModalOpen = ref(false);
const editingForm = ref(null);

const form = useForm({
  name: '',
  slug: '',
  success_message: '',
  redirect_url: '',
  is_active: true,
});

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