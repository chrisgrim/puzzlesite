<template>
    <div class="relative min-h-28 my-20">
        <div class="flex w-full">
            <div class="p-4 flex items-center mt-4 w-full">
                <a class="h-full flex items-center uppercase ml-[30rem]" :href="'/puzzles/' + chapterId + '/' + puzzle.id">
                    <span class="text-5xl mr-10">{{ puzzle.id }}</span>
                    <span class="font-source text-lg">{{ puzzle.title }}</span>
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    puzzle: {
        type: Object,
        required: true
    },
    chapterId: {
        type: Number,
        required: true
    }
});


</script>