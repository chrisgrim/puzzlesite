<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <!-- Header Section -->
        <Header :puzzle="props.puzzle" />

        <div class="mt-14" style="white-space: pre-wrap;">
            <p>The heart of this puzzle was a collection of ten hexagonal "tablets," meticulously crafted to mimic ancient stone fragments. These pieces, fixed firmly in place, added an intriguing twist to the challenge. Each tablet face bore visible letter engravings, spelling out either "FLOWER" or "PETALS" in various arrangements. Arthur's keen eye had already noted this pattern, but he sensed the true enigma lay hidden beneath—in the unseen undersides of these immovable artifacts.</p>
            <p>Centered among these stone-like hexagons sat two circular discs, one slightly smaller and perched atop the other. Their edges hinted at the possibility of rotation and alignment, a tantalizing mechanic begging to be explored. Completing this archaeological tableau was a small slate bearing nine seemingly random letters, its purpose yet another riddle to unravel.
            </p>

            <div class="w-full mt-14 h-2 bg-black"></div>
            <p>Two parter. First part the user has to figure out that the bottom of the artifacts spell out WATERFOWL. Then they have to rotate the two discs so that the Flower Petal Icon lines up with the WATERFOWL icon</p>
            <h2 class="mt-10">Answer: APPROACHING</h2>
        </div>

        <!-- Puzzle Section -->
        <div id="puzzle" class="puzzle flex flex-row items-center justify-center py-16">
            <Puzzle></Puzzle>
        </div>

        <!-- Submission Section -->
        <SubmissionSection 
            :question="'What was hidden in the shapes?'"
            :solution="props.solution" 
            :chapter="props.chapter"
            :puzzle="props.puzzle"
        />
    </div>
</template>


<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import Header from '@/Global/header.vue';
import SubmissionSection from '@/Global/submissionSection.vue';
import Puzzle from './puzzle-UVWXY-component.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});

</script>