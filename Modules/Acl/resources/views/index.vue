<template>
  <AdminLayout>
    <div class="px-4 sm:px-6 lg:px-8 py-8">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-xl font-semibold text-white">Roles & Permissions</h1>
          <p class="mt-2 text-sm text-gray-400">
            A list of all roles and the permissions assigned to them.
          </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
          <button
            @click="openCreateModal"
            type="button"
            class="block rounded-md bg-indigo-500 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
          >
            Create role
          </button>
        </div>
      </div>

      <!-- Roles Table -->
      <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-white/5 sm:rounded-lg">
              <table class="min-w-full divide-y divide-white/5">
                <thead class="bg-white/5">
                  <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Role Name</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Permissions</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                      <span class="sr-only">Actions</span>
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-white/5 bg-gray-900">
                  <tr v-for="role in roles" :key="role.id">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                      {{ role.name }}
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-400">
                      <div class="flex flex-wrap gap-2">
                        <span v-for="perm in role.permissions" :key="perm.id" class="inline-flex items-center rounded-md bg-indigo-500/10 px-2 py-1 text-xs font-medium text-indigo-400 ring-1 ring-inset ring-indigo-500/20">
                          {{ perm.name }}
                        </span>
                      </div>
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                      <button @click="openEditModal(role)" class="text-indigo-400 hover:text-indigo-300 mr-4">Edit</button>
                      <button @click="deleteRole(role)" class="text-red-400 hover:text-red-300">Delete</button>
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
    <div v-if="isModalOpen" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="fixed inset-0 bg-gray-900/75 transition-opacity"></div>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div class="relative transform overflow-hidden rounded-lg bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            <div>
              <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-base font-semibold leading-6 text-white" id="modal-title">
                  {{ editingRole ? 'Edit Role' : 'Create Role' }}
                </h3>
                <div class="mt-2 text-left">
                  <form @submit.prevent="submitForm">
                    <div class="space-y-4">
                      <div>
                        <label for="name" class="block text-sm font-medium text-white">Role Name</label>
                        <input type="text" v-model="form.name" id="name" class="mt-1 block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" required />
                      </div>
                      
                      <div>
                        <label class="block text-sm font-medium text-white mb-2">Assign Permissions</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                          <div v-for="permission in permissions" :key="permission.id" class="flex items-center">
                            <input 
                              type="checkbox" 
                              :id="'perm_' + permission.id"
                              :value="permission.name"
                              v-model="form.permissions"
                              class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                            />
                            <label :for="'perm_' + permission.id" class="ml-2 block text-sm text-gray-300">
                              {{ permission.name }}
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                      <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2">
                        {{ editingRole ? 'Update' : 'Create' }}
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
  roles: Array,
  permissions: Array,
});

const isModalOpen = ref(false);
const editingRole = ref(null);

const form = useForm({
  name: '',
  permissions: [],
});

const openCreateModal = () => {
  editingRole.value = null;
  form.reset();
  form.permissions = [];
  isModalOpen.value = true;
};

const openEditModal = (roleToEdit) => {
  editingRole.value = roleToEdit;
  form.name = roleToEdit.name;
  form.permissions = roleToEdit.permissions.map(p => p.name);
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  editingRole.value = null;
  form.reset();
};

const submitForm = () => {
  if (editingRole.value) {
    form.put(route('admin.acl.roles.update', editingRole.value.id), {
      onSuccess: () => closeModal(),
    });
  } else {
    form.post(route('admin.acl.roles.store'), {
      onSuccess: () => closeModal(),
    });
  }
};

const deleteRole = (roleToDelete) => {
  if (confirm('Are you sure you want to delete this role?')) {
    router.delete(route('admin.acl.roles.destroy', roleToDelete.id));
  }
};
</script>