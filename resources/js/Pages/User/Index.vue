<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    users: Object,
});
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
            <PrimaryButton>
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
                            <p class="text-gray-500">Name: {{ user.name }}</p>
                            <p class="text-gray-500">Email: {{ user.email }}</p>
                            <p class="text-gray-500">Role: {{ user.role?.name }}</p>
                        </div>

                        <div class="mt-3 flex gap-2">
                            <DangerButton class="flex-1 flex items-center justify-center">Delete</DangerButton>
                            <SecondaryButton class="flex-1 flex items-center justify-center">Edit</SecondaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>