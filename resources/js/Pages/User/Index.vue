<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    users: {
        required: true
    },
    roles: {
        required: true
    }
});

const showAddUsersModal = ref(false);
const showDeleteUserModal = ref(false);

const userToDelete = ref({});

const createUserForm = useForm({
    name: '',
    email: '',
    password: '',
    confirm_password: '',
    role_id: ''
});

const submitNewUser = () => {
    createUserForm.post(route('users.store'), {
        preserveScroll: true,
        onSuccess: () => {
            createUserForm.reset();
            showAddUsersModal.value = false;
        }
    })
}

const handleDeleteUser = () => {
    router.delete(route('users.destroy', { user: userToDelete.value.name }), {}, {
        preserveScroll: true,
        onSuccess: () => {
            userToDelete.value = {};
        }
    })
    showDeleteUserModal.value = false;
}

const handleEditUser = (user) => {
    router.get(route('profile.edit-user', { user: user.name }));
}

const userToSearch = ref('');
const filterUsers = () => {
    router.get(route('users.index'), {
        email: userToSearch.value
    });
}
</script>

<template>

    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Users
            </h2>
        </template>

        <template #addUsers>
            <div class="ml-auto flex justify-end">
                <PrimaryButton @click="showAddUsersModal = true">
                    Add new users
                </PrimaryButton>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col gap-6">
                <div class="flex gap-4 items-center justify-center">
                    <InputLabel value="Search user by email" />

                    <TextInput class="w-1/2" v-model="userToSearch" />

                    <SecondaryButton @click="filterUsers">Search</SecondaryButton>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 sm:gap-6">
                    <div v-for="user in users.data" :key="user.id"
                        class="flex flex-col p-2 border-2 border-solid shadow-lg rounded-md transition-transform duration-200 ease-out hover:scale-105">
                        <div class="pb-4">
                            <img :src="user.profile_picture ?? '/images/avatar.png'" alt="Profile Picture"
                                class="h-[300px] w-full object-cover">
                        </div>

                        <div class="flex flex-col gap-2 mt-auto">
                            <InputLabel>Name: {{ user.name }}</InputLabel>
                            <InputLabel>Email: {{ user.email }}</InputLabel>
                            <InputLabel>Role: {{ user.role?.name }}</InputLabel>
                        </div>

                        <div class="mt-3 flex gap-2">
                            <DangerButton @click="showDeleteUserModal = true, userToDelete = user"
                                class="flex-1 flex items-center justify-center">
                                Delete
                            </DangerButton>

                            <SecondaryButton @click="handleEditUser(user)"
                                class="flex-1 flex items-center justify-center">
                                Edit
                            </SecondaryButton>
                        </div>
                    </div>
                </div>

                <nav v-if="users.links.length > 0 && users.data.length > 0"
                    class="mt-8 flex flex-wrap items-center justify-center gap-2" aria-label="Pagination">
                    <template v-for="(link, index) in users.links" :key="index">
                        <Link v-if="link.url" :href="link.url"
                            class="flex items-center justify-center min-w-[2.25rem] px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 shadow-sm transition-colors hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
                            :class="{ 'bg-indigo-600 border-indigo-600 hover:bg-indigo-700 hover:border-indigo-700': link.active }"
                            v-html="link.label" />
                        <span v-else
                            class="min-w-[2.25rem] px-3 py-2 text-sm font-medium rounded-lg border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed"
                            v-html="link.label" />
                    </template>
                </nav>

                <InputLabel v-else value="No Users/User found." class="text-center" />
            </div>
        </div>

        <DialogModal :show="showAddUsersModal"
            @close="showAddUsersModal = false, createUserForm.reset(), createUserForm.clearErrors()">
            <template #title>
                Create User
            </template>
            <template #content>
                <div class="flex flex-col gap-2">
                    <div class="flex flex-col">
                        <InputLabel value="Name" />
                        <TextInput v-model="createUserForm.name" placeholder="Enter name" />
                        <InputError :message="createUserForm.errors.name" />
                    </div>

                    <div class="flex flex-col">
                        <InputLabel value="Email" />
                        <TextInput v-model="createUserForm.email" placeholder="Enter email" />
                        <InputError :message="createUserForm.errors.email" />
                    </div>

                    <div class="flex flex-col">
                        <InputLabel value="Password" />
                        <TextInput v-model="createUserForm.password" placeholder="Enter password" type="password" />
                        <InputError :message="createUserForm.errors.password" />
                    </div>

                    <div class="flex flex-col">
                        <InputLabel value="Confirm password" />
                        <TextInput v-model="createUserForm.confirm_password" placeholder="Repeat password" type="password" />
                        <InputError :message="createUserForm.errors.confirm_password" />
                    </div>

                    <div class="flex flex-col">
                        <InputLabel for="select-role" value="Role" />
                        <select id="select-role" v-model="createUserForm.role_id"
                            class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="" disabled>Select role</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">
                                {{ role.name }}
                            </option>
                        </select>
                        <InputError :message="createUserForm.errors.role_id" />
                    </div>
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click="showAddUsersModal = false, createUserForm.reset(), createUserForm.clearErrors()">
                    Cancel</PrimaryButton>
                <SecondaryButton class="ms-3" @click="submitNewUser">Submit</SecondaryButton>
            </template>
        </DialogModal>

        <DialogModal :show="showDeleteUserModal" @close="showDeleteUserModal = false">
            <template #title>
                Delete User
            </template>

            <template #content>
                Are you sure you want to delete this user???
            </template>

            <template #footer>
                <div class="flex gap-2">
                    <DangerButton @click="handleDeleteUser">Delete</DangerButton>

                    <PrimaryButton @click="showDeleteUserModal = false">Cancel</PrimaryButton>
                </div>
            </template>
        </DialogModal>
    </AuthenticatedLayout>
</template>