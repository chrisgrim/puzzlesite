<template>
    <div class="relative min-h-28 my-20">
        <div class="flex w-full">
            <div :class="puzzle.is_accessible ? 'bg-stone-300' : 'bg-gray-300'" class="p-4 flex items-center mt-4 w-full">
                <a v-if="puzzle.is_accessible" class="h-full flex items-center uppercase ml-[30rem]" :href="'/puzzles/' + chapterId + '/' + puzzle.id">
                    <span class="text-5xl mr-10">{{ puzzle.id }}</span>
                    <span class="font-source text-lg">{{ puzzle.title }}</span>
                </a>
                <div v-else class="h-full flex items-center uppercase ml-[30rem]">
                    <span class="text-5xl mr-10">{{ puzzle.id }}</span>
                    <span class="font-source text-lg">{{ puzzle.title }}</span>
                    <span class="text-red-500 ml-4">(Locked)</span>
                </div>
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