<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <!-- Header Section -->
        <Header :puzzle="props.puzzle" />

        <div class="mt-14" style="white-space: pre-wrap;">
            <p>He called them DIEs — ”Dangerous Infiltration Exercises”. One memorable DIE mimicked a hit movie's laser-grid security system, tasking Arthur to navigate it without triggering alarms. After each solution, Harold would refine the puzzle, incrementally increasing its difficulty based on Arthur's solutions.</p>
            <p>
                This puzzle is a shrinking word ladder for films. CO(N)VERT->VEC(T)OR->COVER or GR(E)NADE->(D)ANGER->RANGE. Then you use the missing letters to spell out adventure
            </p>
            <h2 class="mt-10">Answer: Adventure</h2>
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
import Puzzle from './component-laser-grid.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});






</script>


