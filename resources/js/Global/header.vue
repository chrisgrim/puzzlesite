<template>
	<div class="items-center inline-block mb-20">
        <a class="uppercase text-xl" href="/">{{props.puzzle.chapter.title}}</a>

    </div>

    <div class="bg-[#b0a3c3] h-1 w-full mb-20"></div>

    <div id="Header">
        <h1 class="text-9xl mb-20"> {{props.puzzle.title}} </h1>

        <!-- <div class="mt-14" style="white-space: pre-wrap;">
	        <p>{{ props.puzzle.description }}</p>
	    </div> -->
        <!-- <DifficultyIndicator :difficulty="props.puzzle.difficulty" /> -->
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
import DifficultyIndicator from './difficultyIndicator.vue';


const props = defineProps({
    puzzle: {
        type: Object,
        required: true,
    }
});
</script>
