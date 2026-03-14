<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import File from '@/Icons/File.vue';
import Checkbox from '@/Components/Checkbox.vue';
defineProps({
    categories: Array,
    actors: Array,
});

const createMovie = useForm({
    title: '',
    description: '',
    image: '',
    iframe_url: '',
    rating: '',
    year: '',
    duration: '',
    categories: [],
    actors: [],
})
</script>
<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Movie
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <form
                        id="movie-create-form"
                        class="flex flex-col gap-6 p-4 sm:p-6 md:flex-row md:gap-8"
                        @submit.prevent="createMovie.post(route('movies.store'))"
                    >
                        <!-- Left column: main fields -->
                        <div class="flex flex-1 flex-col gap-4 md:min-w-0">
                            <div class="flex flex-col gap-1">
                                <InputLabel for="title" value="Title" />
                                <TextInput id="title" v-model="createMovie.title" class="w-full" />
                            </div>

                            <div class="flex flex-col gap-1">
                                <InputLabel for="description" value="Description" />
                                <textarea
                                    rows="5"
                                    name="description"
                                    id="description"
                                    v-model="createMovie.description"
                                    class="w-full resize-none rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>

                            <div class="flex flex-col gap-1">
                                <InputLabel for="picture" value="Picture" />
                                <File class="h-20 w-full" />
                            </div>

                            <div class="flex flex-col gap-1">
                                <InputLabel for="iframe_url" value="Iframe URL" />
                                <TextInput id="iframe_url" v-model="createMovie.iframe_url" class="w-full" />
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-3">
                                <div class="flex flex-col gap-1">
                                    <InputLabel for="rating" value="Rating" />
                                    <TextInput id="rating" v-model="createMovie.rating" class="w-full" />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <InputLabel for="year" value="Year" />
                                    <TextInput id="year" v-model="createMovie.year" class="w-full" />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <InputLabel for="duration" value="Duration" />
                                    <TextInput id="duration" v-model="createMovie.duration" class="w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Right column: categories & actors -->
                        <div class="flex flex-col gap-6 border-t border-gray-200 pt-6 md:w-72 md:flex-shrink-0 md:border-t-0 md:border-l md:pt-0 md:pl-8">
                            <div class="flex flex-col gap-2">
                                <InputLabel for="categories" value="Categories" />
                                <div class="flex flex-wrap gap-x-4 gap-y-2 sm:flex-col sm:flex-nowrap">
                                    <label
                                        v-for="category in categories"
                                        :key="category.id"
                                        class="flex items-center gap-2 cursor-pointer"
                                    >
                                        <Checkbox v-model:checked="createMovie.categories" :value="category.name" />
                                        <span class="text-sm text-gray-700">{{ category.name }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <InputLabel for="actors" value="Actors" />
                                <div class="flex flex-wrap gap-x-4 gap-y-2 sm:flex-col sm:flex-nowrap">
                                    <label
                                        v-for="actor in actors"
                                        :key="actor.id"
                                        class="flex items-center gap-2 cursor-pointer"
                                    >
                                        <Checkbox v-model:checked="createMovie.actors" :value="actor.name" />
                                        <span class="text-sm text-gray-700">{{ actor.name }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Submit area: full width below form on all screens -->
                    <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50/50 px-4 py-4 sm:px-6 sm:py-4">
                        <a
                            :href="route('movies.index')"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Cancel
                        </a>
                        <button
                            type="submit"
                            form="movie-create-form"
                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                            :disabled="createMovie.processing"
                        >
                            {{ createMovie.processing ? 'Creating…' : 'Create Movie' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>