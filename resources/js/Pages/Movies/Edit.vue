<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, onUnmounted, ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    movie: Object,
    categories: {
        type: Array,
        default: () => [],
    },
    actors: {
        type: Array,
        default: () => [],
    },
});

const editMovieForm = useForm({
    title: props.movie.title ?? '',
    slug: props.movie.slug ?? '',
    description: props.movie.description ?? '',
    image: null,
    remove_image: false,
    iframe_url: props.movie.iframe_url ?? '',
    year: props.movie.year ?? '',
    duration: props.movie.duration ?? '',
    categories: (props.movie.categories ?? []).map((category) => category.id),
    actors: (props.movie.actors ?? []).map((actor) => actor.id),
});

const pictureInput = ref(null);
const selectedFileName = ref('');
const selectedImagePreview = ref(null);
const actorSearch = ref('');

const movieImagePreview = computed(() => {
    if (selectedImagePreview.value) return selectedImagePreview.value;
    if (editMovieForm.remove_image) return '';
    return props.movie.image || '';
});
const currentActors = computed(() => props.movie.actors ?? []);
const selectedActorsCount = computed(() => editMovieForm.actors.length);
const filteredActors = computed(() => {
    if (!actorSearch.value.trim()) return props.actors;
    const query = actorSearch.value.trim().toLowerCase();
    return props.actors.filter((actor) => actor.name.toLowerCase().includes(query));
});
const hasExistingPoster = computed(() => Boolean(props.movie.image));

function onPictureChange(event) {
    const file = event.target.files?.[0];
    editMovieForm.image = file ?? null;
    selectedFileName.value = file?.name ?? '';
    if (file) {
        editMovieForm.remove_image = false;
    }

    if (selectedImagePreview.value) {
        URL.revokeObjectURL(selectedImagePreview.value);
    }
    selectedImagePreview.value = file ? URL.createObjectURL(file) : null;
}

function clearPicture() {
    editMovieForm.image = null;
    selectedFileName.value = '';
    if (pictureInput.value) {
        pictureInput.value.value = '';
    }
    if (selectedImagePreview.value) {
        URL.revokeObjectURL(selectedImagePreview.value);
    }
    selectedImagePreview.value = null;
}

function removeCurrentPoster() {
    clearPicture();
    editMovieForm.remove_image = true;
}

function undoPosterRemoval() {
    editMovieForm.remove_image = false;
}

function updateMovie() {
    editMovieForm
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post(route('movies.update', props.movie.slug), {
            forceFormData: true,
            preserveScroll: true,
        });
}

onUnmounted(() => {
    if (selectedImagePreview.value) {
        URL.revokeObjectURL(selectedImagePreview.value);
    }
});
</script>

<template>
    <Head :title="`Edit ${movie.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit {{ movie.title }}
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <div class="border-b border-gray-200 bg-gray-50 px-4 py-4 sm:px-6">
                        <p class="text-sm text-gray-500">Update movie details and keep your catalog fresh.</p>
                    </div>

                    <form id="movie-edit-form" class="grid gap-6 p-4 sm:p-6 md:grid-cols-12" @submit.prevent="updateMovie">
                        <div class="space-y-4 md:col-span-8">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-1">
                                    <InputLabel for="title" value="Title" />
                                    <TextInput id="title" v-model="editMovieForm.title" class="w-full" />
                                    <InputError :message="editMovieForm.errors.title" />
                                </div>

                                <div class="space-y-1">
                                    <InputLabel for="slug" value="Slug" />
                                    <TextInput id="slug" v-model="editMovieForm.slug" class="w-full" />
                                    <InputError :message="editMovieForm.errors.slug" />
                                </div>
                            </div>

                            <div class="space-y-1">
                                <InputLabel for="description" value="Description" />
                                <textarea id="description" rows="5" v-model="editMovieForm.description"
                                    class="w-full resize-none rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                <InputError :message="editMovieForm.errors.description" />
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-1">
                                    <InputLabel for="iframe_url" value="Iframe URL" />
                                    <TextInput id="iframe_url" v-model="editMovieForm.iframe_url" class="w-full" />
                                    <InputError :message="editMovieForm.errors.iframe_url" />
                                </div>

                                <div class="space-y-1">
                                    <InputLabel for="duration" value="Duration" />
                                    <TextInput id="duration" v-model="editMovieForm.duration" class="w-full" />
                                    <InputError :message="editMovieForm.errors.duration" />
                                </div>
                            </div>

                            <div class="space-y-1">
                                <InputLabel for="year" value="Year" />
                                <TextInput id="year" v-model="editMovieForm.year" class="w-full" />
                                <InputError :message="editMovieForm.errors.year" />
                            </div>
                        </div>

                        <div class="space-y-5 md:col-span-4">
                            <div class="space-y-2 rounded-lg border border-gray-200 p-3">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-medium text-gray-700">Poster</p>
                                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">
                                        {{ selectedFileName ? 'New file selected' : (editMovieForm.remove_image ? 'Marked for removal' : 'Current file') }}
                                    </span>
                                </div>
                                <img v-if="movieImagePreview" :src="movieImagePreview" alt="Movie poster preview"
                                    class="h-52 w-full rounded-md object-cover" />
                                <div v-else class="flex h-52 items-center justify-center rounded-md bg-gray-100 text-sm text-gray-500">
                                    No poster will be used
                                </div>

                                <input ref="pictureInput" id="image" type="file" accept="image/*"
                                    class="w-full text-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-gray-700"
                                    @change="onPictureChange" />
                                <p v-if="selectedFileName" class="truncate text-xs text-gray-500">{{ selectedFileName }}</p>
                                <div class="flex flex-wrap gap-2 pt-1">
                                    <button v-if="selectedFileName" type="button"
                                        class="rounded-md border border-gray-300 px-2.5 py-1 text-sm text-gray-700 transition hover:bg-gray-50"
                                        @click="clearPicture">
                                        Undo new file
                                    </button>
                                    <button v-if="hasExistingPoster && !editMovieForm.remove_image" type="button"
                                        class="rounded-md border border-red-200 bg-red-50 px-2.5 py-1 text-sm text-red-700 transition hover:bg-red-100"
                                        @click="removeCurrentPoster">
                                        Remove current poster
                                    </button>
                                    <button v-if="editMovieForm.remove_image" type="button"
                                        class="rounded-md border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-sm text-indigo-700 transition hover:bg-indigo-100"
                                        @click="undoPosterRemoval">
                                        Keep current poster
                                    </button>
                                </div>
                                <InputError :message="editMovieForm.errors.image" />
                            </div>

                            <div class="space-y-2 rounded-lg border border-gray-200 p-3">
                                <p class="text-sm font-medium text-gray-700">Categories</p>
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-1">
                                    <label v-for="category in categories" :key="category.id"
                                        class="flex cursor-pointer items-center gap-2 rounded-md border border-gray-200 px-2 py-1.5 hover:bg-gray-50">
                                        <Checkbox v-model:checked="editMovieForm.categories" :value="category.id" />
                                        <span class="text-sm text-gray-700">{{ category.name }}</span>
                                    </label>
                                </div>
                                <InputError :message="editMovieForm.errors.categories" />
                            </div>

                            <div class="space-y-2 rounded-lg border border-gray-200 p-3">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-medium text-gray-700">Actors</p>
                                    <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-xs text-indigo-700">
                                        Selected: {{ selectedActorsCount }}
                                    </span>
                                </div>
                                <TextInput v-model="actorSearch" type="text" placeholder="Search actors..."
                                    class="w-full" />
                                <div class="max-h-56 space-y-2 overflow-y-auto rounded-md border border-gray-100 p-2">
                                    <label v-for="actor in filteredActors" :key="actor.id"
                                        class="flex cursor-pointer items-center gap-2 rounded-md border border-gray-200 px-2 py-1.5 hover:bg-gray-50">
                                        <Checkbox v-model:checked="editMovieForm.actors" :value="actor.id" />
                                        <img :src="actor.profile_picture" :alt="actor.name"
                                            class="h-7 w-7 rounded-full object-cover ring-1 ring-gray-200">
                                        <span class="truncate text-sm text-gray-700">{{ actor.name }}</span>
                                    </label>
                                    <p v-if="filteredActors.length === 0" class="py-3 text-center text-sm text-gray-500">
                                        No actors found.
                                    </p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Current actors: {{ currentActors.map((actor) => actor.name).join(', ') || 'None' }}
                                </p>
                                <InputError :message="editMovieForm.errors.actors" />
                            </div>
                        </div>
                    </form>

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-4 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <Link :href="route('movies.show', movie.slug)"
                            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                            Cancel
                        </Link>
                        <PrimaryButton :disabled="editMovieForm.processing" type="submit" form="movie-edit-form">
                            {{ editMovieForm.processing ? 'Saving...' : 'Save Changes' }}
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
