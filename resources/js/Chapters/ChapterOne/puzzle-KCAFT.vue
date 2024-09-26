<template>
    <div class="max-w-screen-lg m-auto my-24 p-8">

        <!-- Header Section -->
        <Header :puzzle="props.puzzle" />

        <div class="mt-14" style="white-space: pre-wrap;">
            <p>Arthur's mornings began with a puzzle, courtesy of his sleek yet intricate alarm clock. To silence its melody, he had to navigate through four "radio stations", then enter each station’s answer using the hour and minute buttons. Once he correctly solved every station’s puzzle, he would then have to determine the final word needed to quiet the alarm.
            </p>
            <p>For Arthur, it was the perfect way to wake up—a gentle blend of challenge and clarity that set the tone for the day ahead.
            </p>
        </div>

<!--         <div>
            <audio ref="audioPlayer" src="/images/Chapter1/Alarm_Clock_Music.mp3"></audio>
            <button ref="playButton" @click="playAudio" style="display: none;">Play Audio</button>
        </div>
 -->
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
import Puzzle from './component-alarm-clock.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});



</script>