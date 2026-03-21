<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Star from '@/Icons/Star.vue';
defineProps({
    movie: Object,
});

const showAdditionalInfo = ref('description');
</script>
<template>

    <Head :title="movie.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ movie.title }}
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4">
                    <iframe width="100%" height="500" class="rounded-md" :src="movie.iframe_url"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

                    <div class="flex flex-col gap-2 p-4 rounded-md bg-white shadow-sm">
                        <p class="text-sm text-gray-500">Year: {{ movie.year }}</p>

                        <div class="flex items-center gap-1">
                            <p class="text-sm text-gray-500">Rating: {{ movie.rating }}</p>

                            <Star class="w-4 h-4" />
                        </div>

                        <p class="text-sm text-gray-500">
                            Duration: {{ movie.duration }} (h:mm)
                        </p>

                        <p class="text-sm text-gray-500">
                            Categories: {{movie.categories.map(category => category.name).join(',')}}
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex gap-2 rounded-lg bg-gray-100 p-1 w-fit">
                        <button @click="showAdditionalInfo = 'description'" type="button"
                            class="px-4 py-2.5 text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                            :class="showAdditionalInfo === 'description'
                                ? 'bg-white text-gray-900 shadow-sm'
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                            Description
                        </button>

                        <button @click="showAdditionalInfo = 'actors'" type="button"
                            class="px-4 py-2.5 text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                            :class="showAdditionalInfo === 'actors'
                                ? 'bg-white text-gray-900 shadow-sm'
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                            Actors
                        </button>

                        <button @click="showAdditionalInfo = 'comments'" type="button"
                            class="px-4 py-2.5 text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                            :class="showAdditionalInfo === 'comments'
                                ? 'bg-white text-gray-900 shadow-sm'
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                            Comments
                        </button>
                    </div>

                    <div class="mt-1 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div v-if="showAdditionalInfo === 'description'">
                            <p class="text-gray-600 leading-relaxed">{{ movie.description }}</p>
                        </div>

                        <div v-if="showAdditionalInfo === 'actors'"
                            class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                            <div v-if="movie.actors?.length" v-for="actor in movie.actors" :key="actor.id"
                                class="flex flex-col items-center gap-2 text-center">
                                <div
                                    class="w-full max-w-[80px] sm:max-w-[96px] aspect-square shrink-0 overflow-hidden rounded-full bg-gray-100">
                                    <img :src="actor.profile_picture" :alt="actor.name"
                                        class="h-full w-full object-cover">
                                </div>

                                <p class="text-sm font-medium text-gray-700 line-clamp-2 min-h-[2.5rem]">
                                    {{ actor.name }}
                                </p>
                            </div>
                        </div>

                        <div v-if="showAdditionalInfo === 'comments'">
                            Comments will be added soon.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>