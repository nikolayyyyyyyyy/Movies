<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import MovieCard from '@/Components/MovieCard.vue';

defineProps({
    favoriteMovies: Object,
});
</script>
<template>

    <Head title="Favorite Movies" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Favorite Movies
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div v-if="favoriteMovies.length > 0"
                class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                <MovieCard v-for="favoriteMovie in favoriteMovies" :key="favoriteMovie.id" :movie="favoriteMovie" />
            </div>

            <div v-else class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500">No favorite movies found</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>