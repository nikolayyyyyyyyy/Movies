<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
defineProps({
    movies: Array,
});

</script>
<template>

    <Head title="Movies" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                All Movies
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    <div v-for="movie in movies" :key="movie.id" class="flex flex-col cursor-pointer"
                        @click="router.visit(route('movies.show', movie.id))">
                        <div class="flex flex-col">
                            <img :src="movie.image" alt="Movie Image" class="rounded-md">
                            <h3 class="text-lg font-bold text-center">{{ movie.title }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>