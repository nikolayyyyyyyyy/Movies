<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    users: Object,
    roles: Object
});

const showAddUsersModal = ref(false);
const showDeleteUserModal = ref(false);

const userToDelete = ref({});

const createUserForm = useForm({
    name: '',
    email: '',
    password: '',
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
    router.delete(route('users.destroy', userToDelete.value.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            userToDelete.value = {};
        }
    })
    showDeleteUserModal.value = false;
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
            <PrimaryButton @click="showAddUsersModal = true">
                Add new users
            </PrimaryButton>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 sm:gap-6">
                    <div v-for="user in users" :key="user.id"
                        class="flex flex-col p-2 border-2 border-solid shadow-lg rounded-md transition-transform duration-200 ease-out hover:scale-105">
                        <img :src="user.profile_picture ?? '/images/avatar.png'" alt="Profile Picture"
                            class="object-cover w-30 h-30">

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

                            <SecondaryButton class="flex-1 flex items-center justify-center">
                                Edit
                            </SecondaryButton>
                        </div>
                    </div>
                </div>
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
                        <TextInput v-model="createUserForm.password" placeholder="Enter password" />
                        <InputError :message="createUserForm.errors.password" />
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