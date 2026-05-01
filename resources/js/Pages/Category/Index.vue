<script setup>
import MovieCard from '@/Components/MovieCard.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    movies: Object,
    category: Object,
});
const currentRoute = route().current();
const search = ref('');

const searchMovies = () => {
    router.get(currentRoute, {
        search: search.value
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ category.name }}
            </h2>
        </template>

        <template #search>
            <div class="flex gap-4">
                <TextInput type="text" class="w-full" v-model="search" placeholder="Search for a movie" />

                <PrimaryButton @click="searchMovies">Search</PrimaryButton>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div v-if="movies.data.length > 0" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    <MovieCard v-for="movie in movies.data" :key="movie.id" :movie="movie" />
                </div>

                <!-- Pagination -->
                <nav v-if="movies.links.length > 0" class="mt-8 flex flex-wrap items-center justify-center gap-2"
                    aria-label="Pagination">
                    <template v-for="(link, index) in movies.links" :key="index">
                        <Link v-if="link.url" :href="link.url"
                            class="flex items-center justify-center min-w-[2.25rem] px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 shadow-sm transition-colors hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
                            :class="{ 'bg-indigo-600 border-indigo-600 hover:bg-indigo-700 hover:border-indigo-700': link.active }"
                            v-html="link.label" />
                        <span v-else
                            class="min-w-[2.25rem] px-3 py-2 text-sm font-medium rounded-lg border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed"
                            v-html="link.label" />
                    </template>
                </nav>
            </div>
            <div v-else class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500">No movies found</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>