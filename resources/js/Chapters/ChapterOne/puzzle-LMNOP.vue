<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <!-- Header Section -->
        <Header :puzzle="props.puzzle" />

        <div class="mt-14" style="white-space: pre-wrap;">
            <p>Arthur lay in bed, the early morning light barely touching the edges of his room, cradling the small clock in his hands. The brass knob on the face of the device gleamed faintly as he turned it to the first of four "radio stations." Each station displayed a written question on the clock’s small screen, waiting for him to respond by adjusting the hour and minute hands. It wasn’t a complicated device, but it was just enough to stir his mind from sleep, offering the perfect blend of challenge and clarity to start his day.</p>

            <p>As he submitted his answers, the clock flashed not the time, but a small part of the hidden solution. Each correct response lit up a segment, giving him just one piece of the final puzzle. Arthur knew that by the time he solved the last station’s riddle, he would have everything he needed. The answer wasn’t in the mechanics or the time, but in the flashes of those brief clues. And like every puzzle in his life, it was about uncovering the hidden word that tied it all together—an unspoken reminder that answers were always just beneath the surface, waiting to be found.</p>

            <h2 class="mt-10">Answer: DAYS</h2>
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
import Puzzle from './puzzle-LMNOP-component.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});






</script>


