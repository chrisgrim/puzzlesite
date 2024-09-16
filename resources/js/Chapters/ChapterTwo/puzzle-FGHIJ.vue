<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <!-- Header Section -->
        <Header :puzzle="props.puzzle" />

        <div class="mt-14" style="white-space: pre-wrap;">
            <p>Determined to regain focus, Arthur returned his attention to the puzzle laid out before him. To his surprise, the creator of today's Associations puzzle was not credited with a name but rather seven symbols. This was peculiar, as Lana Felsworth had always been the author of the Monday Associations puzzle for as long as Arthur had lived in the city. The format of the puzzle also appeared slightly different. Intrigued by this anomaly, Arthur focused intently on the strange puzzle before him, curiosity piqued by the unusual presentation</p>

            <h2 class="mt-10">Answer: PARALLEL</h2>
        </div>

        <!-- Puzzle Section -->
        <div id="puzzle" class="puzzle">
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
import Puzzle from './puzzle-FGHIJ-component.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});


</script>
