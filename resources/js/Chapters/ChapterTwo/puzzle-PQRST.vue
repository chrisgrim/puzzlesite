<template>
    <div class="relative overflow-hidden">
        <div class="max-w-screen-lg m-auto my-24 p-8">

            <!-- Header Section -->
            <Header :puzzle="props.puzzle" />

            <div class="mt-14" style="white-space: pre-wrap;">
                <p>{{ props.puzzle.description }}</p>
            </div>

            <!-- Puzzle Section -->
            <div id="puzzle" class="puzzle flex flex-row items-center justify-center py-16 bg-[url('https://github.com/rheh/CSS-Sticky-Notes/blob/master/cork_board.gif?raw=true')] bg-no-repeat bg-cover">

                <div class="p-4">
                  <img src="/images/Sherlock-poster.jpg" class="w-full h-[60rem]">
              </div>
            </div>

            <!-- Submission Section -->
            <SubmissionSection 
                :question="'What was hidden in the shapes?'"
                :solution="props.solution" 
                :chapter="props.chapter"
                :puzzle="props.puzzle"
            />
        </div>
    </div>
</template>


<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import Header from '@/Global/header.vue';
import SubmissionSection from '@/Global/submissionSection.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});






</script>


