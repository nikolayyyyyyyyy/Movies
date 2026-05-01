<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import File from '@/Icons/File.vue';
defineProps({
    categories: Array,
    actors: Array,
});

const createMovie = useForm({
    title: '',
    slug: '',
    description: '',
    image: '',
    iframe_url: '',
    year: '',
    duration: '',
    categories: [],
    actors: [],
})

const displayAddActorsModal = ref(false);
const searchActor = ref('');
const selectedFileName = ref('');
const pictureInput = ref(null);

const onPictureChange = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        createMovie.image = file;
        selectedFileName.value = file.name;
    }
};

const clearPicture = () => {
    createMovie.image = '';
    selectedFileName.value = '';
    if (pictureInput.value) pictureInput.value.value = '';
};

const searchActors = () => {
    router.reload({
        only: ['actors'],
        data: {
            search: searchActor.value
        }
    });
}

</script>
<template>

    <Head title="Create Movie" />

    <AuthenticatedLayout>
        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex gap-3 border-b border-gray-200 bg-gray-50/50 px-4 py-4 sm:px-6 sm:py-4">
                        <h2 class="text-xl font-semibold leading-tight text-gray-800">
                            Create Movie
                        </h2>
                    </div>

                    <form id="movie-create-form" class="flex flex-col gap-6 p-4 sm:p-6 md:flex-row md:gap-8"
                        @submit.prevent="createMovie.post(route('movies.store'))">
                        <div class="flex flex-1 flex-col gap-4 md:min-w-0">
                            <div class="flex flex-col gap-1">
                                <InputLabel for="title" value="Title" />
                                <TextInput id="title" v-model="createMovie.title" class="w-full" />
                                <InputError :message="createMovie.errors.title" />
                            </div>

                            <div class="flex flex-col gap-1">
                                <InputLabel for="slug" value="Slug" />
                                <TextInput id="slug" v-model="createMovie.slug" class="w-full" />
                                <InputError :message="createMovie.errors.slug" />
                            </div>

                            <div class="flex flex-col gap-1">
                                <InputLabel for="description" value="Description" />
                                <textarea rows="5" name="description" id="description" v-model="createMovie.description"
                                    class="w-full resize-none rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                <InputError :message="createMovie.errors.description" />
                            </div>

                            <div class="flex flex-col gap-1">
                                <InputLabel for="picture" value="Picture" />
                                <div
                                    class="flex w-full items-stretch gap-3 rounded-md border border-gray-300 bg-white p-2 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
                                    <File class="h-20 w-16 flex-shrink-0 text-gray-400 sm:w-20" />
                                    <div class="flex min-w-0 flex-1 flex-col justify-center gap-1">
                                        <div v-if="!selectedFileName" class="flex min-h-[2rem] w-full items-center">
                                            <input ref="pictureInput" id="picture" type="file" accept="image/*"
                                                class="min-w-0 flex-1 border-0 bg-transparent p-0 text-sm file:mr-2 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-gray-700 focus:ring-0"
                                                @change="onPictureChange" />
                                        </div>
                                        <template v-else>
                                            <span class="truncate text-sm font-medium text-gray-700">{{ selectedFileName
                                                }}</span>
                                            <button type="button"
                                                class="w-fit text-sm text-indigo-600 hover:text-indigo-500"
                                                @click="clearPicture">
                                                Change
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                <InputError :message="createMovie.errors.image" />
                            </div>

                            <div class="flex flex-col gap-1">
                                <InputLabel for="iframe_url" value="Iframe URL" />
                                <TextInput id="iframe_url" v-model="createMovie.iframe_url" class="w-full" />
                                <InputError :message="createMovie.errors.iframe_url" />
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-2">
                                <div class="flex flex-col gap-1">
                                    <InputLabel for="year" value="Year" />
                                    <TextInput id="year" v-model="createMovie.year" class="w-full" />
                                    <InputError :message="createMovie.errors.year" />
                                </div>

                                <div class="flex flex-col gap-1">
                                    <InputLabel for="duration" value="Duration" />
                                    <TextInput id="duration" v-model="createMovie.duration" class="w-full" />
                                    <InputError :message="createMovie.errors.duration" />
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex flex-col gap-6 border-t border-gray-200 pt-6 md:w-72 md:flex-shrink-0 md:border-t-0 md:border-l md:pt-0 md:pl-8">
                            <div class="flex flex-col gap-2">
                                <InputLabel for="categories" value="Categories" />

                                <div class="flex flex-wrap gap-x-4 gap-y-2 sm:flex-col sm:flex-nowrap">
                                    <label v-for="category in categories" :key="category.id"
                                        class="flex items-center gap-2 cursor-pointer">

                                        <Checkbox v-model:checked="createMovie.categories" :value="category.id" />
                                        <span class="text-sm text-gray-700">{{ category.name }}</span>
                                    </label>
                                    <InputError :message="createMovie.errors.categories" />
                                </div>
                            </div>
                            <SecondaryButton @click="displayAddActorsModal = true"
                                class="flex justify-center align-center">
                                Add Actors
                            </SecondaryButton>
                        </div>
                    </form>

                    <div
                        class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50/50 px-4 py-4 sm:px-6 sm:py-4">
                        <PrimaryButton :disabled="createMovie.processing" type="submit" form="movie-create-form">
                            {{ createMovie.processing ? 'Creating…' : 'Create Movie' }}
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>

        <DialogModal :show="displayAddActorsModal" @close="displayAddActorsModal = false">
            <template #title>
                Add Actors
            </template>
            <template #content>
                <div class="flex flex-col gap-4 min-w-0">
                    <div class="flex flex-col gap-1.5 flex-shrink-0">
                        <InputLabel for="actors" value="Search actor" />
                        <TextInput @change="searchActors" type="text" id="actors" v-model="searchActor"
                            placeholder="Search by actor name..."
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div class="flex flex-col gap-1 min-h-0">
                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">
                            Select actors ({{ actors?.length ?? 0 }})
                        </span>
                        <div class="actors-scroll mt-1 rounded-lg border border-gray-200 bg-gray-50/50 p-1 sm:p-1.5">
                            <div v-if="actors?.length"
                                class="actors-list-scroll flex max-h-[min(50vh,320px)] min-h-[120px] flex-col gap-0.5 overflow-y-auto overflow-x-hidden">
                                <label v-for="actor in actors" :key="actor.id"
                                    class="actor-row flex cursor-pointer items-center gap-3 rounded-md px-2 py-2.5 transition-colors hover:bg-white hover:shadow-sm focus-within:bg-white focus-within:shadow-sm sm:gap-4 sm:px-3">
                                    <Checkbox v-model:checked="createMovie.actors" :value="actor.id"
                                        class="flex-shrink-0" />
                                    <img :src="actor.profile_picture" alt=""
                                        class="h-10 w-10 flex-shrink-0 rounded-full object-cover ring-1 ring-gray-200 sm:h-11 sm:w-11" />
                                    <span class="min-w-0 truncate text-sm font-medium text-gray-800 sm:text-base">
                                        {{ actor.name }}
                                    </span>
                                </label>
                            </div>
                            <div v-else
                                class="actors-list-scroll flex max-h-[min(50vh,320px)] min-h-[120px] items-center justify-center rounded-md bg-gray-100/50 text-sm text-gray-500">
                                No actors found. Try a different search.
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click="displayAddActorsModal = false">
                    Done
                </PrimaryButton>
            </template>
        </DialogModal>
    </AuthenticatedLayout>
</template>

<style scoped>
.actors-list-scroll {
    scrollbar-gutter: stable;
}

/* Custom scrollbar — WebKit (Chrome, Safari, Edge) */
.actors-list-scroll::-webkit-scrollbar {
    width: 8px;
}

.actors-list-scroll::-webkit-scrollbar-track {
    background: rgb(243 244 246);
    border-radius: 4px;
}

.actors-list-scroll::-webkit-scrollbar-thumb {
    background: rgb(209 213 219);
    border-radius: 4px;
}

.actors-list-scroll::-webkit-scrollbar-thumb:hover {
    background: rgb(156 163 175);
}

/* Firefox */
.actors-list-scroll {
    scrollbar-width: thin;
    scrollbar-color: rgb(209 213 219) rgb(243 244 246);
}
</style>