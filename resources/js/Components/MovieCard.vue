<script setup>
import { router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { ref } from 'vue';
import DialogModal from './DialogModal.vue';

const props = defineProps({
    movie: {
        type: Object,
        required: true,
    },
});

const showDeleteModal = ref(false);

const confirmDelete = () => {
    router.delete(route('movies.destroy', props.movie.slug), {
        onFinish: () => {
            showDeleteModal.value = false;
        },
    });
};
</script>

<template>
    <div class="flex cursor-pointer flex-col relative" @click="router.visit(route('movies.show', movie.slug))">
        <PrimaryButton v-if="$page.props.auth.user.role_id == 1" class="absolute bg-red-500 text-white top-2 right-2"
            @click.stop.prevent="showDeleteModal = true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
        </PrimaryButton>

        <div class="flex flex-col">
            <img :src="movie.image" :alt="movie.title" class="h-80 w-full rounded-md object-cover">
            <h3 class="text-center text-lg font-bold">{{ movie.title }}</h3>
        </div>
    </div>

    <DialogModal :show="showDeleteModal" @close="showDeleteModal = false">
        <template #title>
            Delete Movie
        </template>
        <template #content>
            Are you sure you want to delete this movie?
        </template>
        <template #footer>
            <PrimaryButton @click="showDeleteModal = false">Cancel</PrimaryButton>
            <DangerButton class="ms-3" @click="confirmDelete">Delete</DangerButton>
        </template>
    </DialogModal>
</template>
