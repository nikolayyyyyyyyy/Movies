<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Welcome to SM moovies!
                    </div>
                    <div class="p-6 text-gray-900">
                        Our platform is dedicated to providing you with the latest and greatest movies.
                        We are a team of movie enthusiasts who are passionate about sharing our love of cinema with the
                        world.
                        We believe that movies are more than just entertainment, they are a way to escape reality and
                        experience
                        new worlds.
                    </div>
                    <p class="text-gray-900 p-6">
                        <span class="font-bold">
                            We dont keep any movies in the database, we just provide a platform for you to watch them
                            via
                            iframes from
                            other
                            platforms.
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
