<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import Star from '@/Icons/Star.vue';
import HandThumbUpSolidBold from '@/Icons/HandThumbUpSolidBold.vue';
import HandThumbDownSolidBold from '@/Icons/HandThumbDownSolidBold.vue';
const props = defineProps({
    movie: Object,
    rating: Number,
});

const page = usePage();
const showAdditionalInfo = ref('description');

const form = useForm({
    comment: '',
    movie_id: props.movie.id,
});

const comments = ref([...(props.movie.comments ?? [])]);
const editingCommentId = ref(null);
const editCommentValue = ref('');
const currentUserId = computed(() => page.props.auth?.user?.id);

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

function isCommentAuthor(comment) {
    return Number(comment.user_id) === Number(currentUserId.value)
        || Number(page.props.auth?.user?.role_id) === 1;
}

function startEditComment(comment) {
    editingCommentId.value = comment.id;
    editCommentValue.value = comment.comment ?? '';
}

function cancelEditComment() {
    editingCommentId.value = null;
    editCommentValue.value = '';
}

function saveEditComment(comment) {
    const value = editCommentValue.value.trim();
    if (!value) return;

    router.put(route('comments.update', comment.id), {
        comment: value,
        preserveScroll: true,
    }, {
        onSuccess: () => {
            cancelEditComment();
        }
    });
}

function deleteComment(commentId) {
    router.delete(route('comments.destroy', commentId),
        {
            preserveScroll: true,
            onSuccess: () => {
                cancelEditComment();
            }
        });
}

const likeMovie = () => {
    router.post(
        route('user-movie-reactions.store'),
        { movie_id: props.movie.id, reaction: 'like' },
        {
            preserveScroll: true,
            preserveState: false
        },
    );
}

const dislikeMovie = () => {
    router.post(
        route('user-movie-reactions.store'),
        { movie_id: props.movie.id, reaction: 'dislike' },
        {
            preserveScroll: true,
            preserveState: false
        },
    );
}

const isLiked = computed(() => {
    return props.movie.reactions[0]?.reaction == 'like';
});

const isDisliked = computed(() => {
    return props.movie.reactions[0]?.reaction == 'dislike';
});

onMounted(() => {
    Echo.channel('movie.' + props.movie.id).listen('.comment.created', (e) => {
        if (e.comment?.id && !comments.value.some((c) => c.id === e.comment.id)) {
            comments.value.push(e.comment);
        }
    });
    
    Echo.channel('movie.' + props.movie.id).listen('.comment.updated', (e) => {
        if (!e.comment?.id) return;
        const idx = comments.value.findIndex((c) => c.id === e.comment.id);
        if (idx === -1) return;
        comments.value[idx] = e.comment;
    });

    Echo.channel('movie.' + props.movie.id).listen('.comment.deleted', (e) => {
        if (!e.comment_id) return;
        const deletedId = Number(e.comment_id);
        comments.value = comments.value.filter((c) => Number(c.id) !== deletedId);
        if (Number(editingCommentId.value) === deletedId) {
            cancelEditComment();
        }
    });

    Echo.channel('movie_rating.' + props.movie.id).listen('.rating.updated', () => {
        router.reload({ only: ['rating'] });
    });
});

onBeforeUnmount(() => {
    Echo.leave('movie.' + props.movie.id);
    Echo.leave('movie_rating.' + props.movie.id);
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

                    <div class="flex items-center gap-2  p-4 rounded-md bg-white shadow-sm justify-end">
                        <div class="flex items-center gap-2">
                            <p class="text-sm text-gray-500">Like</p>

                            <HandThumbUpSolidBold @click="likeMovie" class="w-5 h-5 cursor-pointer transition-colors"
                                :class="isLiked ? 'text-green-700' : 'text-gray-500 hover:text-gray-700'" />
                        </div>

                        <div class="flex items-center gap-2">
                            <p class="text-sm text-gray-500">Dislike</p>

                            <HandThumbDownSolidBold @click="dislikeMovie"
                                class="w-5 h-5 cursor-pointer transition-colors"
                                :class="isDisliked ? 'text-red-700' : 'text-gray-500 hover:text-gray-700'" />
                        </div>
                    </div>

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
                                    {{ rating }}
                                </span>
                            </p>

                            <Star class="w-4 h-4" :fill="'#f59e0b'" />
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
                            <TransitionGroup v-if="comments.length" tag="ul" name="comment-item"
                                class="space-y-0 divide-y divide-gray-100">
                                <li v-for="comment in comments" :key="comment.id"
                                    class="flex flex-col gap-3 py-4 first:pt-0 last:pb-0 sm:flex-row sm:gap-4">
                                    <div class="shrink-0">
                                        <div
                                            class="h-10 w-10 overflow-hidden rounded-full bg-gray-100 ring-2 ring-white shadow-sm sm:h-11 sm:w-11">
                                            <img :src="comment.user?.profile_picture ?? '/images/avatar.png'"
                                                :alt="comment.user?.name ?? ''" class="h-full w-full object-cover" />
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1 rounded-xl bg-white p-3">
                                        <div
                                            class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                                            <div class="flex min-w-0 flex-wrap items-baseline gap-x-2 gap-y-0.5">
                                                <span class="text-sm font-semibold text-gray-900">
                                                    {{ comment.user?.name ?? 'User' }}
                                                </span>
                                                <time v-if="comment.created_at"
                                                    class="text-xs text-gray-400 sm:text-[13px]"
                                                    :datetime="comment.created_at">
                                                    {{ formatCommentDate(comment.created_at) }}
                                                </time>
                                            </div>

                                            <Transition enter-active-class="transition duration-200 ease-out"
                                                enter-from-class="opacity-0 -translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition duration-150 ease-in"
                                                leave-from-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 -translate-y-1">
                                                <div v-if="isCommentAuthor(comment)"
                                                    class="flex w-full items-center gap-2 sm:w-auto">
                                                    <button type="button" @click="startEditComment(comment)"
                                                        v-if="editingCommentId !== comment.id"
                                                        class="inline-flex flex-1 items-center justify-center rounded-md border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-100 sm:flex-none">
                                                        Edit
                                                    </button>
                                                    <button type="button" @click="deleteComment(comment.id)"
                                                        class="inline-flex flex-1 items-center justify-center rounded-md border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-100 sm:flex-none">
                                                        Delete
                                                    </button>
                                                </div>
                                            </Transition>
                                        </div>

                                        <Transition enter-active-class="transition-all duration-200 ease-out"
                                            enter-from-class="opacity-0 max-h-0" enter-to-class="opacity-100 max-h-40"
                                            leave-active-class="transition-all duration-150 ease-in"
                                            leave-from-class="opacity-100 max-h-40" leave-to-class="opacity-0 max-h-0">
                                            <div v-if="editingCommentId === comment.id"
                                                class="mt-3 space-y-2 overflow-hidden">
                                                <textarea v-model="editCommentValue" rows="3"
                                                    class="w-full resize-none rounded-lg border-gray-300 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                                <div class="flex flex-col gap-2 sm:flex-row">
                                                    <button type="button" @click="saveEditComment(comment)"
                                                        :disabled="!editCommentValue.trim()"
                                                        class="inline-flex items-center justify-center rounded-md bg-blue-600 px-3 py-2 text-xs font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                                                        Save
                                                    </button>
                                                    <button type="button" @click="cancelEditComment"
                                                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-50">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </Transition>

                                        <p v-if="editingCommentId !== comment.id"
                                            class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-gray-700">
                                            {{ comment.comment }}
                                        </p>
                                    </div>
                                </li>
                            </TransitionGroup>

                            <div v-else
                                class="flex flex-col items-center justify-center gap-3 rounded-lg bg-gray-50/60 px-4 py-10 text-center sm:py-12">
                                <img src="/images/comment.png" alt="No comments" class="w-12 h-12">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">No comments yet</p>
                                    <p class="mt-1 text-sm text-gray-500">Be the first to share your thoughts.</p>
                                </div>
                            </div>

                            <form @submit.prevent="submitComment"
                                class="flex flex-col gap-3 rounded-xl bg-gradient-to-b from-gray-50/90 to-white p-3 sm:flex-row sm:items-stretch sm:gap-3 sm:p-4">
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

<style>
.comment-item-enter-active,
.comment-item-leave-active {
    transition: all 0.22s ease;
}

.comment-item-enter-from,
.comment-item-leave-to {
    opacity: 0;
    transform: translateY(8px);
}
</style>