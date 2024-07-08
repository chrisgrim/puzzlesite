<template>
	<div class="items-center mb-20 border border-black inline-block px-4 py-2">
        <a class="uppercase text-md" href="/">Chapter 1</a>
    </div>

    <div id="Header">
        <h2 class="text-9xl mb-20"> {{props.puzzle.title}} </h2>
        <div class="bg-black h-[.11rem] w-[10rem] mt-10"></div>
        <DifficultyIndicator :difficulty="props.puzzle.difficulty" />

        <div class="mt-14" style="white-space: pre-wrap;">
	        <p>{{ props.puzzle.description }}</p>
	    </div>
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
