<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <!-- Header Section -->
        <Header :puzzle="props.puzzle" />

        <div class="mt-14" style="white-space: pre-wrap;">
            <p>Arthur sat at his kitchen table, sipping his morning tea as he unfolded the newspaper. His eyes lingered on a familiar puzzle type that had quickly become a new favorite. This challenge, unlike the standard crosswords he was so accustomed to, required him to find the common thread between three seemingly unrelated words. The puzzle presented nine words in total, broken into three sets of three. His task was to identify the single word that linked each set. It was a game of patterns and associations, a perfect mental exercise to accompany his breakfast.</p>

            <p>As Arthur worked his way through the puzzle, he appreciated the subtle complexity. What seemed random at first often revealed an elegant connection once he took the time to consider each set carefully. After solving the first three sets, he found himself, as always, trying to anticipate the overall structure. Though each individual word had its own solution, Arthur knew there was often something more hidden in the puzzle, some deeper layer connecting everything. He enjoyed this type of challenge, one that echoed his view of the world—that if you looked closely enough, you could always find the connection that brought everything together.</p>

            <div class="w-full h-2 bg-black"></div>
            <p>Puzzle needs a second layer</p>
            <h2 class="mt-14">Answer: BRING</h2>
        </div>

        <!-- Puzzle Section -->
        <div id="puzzle" class="puzzle">
            <p>Long Puzzle. Vin Tenn but 5 times. Then the answer is the first letter of each Ven Tenn.</p>
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
import Puzzle from './puzzle-connections-component.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});


</script>