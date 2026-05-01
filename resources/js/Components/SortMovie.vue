<script setup>
import { ref } from 'vue';
const props = defineProps({
    sortBy: {
        type: String,
        default: 'title',
    },
});
const selectedSort = ref('');
const isOpen = ref(false);
const emit = defineEmits(['sortMovies']);

const onChange = () => {
    isOpen.value = false;
    emit('sortMovies', selectedSort.value);
};
</script>   
<template>
    <div class="relative inline-block">
        <select
            name="sortBy"
            id="sortBy"
            v-model="selectedSort"
            class="custom-select border-gray-300 rounded-md focus:border-indigo-500 appearance-none bg-none pr-10"
            @mousedown="isOpen = true"
            @blur="isOpen = false"
            @change="onChange"
        >
            <option disabled value="">Sort by</option>
            <option value="title">Title</option>
            <option value="year">Year (asc)</option>
            <option value="year_desc">Year (desc)</option>
            <option value="rating">Rating (asc)</option>
            <option value="rating_desc">Rating (desc)</option>
        </select>

        <svg
            class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500 transition-transform duration-200"
            :class="{ 'rotate-180': isOpen }"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
        >
            <path
                fill-rule="evenodd"
                d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z"
                clip-rule="evenodd"
            />
        </svg>
    </div>
</template>

<style scoped>
.custom-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: none !important;
}

.custom-select::-ms-expand {
    display: none;
}
</style>