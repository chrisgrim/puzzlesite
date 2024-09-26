<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <!-- Header Section -->
        <Header :puzzle="props.puzzle" />

        <div class="mt-14" style="white-space: pre-wrap;">
            <p>
                The heart of this puzzle was a set of eight six-sided dice, each face bearing engraved letters. When Arthur had dropped the dice, they had magnetically locked themselves to the table surface. The instructions told Arthur that each dice contained the letters for either "PLANET" or "TANGLE". Unable to lift the dice from the table, Arthur’s mission was to figure out what letters were face down.
            </p>
            <p>
                Centered among these letter dice sat two circular discs, one sitting atop the other. Each disc could be rotated, but to what end Arthur knew he would have to figure out. Alongside the discs was a slip of paper with some lines and letters underneath. Maybe this paper had something to do with the discs, which in turn had something to do with the dice?
            </p>
                
            
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
import Puzzle from './component-dice.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});

</script>