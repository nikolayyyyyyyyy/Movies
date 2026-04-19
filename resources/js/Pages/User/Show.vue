<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    user: {
        required: true
    }
});
</script>

<template>

    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ user.name }}
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto p-4 sm:px-6 sm:w-[600px] lg:px-8 flex flex-col gap-6 shadow-2xl rounded-lg">
                <div class="pb-4 relative">
                    <img :src="user.profile_picture ?? '/images/avatar.png'" alt="Profile Picture"
                        class="h-[400px] w-full object-cover rounded-md">
                    <DangerButton class="absolute top-3 right-3 transform-transition duration-500 hover:scale-110">X
                    </DangerButton>
                </div>

                <div class="flex flex-col gap-2 mt-auto">
                    <div>
                        <InputLabel value="Name" />
                        <TextInput class="w-full" v-model="user.name" />
                    </div>

                    <div>
                        <InputLabel value="Email" />
                        <TextInput class="w-full" v-model="user.email" />
                    </div>

                    <div>
                        <InputLabel value="Role" />
                        <TextInput class="w-full" v-model="user.name" />
                    </div>
                </div>

                <div class="mt-3 flex gap-2">
                    <SecondaryButton @click="handleEditUser(user)" class="flex-1 flex items-center justify-center">
                        Done
                    </SecondaryButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>