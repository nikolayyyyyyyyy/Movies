<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
const user = usePage().props.auth.user;

const profilePicture = ref(null);
const form = useForm({
    profile_picture: null,
});

const handleProfilePictureChange = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        profilePicture.value = file;
        form.profile_picture = file;
        form.errors.profile_picture = null;
    }
};

const updateProfilePicture = () => {
    form.put(route('profile.update-profile-picture'), {
        preserveState: false,
        onSuccess: () => {
            profilePicture.value = null;
            form.reset('profile_picture');
        }
    });
};
</script>

<template>
    <section>
        <form @submit.prevent="updateProfilePicture" id="update-profile-picture-form"
            class="flex justify-between items-center">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Profile Picture
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Update your account's profile picture.
                </p>
            </header>

            <div class="flex flex-col items-center gap-4">
                <img :src="user.profile_picture ?? '/avatar.png'" alt="Profile Picture"
                    class="w-20 h-20 md:w-[120px] md:h-[120px] object-cover rounded-md">

                <input @change="handleProfilePictureChange" type="file" id="profile_picture" name="profile_picture"
                    accept="image/*" class="hidden">

                <label for="profile_picture"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer">
                    <span class="ml-2">Choose Profile Picture</span>
                </label>

                <span class="text-sm text-gray-600 py-2 bg-gray-100 rounded-md px-4" v-if="form.profile_picture">image
                    selected</span>
            </div>
        </form>

        <div class="flex items-center gap-4">
            <PrimaryButton form="update-profile-picture-form" type="submit" :disabled="form.processing">
                <span v-if="form.processing">Saving...</span>

                <span v-else>Save</span>
            </PrimaryButton>

            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                    Saved.
                </p>
            </Transition>
        </div>

        <InputError v-if="form.errors.profile_picture" :message="form.errors.profile_picture" class="mt-2" />
    </section>
</template>
