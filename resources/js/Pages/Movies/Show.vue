<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import Star from '@/Icons/Star.vue';
const props = defineProps({
    movie: Object,
});

const page = usePage();
const showAdditionalInfo = ref('description');

const form = useForm({
    comment: '',
    movie_id: props.movie.id,
});

const comments = ref([...(props.movie.comments ?? [])]);

watch(
    () => props.movie.comments,
    (c) => {
        comments.value = [...(c ?? [])];
    },
    { deep: true },
);

function formatCommentDate(iso) {
    return new Date(iso).toLocaleString('en-US', {
        dateStyle: 'medium',
        timeStyle: 'short',
    });
}

function submitComment() {
    form.post(route('comments.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
}

onMounted(() => {
    Echo.channel('movie.' + props.movie.id).listen('.comment.created', (e) => {
        if (e.comment?.id && !comments.value.some((c) => c.id === e.comment.id)) {
            comments.value.push(e.comment);
        }
    });
});

onBeforeUnmount(() => {
    Echo.leave('movie.' + props.movie.id);
});
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
                        <p class="text-sm">
                            Year:
                            <span class="text-gray-500">
                                {{ movie.year }}
                            </span>
                        </p>

                        <div class="flex items-center gap-0.5">
                            <p class="text-sm">
                                Rating:
                                <span class="text-gray-500">
                                    {{ movie.rating }}
                                </span>
                            </p>

                            <Star class="w-4 h-4" />
                        </div>

                        <p class="text-sm">
                            Duration:
                            <span class="text-gray-500">
                                {{ movie.duration }}
                            </span>
                        </p>

                        <p class="text-sm">
                            Categories:
                            <span class="text-gray-500">
                                {{movie.categories.map(category => category.name).join(', ')}}
                            </span>
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

                        <div v-if="showAdditionalInfo === 'comments'" class="flex flex-col gap-6">
                            <ul v-if="comments.length" class="space-y-0 divide-y divide-gray-100">
                                <li v-for="comment in comments" :key="comment.id"
                                    class="flex gap-3 py-4 first:pt-0 last:pb-0 sm:gap-4">
                                    <div class="shrink-0">
                                        <div
                                            class="h-10 w-10 overflow-hidden rounded-full bg-gray-100 ring-2 ring-white shadow-sm sm:h-11 sm:w-11">
                                            <img :src="comment.user?.profile_picture ?? '/images/avatar.png'"
                                                :alt="comment.user?.name ?? ''" class="h-full w-full object-cover" />
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1 space-y-1.5">
                                        <div class="flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
                                            <span class="text-sm font-semibold text-gray-900">
                                                {{ comment.user?.name ?? 'User' }}
                                            </span>
                                            <time v-if="comment.created_at" class="text-xs text-gray-400 sm:text-[13px]"
                                                :datetime="comment.created_at">
                                                {{ formatCommentDate(comment.created_at) }}
                                            </time>
                                        </div>
                                        <p class="whitespace-pre-wrap text-sm leading-relaxed text-gray-700">
                                            {{ comment.comment }}
                                        </p>
                                    </div>
                                </li>
                            </ul>

                            <div v-else
                                class="flex flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-gray-200 bg-gray-50/60 px-4 py-10 text-center sm:py-12">
                                <img src="/images/comment.png" alt="No comments" class="w-12 h-12">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">No comments yet</p>
                                    <p class="mt-1 text-sm text-gray-500">Be the first to share your thoughts.</p>
                                </div>
                            </div>

                            <form @submit.prevent="submitComment"
                                class="flex flex-col gap-3 rounded-xl border border-gray-200 bg-gradient-to-b from-gray-50/90 to-white p-3 shadow-inner sm:flex-row sm:items-stretch sm:gap-3 sm:p-4">
                                <div class="flex min-w-0 flex-1 gap-3">
                                    <img :src="page.props.auth?.user?.profile_picture ?? '/images/avatar.png'"
                                        alt="profile_picture" class="h-10 w-10 object-cover rounded-full" />

                                    <label class="sr-only" for="movie-comment">Write a comment…</label>

                                    <textarea id="movie-comment" v-model="form.comment" placeholder="Write a comment…"
                                        class="w-full resize-none rounded-lg border-gray-300 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:min-h-[4.5rem]"></textarea>
                                </div>

                                <button type="submit" :disabled="!form.comment.trim()"
                                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto sm:min-w-[3.25rem] sm:px-3"
                                    aria-label="Send comment">

                                    <img src="/images/send.png" alt="Send" class="w-10 h-10 shrink-0" />

                                    <span class="sm:hidden">Send</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>