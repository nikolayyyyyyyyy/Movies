<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateProfilePicture from './Partials/UpdateProfilePicture.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    targetUser: {
        type: Object,
        required: true,
    },
    isOwnProfile: {
        type: Boolean,
        required: true,
    },
    profileUpdateRoute: {
        type: String,
        required: true,
    },
    profilePictureUpdateRoute: {
        type: String,
        required: true,
    },
    profilePictureDestroyRoute: {
        type: String,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    },
    canManageRole: {
        type: Boolean,
        required: true,
    },
});
</script>

<template>

    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <UpdateProfilePicture :target-user="targetUser" :profile-picture-update-route="profilePictureUpdateRoute"
                        :profile-picture-destroy-route="profilePictureDestroyRoute" />
                </div>

                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"
                        :target-user="targetUser" :profile-update-route="profileUpdateRoute"
                        :roles="roles" :can-manage-role="canManageRole"
                        class="max-w-xl" />
                </div>

                <div v-if="isOwnProfile" class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div v-if="isOwnProfile" class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
