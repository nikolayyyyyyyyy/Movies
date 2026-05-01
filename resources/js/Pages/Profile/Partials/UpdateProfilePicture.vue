<script setup>
import { router, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { computed, ref } from 'vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    targetUser: {
        type: Object,
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
});
const user = computed(() => props.targetUser);
const form = useForm({
    profile_picture: null,
});

const preview = ref(user.value.profile_picture != null ?
    user.value.profile_picture :
    '/images/avatar.png');

const onFileChange = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        form.profile_picture = file;
        form.errors.profile_picture = null;

        preview.value = URL.createObjectURL(file);
    }
};

const updateProfilePicture = () => {
    form.put(props.profilePictureUpdateRoute, {
        onSuccess: () => {
            form.reset();
        }
    });
};

const removeProfilePicture = () => {
    router.put(props.profilePictureDestroyRoute, {}, {
        preserveScroll: true,
        preserveState: false
    })
}
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
                <div class="relative">
                    <img :src="preview" alt="Profile Picture"
                        class="w-40 h-40 md:w-[120px] md:h-[120px] object-cover rounded-md">
                    <span v-if="user.profile_picture" @click="removeProfilePicture"
                        class="absolute top-3 right-3 bg-red-600 px-2 rounded-md cursor-pointer text-white transition-transform duration-300 ease-out hover:scale-110">
                        X
                    </span>
                </div>

                <input @change="onFileChange" type="file" id="profile_picture" name="profile_picture" accept="image/*"
                    class="hidden">

                <label for="profile_picture"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer">
                    <span class="ml-2">Choose Profile Picture</span>
                </label>
            </div>
        </form>

        <div class="flex items-center gap-4">
            <PrimaryButton form="update-profile-picture-form" type="submit" :disabled="form.processing">
                Save
            </PrimaryButton>

            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                    Saved.
                </p>
            </Transition>
        </div>

        <InputError v-if="form.errors.profile_picture" :message="form.errors.profile_picture" class="mt-2" />
    </section>
</template>
