<template>
    <div class="max-w-screen-lg m-auto my-24 p-8" :class="{ 'shake': isShaking }">

        <!-- Header Sectioin -->
        <Header :puzzle="props.puzzle" />

        <div class="my-14">
            <HiddenTextSlider ref="hiddenTextSlider" @shake="triggerShake">
                <template #original-text>
                    <span @click="handleTestWordClick(1)">Test</span> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure <span @click="handleTestWordClick(2)">Test</span> in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. <span @click="handleTestWordClick(3)">Test</span> sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </template>
                <template #hidden-text>
                    <p class="p-4">
                        Dont go down that path Neo!
                    </p>
                </template>
            </HiddenTextSlider>
        </div>

        <!-- Puzzle Section -->
        <div id="puzzle" class="puzzle flex flex-col items-center justify-center">
            <div class="relative w-full max-w-4xl h-96 mx-auto">
                <div
                    v-for="(shape, index) in shapes"
                    :key="index"
                    :style="{ transform: `translate(${shape.x}px, ${shape.y}px)`, position: 'absolute' }"
                    class="cursor-move"
                    @mousedown="startDrag($event, shape)"
                >
                    <!-- Handle paths -->
                    <svg :viewBox="`${shape.minX} ${shape.minY} ${shape.boxWidth} ${shape.boxHeight}`" 
                         preserveAspectRatio="none"
                         :style="{ width: `${shape.width}px`, height: `${shape.height}px`, color: shape.color }"
                         fill="none" 
                         stroke="currentColor" 
                         :stroke-width="shape.strokeWidth">
                        <path :d="shape.d" :fill="shape.color" />
                    </svg>
                </div>
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
</template>

<script setup>
import { ref } from 'vue';
import Header from '@/Global/header.vue';
import SubmissionSection from '@/Global/submissionSection.vue';
import HiddenTextSlider from '@/Global/Puzzles/hiddenTextSlider.vue';

const props = defineProps({
    user: Object,
    puzzle: Object,
    chapter: Object,
    solution: Object,
});

let currentShape = null;
let offsetX = 0;
let offsetY = 0;

const isShaking = ref(false);
const hiddenTextSlider = ref(null);


function startDrag(event, shape) {
    currentShape = shape;
    offsetX = event.clientX - shape.x;
    offsetY = event.clientY - shape.y;
    document.addEventListener('mousemove', onDragging);
    document.addEventListener('mouseup', endDrag);
}

function onDragging(event) {
    if (currentShape) {
        currentShape.x = event.clientX - offsetX;
        currentShape.y = event.clientY - offsetY;
    }
}

function endDrag() {
    document.removeEventListener('mousemove', onDragging);
    document.removeEventListener('mouseup', endDrag);
    currentShape = null;
}

const shapes = ref([
    {
        type: 'path', 
        d: 'M99.976 100C44.758 99.987 0 55.22 0 0h200c0 55.22-44.758 99.987-99.976 100 55.218.013 99.976 44.78 99.976 100H0c0-55.22 44.758-99.987 99.976-100Z', 
        color: '#3498DB', 
        minX: 0,
        minY: 0,
        width: 100,
        height: 100,
        boxWidth: 200,
        boxHeight: 200,
        strokeWidth: 0,
        x: 400, 
        y: 20 
    },
    {
        type: 'path',
        d: 'M200 150c0-55.228-44.772-100-100-100S0 94.772 0 150h200Z', 
        color: '#3498DB', 
        minX: 0,
        minY: 0,
        width: 100,
        height: 100,
        boxWidth: 200,
        boxHeight: 200,
        strokeWidth: 0,
        x: 230, 
        y: 100 
    },
    {
        type: 'path', 
        d: 'M12 17v83c0 49.3 40.5 89.1 90.1 88 46.8-1.1 85-39.4 85.9-86.2.5-25-9.5-47.7-25.8-64C146.3 21.9 124.3 12 100 12H17c-2.7 0-5 2.3-5 5z',
        color: '#3498DB', 
        minX: 0,
        minY: 0,
        width: 50,
        height: 50,
        boxWidth: 200,
        boxHeight: 200,
        strokeWidth: 20,
        x: 50,
        y: 20 
    },
    {
        type: 'path',
        d: 'M200 150h-56.25c0-24.162-19.588-43.75-43.75-43.75S56.25 125.838 56.25 150H0C0 94.772 44.772 50 100 50s100 44.772 100 100Z',
        color: '#3498DB', 
        minX: 0,
        minY: 0,
        width: 80,
        height: 80,
        boxWidth: 200,
        boxHeight: 200,
        strokeWidth: 0,
        x: 130, 
        y: 20 
    }
]);

function handleTestWordClick(wordIndex) {
    hiddenTextSlider.value.handleTestWordClick(wordIndex);
}

function triggerShake() {
    isShaking.value = true;
    setTimeout(() => {
        isShaking.value = false;
    }, 500); // Duration of the shake effect
}

</script>

<style scoped>
@keyframes shake {
  0% { transform: translate(1px, 1px) rotate(0deg); }
  10% { transform: translate(-1px, -2px) rotate(-1deg); }
  20% { transform: translate(-3px, 0px) rotate(1deg); }
  30% { transform: translate(3px, 2px) rotate(0deg); }
  40% { transform: translate(1px, -1px) rotate(1deg); }
  50% { transform: translate(-1px, 2px) rotate(-1deg); }
  60% { transform: translate(-3px, 1px) rotate(0deg); }
  70% { transform: translate(3px, 1px) rotate(-1deg); }
  80% { transform: translate(-1px, -1px) rotate(1deg); }
  90% { transform: translate(1px, 2px) rotate(0deg); }
  100% { transform: translate(1px, -2px) rotate(-1deg); }
}

.shake {
  animation: shake 0.5s;
  animation-iteration-count: 1;
}

.no-select {
  user-select: none;
}
</style>

