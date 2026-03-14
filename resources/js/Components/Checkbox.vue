<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        if (Array.isArray(props.checked)) {
            return props.value != null && props.checked.includes(props.value);
        }
        return !!props.checked;
    },
    set(checked) {
        if (Array.isArray(props.checked) && props.value != null) {
            const next = checked
                ? [...props.checked, props.value]
                : props.checked.filter((v) => v !== props.value);
            emit('update:checked', next);
        } else {
            emit('update:checked', checked);
        }
    },
});
</script>

<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    />
</template>
