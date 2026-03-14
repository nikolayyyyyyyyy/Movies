<script setup lang="ts">
/*
 *     This file is part of Valuation Marketplace Software.
 *
 *     (c) Dragomir Kolev <dragok@gmail.com>
 *         Louis Hage <louis@softwareinc.nl>
 *
 *     Copyright 2021-2024, JamesItServices Ltd
 *     All rights reserved.
 */
import Modal from './Modal.vue';
import IconXMark from '@/Icons/XMarkBold.vue';

const emit = defineEmits<{ close: [] }>();

withDefaults(
    defineProps<{
        show?: boolean;
        closeable?: boolean;
    }>(),
    {
        show: false,
        closeable: true
    }
);

const close = () => {
    emit('close');
};
</script>

<template>
    <Modal :closeable="closeable" :show="show" @close="close">
        <div class="px-6 py-4">
            <IconXMark
                class="absolute right-1.5 top-2 size-5 cursor-pointer text-gray-300 transition-all hover:text-gray-500 active:scale-90 md:size-7"
                @click="close" />
            <div class="text-xl font-bold text-gray-900">
                <slot name="title" />
            </div>

            <div class="mt-4 text-lg text-gray-600">
                <slot name="content" />
            </div>
        </div>

        <div class="flex flex-row justify-end bg-gray-100 px-6 py-4 text-end">
            <slot name="footer" />
        </div>
    </Modal>
</template>
